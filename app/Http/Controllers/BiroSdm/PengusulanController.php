<?php

namespace App\Http\Controllers\BiroSdm;

use Carbon\Carbon;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Program;
use App\Models\Assignment;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Assign;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengusulanController extends Controller
{
    public function index(Request $request)
    {
        $results = new Program();
        $results = $results->whereHas('assignment')->with('assignment');
        // $results = $results->with('assignment');
        $results = $this->withFilter($results, $request);

        $results = $results->orderByRaw("CASE WHEN tanggal_mulai >= CURDATE() THEN 0 ELSE 1 END, tanggal_mulai ASC")->paginate(10);

        return view('pages.biro-sdm.pengusulan.pengusulan', compact(['results', 'request']));
    }

    private function withFilter($query, $request)
    {
        $search = $request->input('search');
        if ($search) {
            $query = $query->where(function($query) use ($search) {
                $query->whereRaw('LOWER(nama_pelatihan) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(deskripsi) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(lokasi) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(penyelenggara) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($request->input('tanggal_mulai') && $request->input('tanggal_selesai')) {
            $query = $query->where('tanggal_mulai', '>=', $request->input('tanggal_mulai'))
                ->where('tanggal_selesai', '<=', $request->input('tanggal_selesai'));
        }

        return $query;
    }

    public function show(string $id)
    {
        $data = Program::with('assignment')->find($id);
        $jabatan = Jabatan::select('id', 'nama_jabatan')->get();
        $kriteria = Program::select('jabatan.nama_jabatan', 'jabatan.golongan')
        ->leftJoin('kriteria', 'program.id', 'kriteria.id_program')
        ->leftJoin('jabatan', 'kriteria.id_jabatan', 'jabatan.id')
        ->where('program.id', $id)
        ->get();

        return view('pages.biro-sdm.pengusulan.modal-pengusulan', compact('data', 'jabatan', 'kriteria'));
    }

    public function form()
    {
        return view('pages.biro-sdm.pengusulan.form-pengusulan');
    }

    public function listProgram(Request $request)
    {
        if ($request->listProgram) {
            $results = Program::select('nama_pelatihan')
            ->orderBy('tanggal_mulai', 'asc')
            ->where(function ($q) {
                $q->where('tanggal_mulai', '>=', now())
                ->orWhere('tanggal_selesai', '>=', now());
            })
            ->groupBy('nama_pelatihan', 'tanggal_mulai')
            ->get()
            ->makeHidden(['status']);
        } else if ($request->listLocation) {
            $results = Program::select('lokasi')
            ->where('nama_pelatihan', $request->nama_pelatihan)
            ->orderBy('tanggal_mulai', 'asc')
            ->where(function ($q) {
                $q->where('tanggal_mulai', '>=', now())
                ->orWhere('tanggal_selesai', '>=', now());
            })
            ->groupBy('lokasi', 'tanggal_mulai')
            ->get()
            ->makeHidden(['status']);
        } else if ($request->listSchedule) {
            $results = Program::where('nama_pelatihan', $request->nama_pelatihan)
            ->where('lokasi', $request->lokasi)
            ->select('id', 'tanggal_mulai', 'tanggal_selesai')
            ->orderBy('tanggal_mulai', 'desc')->get()
            ->makeHidden(['status']);

            foreach ($results as $result) {
                $result->tanggal_mulai = Carbon::parse($result->tanggal_mulai)->translatedFormat('j F Y');
                $result->tanggal_selesai = Carbon::parse($result->tanggal_selesai)->translatedFormat('j F Y');
            }
        }

        return response()->json($results);
    }

    public function listPegawai(Request $request)
    {
        $id_program = $request->id_program;
        $pegawai = Pegawai::select('pegawai.*', 'assignment.id as id_assignment')
        ->whereHas('kompetensi', function($query) use ($request) {
            $query->join('standar_kompetensi', 'kompetensi_pegawai.id_standar_kompetensi', 'standar_kompetensi.id')
            ->join('jabatan', 'standar_kompetensi.id_jabatan', 'jabatan.id')
            ->join('kriteria', 'jabatan.id', 'kriteria.id_jabatan')
            ->where('kriteria.id_program', $request->id_program)
            ->whereColumn('kompetensi_pegawai.kpi', '<=', 'standar_kompetensi.kpi_standar');
        })
        ->when($request->assigned, function($query) use ($request) {
            $query->join('assignment', 'pegawai.id', 'assignment.id_pegawai')
            ->where('assignment.id_program', $request->id_program);
        })->when(!$request->assigned, function($query) {
            $query->leftJoin('assignment', 'pegawai.id', 'assignment.id_pegawai')
            ->orderBy('assignment.id', 'desc');
        })
        ->get();

        if ($request->assigned) {
            return view('pages.biro-sdm.pengusulan.pengusulan-detail', compact('pegawai', 'id_program'));
        } else {
            return view('pages.biro-sdm.pengusulan.form-pengusulan-pegawai', compact('pegawai', 'id_program'));
        }
    }

    public function updateOrCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->update) {
                Assignment::where('id_program', $request->id_program)
                ->whereNotIn('id_pegawai', $request->id_pegawai)
                ->delete();
            }

            foreach ($request->id_pegawai as $pegawai) {
                Assignment::firstOrCreate(
                    [
                        'id_program' => $request->id_program,
                        'id_pegawai' => $pegawai
                    ],
                    [
                        'status' => 1,
                        'assigned_by_id' => Auth::user()->id,
                        'assigned_by_name' => Auth::user()->name
                    ]
                );
            }
            DB::commit();

            $program = Program::find($request->id_program);
            return redirect()->route('biro-sdm.pengusulan.index')->with('success', 'Berhasil memperbarui usulan pegawai ke program pelatihan ' . $program->nama_pelatihan);
        } catch (\Exception $e) {
            return $e;
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengusulkan program pelatihan');
        }
    }

    public function edit(Request $request)
    {
        $id_program = $request->id_program;
        $program = Program::find($id_program);
        $pegawai = Pegawai::select('pegawai.*', 'assignment.id as id_assignment')
        ->whereHas('kompetensi', function($query) use ($request) {
            $query->join('standar_kompetensi', 'kompetensi_pegawai.id_standar_kompetensi', 'standar_kompetensi.id')
            ->join('jabatan', 'standar_kompetensi.id_jabatan', 'jabatan.id')
            ->join('kriteria', 'jabatan.id', 'kriteria.id_jabatan')
            ->where('kriteria.id_program', $request->id_program)
            ->whereColumn('kompetensi_pegawai.kpi', '<=', 'standar_kompetensi.kpi_standar');
        })->leftJoin('assignment', 'pegawai.id', 'assignment.id_pegawai')
        ->orderBy('assignment.id', 'desc')
        ->get();

        $program->tanggal_mulai = Carbon::parse($program->tanggal_mulai)->translatedFormat('j F Y');
        $program->tanggal_selesai = Carbon::parse($program->tanggal_selesai)->translatedFormat('j F Y');

        return view('pages.biro-sdm.pengusulan.edit-pengusulan-pegawai', compact('pegawai', 'program'));
    }

    public function delete(Request $request)
    {
        $program = Program::find($request->id_program);
        DB::beginTransaction();
        try {
            Assignment::where('id_program', $request->id_program)->delete();
            DB::commit();
            return redirect()->route('biro-sdm.pengusulan.index')->with('success', 'Berhasil menghapus usulan pegawai ke program pelatihan' . $program->nama_pelatihan);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengusulkan program pelatihan');
        }
    }
}

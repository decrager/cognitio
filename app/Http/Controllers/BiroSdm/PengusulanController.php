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
        $results = $results->whereHas('assignment')
        // ->where('tanggal_selesai', '>=', now())
        ->where(function ($q) {
            $q->onGoingProgram();
        })
        ->with('assignment');
        // $results = $results->with('assignment');
        $results = $this->withFilter($results, $request);

        $results = $results->orderByRaw("CASE WHEN tanggal_mulai > CURDATE() THEN 0 ELSE 1 END, tanggal_mulai ASC")->paginate(10);

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

        if ($request->input('tanggal_mulai')) {
            $query = $query->where('tanggal_mulai', '>=', $request->input('tanggal_mulai'));
        }

        if ($request->input('tanggal_selesai')) {
            $query = $query->where('tanggal_selesai', '<=', $request->input('tanggal_selesai'));
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

        return view('pages.biro-sdm.pengusulan.modal-detail-program', compact('data', 'jabatan', 'kriteria'));
    }

    public function form()
    {
        return view('pages.biro-sdm.pengusulan.form-pengusulan');
    }

    public function listProgram(Request $request)
    {
        if ($request->listProgram) {
            $results = Program::select('nama_pelatihan')
            ->orderBy('nama_pelatihan')
            ->where(function ($q) {
                $q->where('tanggal_mulai', '>=', now());
                // ->orWhere('tanggal_selesai', '>=', now());
            })
            ->groupBy('nama_pelatihan')
            ->get()
            ->makeHidden(['status']);
        } else if ($request->listLocation) {
            $results = Program::select('lokasi')
            ->where('nama_pelatihan', $request->nama_pelatihan)
            ->where(function ($q) {
                $q->onGoingProgram();
            })
            // ->where(function ($q) {
            //     $q->where('tanggal_mulai', '>=', now())
            //     ->orWhere('tanggal_selesai', '>=', now());
            // })
            ->groupBy('lokasi')
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
        $program = Program::find($id_program);
        $pegawais = Program::select('pegawai.id')
        ->join('kriteria', 'program.id', 'kriteria.id_program')
        ->join('jabatan', 'kriteria.id_jabatan', 'jabatan.id')
        ->join('pegawai', 'jabatan.id', 'pegawai.id_jabatan')
        ->where('program.id', $id_program)
        ->groupBy('pegawai.id')
        ->get();

        $pegawai = Pegawai::select('pegawai.*')
        ->whereHas('kompetensi', function ($query) {
            $query->join('standar_kompetensi', 'kompetensi_pegawai.id_standar_kompetensi', 'standar_kompetensi.id')
                    ->whereColumn('kompetensi_pegawai.kpi', '<', 'standar_kompetensi.kpi_standar');
        })
        ->leftJoin('assignment', function ($q) use ($id_program, $request) {
            $q->on('pegawai.id', '=', 'assignment.id_pegawai')
            ->when(!$request->assigned, function ($query) use ($request) {
                $query->where('assignment.id_program', '=', $request->id_program);
            });
        })
        ->selectRaw("CASE WHEN assignment.id_program IS NOT NULL THEN 'Assigned' ELSE 'Not Assigned' END AS assignment_status, assignment.id as id_assignment")
        ->when($request->assigned, function ($query) use ($request) {
            $query->where('assignment.id_program', $request->id_program);
        })
//        ->when(!$request->assigned, function ($query) use ($request) {
//            $query->where(function ($query) use ($request) {
//                $query->where('assignment.id_program', '!=', $request->id_program)
//                        ->orWhereNull('assignment.id_program');
//            });
//        })
        ->whereIn('pegawai.id', $pegawais->pluck('id'))
        ->orderBy('assignment.id', 'desc')
        ->get();

        if ($request->assigned) {
            return view('pages.biro-sdm.pengusulan.pengusulan-detail', compact('pegawai', 'id_program', 'program'));
        } else {
            // Kuota & Checked Ids, buat handle validasi dan pengusulan ter check
            $kuota  = Program::find($id_program)->kuota;
            $checked_ids_arr = Assignment::where('id_program', $id_program)->pluck('id_pegawai')->toArray();
            $checked_ids = implode(',', $checked_ids_arr);
            return view('pages.biro-sdm.pengusulan.form-pengusulan-pegawai', compact('pegawai', 'id_program','kuota','checked_ids'));
        }
    }

    public function updateOrCreate(Request $request)
    {
        $now = Assignment::where('id_program', $request->id_program)->get();

        if (count($request->id_pegawai) == 0) {
            goto skip;
        }

        if ($now->count() > 0) {
            $program = Program::find($request->id_program);
            $now = $now->pluck('id_pegawai')->toArray();

            // Find added and removed employees
            $added = array_diff($request->id_pegawai, $now); // Employees in request but not in now
            $removed = array_diff($now, $request->id_pegawai); // Employees in now but not in request

            // Count added and removed employees
            $countAdded = count($added);
            $countRemoved = count($removed);
            $total = $countAdded + count($now); // Total employees after addition

            if ($total > $program->kuota) {
                return redirect()->back()->with('error', 'Jumlah pegawai yang diusulkan melebihi batas program.');
            }
        }

        skip:

        // If Checked Ids Empty
        if ($request->checked_ids == '') {
            return redirect()->back()->with('error', 'Pilih pegawai yang akan diusulkan.');
        }

        DB::beginTransaction();
        try {
            //checkedIds
            $checked_ids = explode(',', $request->checked_ids);

            // Comment: $request->update, handling pengusulan dari form tambah pengusulan, jika tidak di komen check box yang dihapus tidak hilang
            //if ($request->update) {
            Assignment::where('id_program', $request->id_program)
            ->whereNotIn('id_pegawai', $checked_ids)
            ->delete();
            //}

            $pegawai_auth = Pegawai::where('id_user', Auth::user()->id)->first();

            foreach ($checked_ids as $pegawai) {
                Assignment::firstOrCreate(
                    [
                        'id_program' => $request->id_program,
                        'id_pegawai' => $pegawai
                    ],
                    [
                        'status' => 1,
                        'assigned_by_id' => Auth::user()->id,
                        'assigned_by_name' => $pegawai_auth->nama,
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
        $kuota = $program->kuota;
        $pegawai = Pegawai::select('pegawai.*', 'assignment.id as id_assignment')
        ->whereHas('kompetensi', function($query) use ($request) {
            $query->join('standar_kompetensi', 'kompetensi_pegawai.id_standar_kompetensi', 'standar_kompetensi.id')
            ->join('jabatan', 'standar_kompetensi.id_jabatan', 'jabatan.id')
            ->join('kriteria', 'jabatan.id', 'kriteria.id_jabatan')
            ->where('kriteria.id_program', $request->id_program)
            ->whereColumn('kompetensi_pegawai.kpi', '<=', 'standar_kompetensi.kpi_standar');
        })->leftJoin('assignment', function ($q) use ($request){
            $q->on('pegawai.id', '=', 'assignment.id_pegawai')
            ->where('assignment.id_program', '=', $request->id_program);
        })
        ->orderBy('assignment.id', 'desc')
        ->get();

        $program->tanggal_mulai = Carbon::parse($program->tanggal_mulai)->translatedFormat('j F Y');
        $program->tanggal_selesai = Carbon::parse($program->tanggal_selesai)->translatedFormat('j F Y');

        $assignment = Assignment::where('id_program', $id_program)->get();
        $checked_ids_arr = $assignment->pluck('id_pegawai')->toArray();
        $checked_ids = implode(',', $checked_ids_arr);

        return view('pages.biro-sdm.pengusulan.edit-pengusulan-pegawai', compact('pegawai', 'program','kuota','checked_ids'));
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

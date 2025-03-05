<?php

namespace App\Http\Controllers\BiroSdm;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Program;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenetapanController extends Controller
{
    public function index(Request $request)
    {
        $results = new Program();
        $results = $results::select('program.*')
        ->withCount([
            'assignment as jml_usulan' => function($query) {
                $query->where('status', 1);
            },
            'assignment as jml_konfirm' => function($query) {
                $query->where('status', 2);
            },
            'assignment as jml_tolak' => function($query) {
                $query->where('status', 3);
            },
            'assignment as jml_tetap' => function($query) {
                $query->where('status', 4);
            }
        ])
        ->whereHas('assignment')->with('assignment');
        $results = $this->withFilter($results, $request);

        $results = $results->orderByRaw("CASE WHEN tanggal_mulai >= CURDATE() THEN 0 ELSE 1 END, tanggal_mulai ASC")->paginate(10);

        return view('pages.biro-sdm.penetapan.penetapan', compact(['results', 'request']));
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

        return view('pages.biro-sdm.penetapan.modal-detail-program', compact('data', 'jabatan', 'kriteria'));
    }

    public function listPegawai(Request $request)
    {
        $id_program = $request->id_program;
        $pegawai = Pegawai::select('pegawai.*', 'assignment.id as id_assignment', 'assignment.status')
        ->whereHas('kompetensi', function($query) use ($request) {
            $query->join('standar_kompetensi', 'kompetensi_pegawai.id_standar_kompetensi', 'standar_kompetensi.id')
            ->join('jabatan', 'standar_kompetensi.id_jabatan', 'jabatan.id')
            ->join('kriteria', 'jabatan.id', 'kriteria.id_jabatan')
            ->where('kriteria.id_program', $request->id_program)
            ->whereColumn('kompetensi_pegawai.kpi', '<=', 'standar_kompetensi.kpi_standar');
        })
        ->join('assignment', 'pegawai.id', 'assignment.id_pegawai')
        ->where('assignment.id_program', $request->id_program)
        ->orderByRaw("FIELD(assignment.status, 2, 1, 3, 4)")
        ->get();

        return view('pages.biro-sdm.penetapan.penetapan-detail', compact('pegawai', 'id_program'));
    }

    public function update(Request $request)
    {
        $id_program = $request->id_program;
        $program = Program::find($id_program);

        DB::beginTransaction();
        try {
            foreach ($request->id_pegawai as $row) {
                Assignment::where('id_program', $id_program)
                ->where('id_pegawai', $row)
                ->update(['status' => 4]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Berhasil menetapkan pegawai ke Pelatihan ' . $program->nama_pelatihan);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data');
        }
    }

    public function delete(Request $request)
    {
        $id_program = $request->id_program;
        $program = Program::find($id_program);

        DB::beginTransaction();
        try {
            Assignment::where('id_program', $id_program)
            ->where('status', 3)
            ->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Berhasil menghapus pegawai yang ditolak dari Pelatihan ' . $program->nama_pelatihan);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data');
        }
    }
}

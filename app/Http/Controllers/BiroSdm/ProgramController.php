<?php

namespace App\Http\Controllers\BiroSdm;

use App\Models\Assignment;
use App\Service\BiroSdmAssignment;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use App\Models\Jabatan;
use App\Models\Program;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $results = new Program();
        $results = $this->withFilter($results, $request);
        $results = $results->where('tanggal_mulai', '>=', now())->orderBy('tanggal_mulai')->paginate(10);

        $jabatan = Jabatan::select('id', 'nama_jabatan')->get();

        return view('pages.biro-sdm.program.program', compact(['results', 'request', 'jabatan']));
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
        $data = Program::find($id);
        $jabatan = Jabatan::select('id', 'nama_jabatan')->get();
        $kriteria = Program::select('jabatan.nama_jabatan', 'jabatan.golongan')
        ->leftJoin('kriteria', 'program.id', 'kriteria.id_program')
        ->leftJoin('jabatan', 'kriteria.id_jabatan', 'jabatan.id')
        ->where('program.id', $id)
        ->get();

        return view('pages.biro-sdm.program.modal-program', compact('data', 'jabatan', 'kriteria'));
    }

    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            $program = Program::create([
                'nama_pelatihan' => $request->nama_pelatihan,
                'deskripsi' => $request->deskripsi,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'lokasi' => $request->lokasi,
                'kuota' => $request->kuota,
                'penyelenggara' => $request->penyelenggara
            ]);

            foreach ($request->jabatan as $row) {
                Kriteria::create([
                    'id_program' => $program->id,
                    'id_jabatan' => $row
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data program dan pelatihan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        DB::beginTransaction();

        try {
            $program = Program::find($id);
            $program->update([
                'nama_pelatihan' => $request->nama_pelatihan,
                'deskripsi' => $request->deskripsi,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'lokasi' => $request->lokasi,
                'kuota' => $request->kuota,
                'penyelenggara' => $request->penyelenggara
            ]);

            Kriteria::where('id_program', $id)->delete();
            foreach ($request->jabatan as $row) {
                Kriteria::create([
                    'id_program' => $program->id,
                    'id_jabatan' => $row
                ]);
            }
            DB::commit();
            return redirect()->route('biro-sdm.program.index')->with('success', 'Data program dan pelatihan berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            Program::find($id)->delete();
            Kriteria::where('id_program', $id)->delete();
            DB::commit();

            return redirect()->route('biro-sdm.program.index')->with('success', 'Data program dan pelatihan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function programFinalization($id_program, Request $request)
    {
        $program = Program::with(['kriteria'])->find($id_program);

        $service = new BiroSdmAssignment();
        $pegawai = $service->getAssignmentEmployeeByProgramId($id_program, $request)
           ->whereIn("assignment.status", [2,4])->paginate(20);

        // Mapping Status
        $pegawai->map(function ($item) use ($program, $service) {
            $item->status_text = $service->mappingStatusAssignment($item->status);
        });

        return view('pages.biro-sdm.program-finalize', compact('program', 'pegawai','request'));
    }

    public function updateStatusAssignmentFinal($id_assignment)
    {
        $assignment = Assignment::find($id_assignment);
        $assignment->status = 4;
        $assignment->save();

        return redirect()->back()->with('success', 'Status assignment berhasil diperbarui');
    }

    public function printFinalization($id_program, Request $request)
    {
        $program = Program::with(['kriteria'])->find($id_program);

        $service = new BiroSdmAssignment();
        $pegawai = $service->getAssignmentEmployeeByProgramId($id_program, $request)->where("assignment.status",4)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.assignment-print', compact('program', 'pegawai'));
        return $pdf->download('program.pdf');
    }
}

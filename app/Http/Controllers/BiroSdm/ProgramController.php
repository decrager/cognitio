<?php

namespace App\Http\Controllers\BiroSdm;

use App\Models\Assignment;
use App\Service\BiroSdmAssignment;
use Carbon\Carbon;
use App\Models\Jabatan;
use App\Models\Program;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $results = new Program();
        $results = $this->withFilter($results, $request);
        $results = $results->orderByRaw("CASE WHEN tanggal_mulai > CURDATE() THEN 0 ELSE 1 END, tanggal_mulai ASC")->paginate(10);

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
        $data = Program::find($id);
        $jabatan = Jabatan::select('id', 'nama_jabatan')->get();
        $kriteria = Program::select('jabatan.nama_jabatan', 'jabatan.golongan')
        ->leftJoin('kriteria', 'program.id', 'kriteria.id_program')
        ->leftJoin('jabatan', 'kriteria.id_jabatan', 'jabatan.id')
        ->where('program.id', $id)
        ->get();

        return view('pages.biro-sdm.program.modal-program', compact('data', 'jabatan', 'kriteria'));
    }

    private function validateProgram($request, $isCreate = true)
    {
        $rules = [
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|date|after:now',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'lokasi' => 'required',
            'kuota' => 'required|numeric|min:1',
            'penyelenggara' => 'required',
            'jabatan' => 'required'
        ];

// If the request is for creating a new record, add the unique validation rule
        // if ($isCreate) {
        //     $rules['nama_pelatihan'] = 'required';
        // } else {
        //     // If the request is for updating an existing record, ignore the unique validation for the current record
        //     $rules['nama_pelatihan'] = 'required,' . $request->id;
        // }

        $messages = [
            'nama_pelatihan.required' => 'Nama pelatihan harus diisi',
            'nama_pelatihan.unique' => 'Nama pelatihan sudah ada',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal',
            'tanggal_mulai.after' => 'Tanggal mulai harus setelah hari ini',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.date' => 'Tanggal selesai harus berupa tanggal',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai',
            'lokasi.required' => 'Lokasi harus diisi',
            'kuota.required' => 'Kuota harus diisi',
            'kuota.numeric' => 'Kuota harus berupa angka',
            'kuota.min' => 'Kuota minimal 1',
            'penyelenggara.required' => 'Penyelenggara harus diisi',
            'jabatan.required' => 'Jabatan harus diisi'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }

    public function create(Request $request)
    {

        $validator = $this->validateProgram($request);

        // Check if validation fails
        if ($validator->fails()) {
            // Return JSON with validation errors
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        // If validation passes, proceed with the rest of the logic
        if ($request->action != 'submit') {
            // Return JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Data program dan pelatihan berhasil disimpan'
            ]);
        }

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
        $validator = $this->validateProgram($request, false);

        // Check if validation fails
        if ($validator->fails()) {
            // Return JSON with validation errors
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        // If validation passes, proceed with the rest of the logic
        if ($request->action != 'submit') {
            // Return JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Data program dan pelatihan berhasil disimpan'
            ]);
        }


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
            Assignment::where('id_program', $id)->delete();
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

        return view('pages.biro-sdm.program.program-finalize', compact('program', 'pegawai','request'));
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
        $pegawai = $service->getAssignmentEmployeeByProgramId($id_program, $request)->where("assignment.status", 4)->get();

        $file_name = 'Program Pelatihan - ' . $program->nama_pelatihan . ' - ' . Carbon::now()->format('d-m-Y H:i:s');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.assignment-print', compact('program', 'pegawai'));
        return $pdf->download($file_name . '.pdf');
    }
}

<?php

namespace App\Service;

use App\Models\Pegawai;

class BiroSdmAssignment
{
    public function getAssignmentEmployeeByProgramId($program_id, $request)
    {
        $pegawai = Pegawai::join("assignment", "assignment.id_pegawai", "=", "pegawai.id")
            ->where("assignment.id_program", $program_id)
            ->leftJoin("jabatan", "pegawai.id_jabatan", "=", "jabatan.id")
            ->leftJoin("unit_kerja", "pegawai.id_unit", "=", "unit_kerja.id")
            ->select("pegawai.*","assignment.id as id_assignment","assignment.status", "jabatan.nama_jabatan", "unit_kerja.nama_unit");

        if ($request->input('search')) {
            $pegawai = $pegawai->where(function($query) use ($request) {
                $query->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->input('search')) . '%'])
                      ->orWhereRaw('LOWER(nama_jabatan) LIKE ?', ['%' . strtolower($request->input('search')) . '%'])
                      ->orWhereRaw('LOWER(nama_unit) LIKE ?', ['%' . strtolower($request->input('search')) . '%']);
            });
        }

        $pegawai = $pegawai->orderBy("assignment.status","ASC");

        return $pegawai;
    }

    public function mappingStatusAssignment(int $status)
    {
        $status_assignment = [
            1 => "Usulan",
            2 => "Konfirmasi",
            3 => "Tidak Dikonfirmasi",
            4 => "Ditetapkan",
        ];

        return $status_assignment[$status];
    }
}

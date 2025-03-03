<?php

namespace App\Service;

use App\Models\Pegawai;

class BiroSdmDashboard
{
    public function getPegawaiNonKompeten($get = true)
    {
        $pegawai = Pegawai::join("kompetensi_pegawai", "pegawai.id", "=", "kompetensi_pegawai.id_pegawai")
            ->join("standar_kompetensi", "kompetensi_pegawai.id_standar_kompetensi", "=", "standar_kompetensi.id")

            ->whereColumn("kompetensi_pegawai.kpi", "<", "standar_kompetensi.kpi_standar")
            ->isPegawai()
            ->select("pegawai.*")
            ->leftJoin("jabatan", "pegawai.id_jabatan", "=", "jabatan.id")
            ->leftJoin("unit_kerja", "pegawai.id_unit", "=", "unit_kerja.id")
            ->select("pegawai.*", "jabatan.nama_jabatan", "unit_kerja.nama_unit")
            ->groupBy("pegawai.id");

        if ($get){
            $pegawai = $pegawai->get();
        }

        return $pegawai;
    }
}

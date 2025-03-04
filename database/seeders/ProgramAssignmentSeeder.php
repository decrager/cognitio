<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Kriteria;
use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $program;
    protected $id_jabatan = 1;

    public function run(): void
    {
        try {
            DB::beginTransaction();
            $this->createProgram();
            $this->generateKriteria();
            $this->generateAssignment();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createProgram()
    {
        // Now add 5 & 10 days
        $date_start = date('Y-m-d', strtotime('+5 days', strtotime(date('Y-m-d'))));
        $date_end = date('Y-m-d', strtotime('+10 days', strtotime(date('Y-m-d'))));

        $mixed_name = ['Pelatihan Kepemimpinan', 'Pelatihan Keterampilan', 'Pelatihan Kepemimpinan', 'Pelatihan Keterampilan', 'Pelatihan Kepemimpinan', 'Pelatihan Keterampilan', 'Pelatihan Kepemimpinan', 'Pelatihan Keterampilan', 'Pelatihan Kepemimpinan', 'Pelatihan Keterampilan'];
        $data = Program::create([
          "nama_pelatihan" => $mixed_name[rand(0, 9)],
          "deskripsi" => "Pelatihan ini bertujuan untuk meningkatkan keterampilan dan keahlian dalam bidang yang dipilih",
          "lokasi" => "Gedung Serbaguna",
          "penyelenggara" => "Biro SDM",
          "tanggal_mulai" => $date_start,
          "tanggal_selesai" => $date_end,
          "kuota" => 10,
        ]);

        $this->program = $data;
    }

    public function generateKriteria()
    {
        Kriteria::create([
            "id_program" => $this->program->id,
            "id_jabatan" => $this->id_jabatan,
        ]);
    }

    public function generateAssignment()
    {
        // Get Kompetensi Pegawai Join By Standar Kompetensi & Pegawai
        $query = "
           SELECT MIN(kompetensi_pegawai.kpi), MIN(standar_kompetensi.kpi_standar), pegawai.id
                FROM kompetensi_pegawai
                LEFT JOIN pegawai ON kompetensi_pegawai.id_pegawai = pegawai.id
                LEFT JOIN standar_kompetensi ON kompetensi_pegawai.id_standar_kompetensi = standar_kompetensi.id
                WHERE pegawai.id_jabatan = {$this->id_jabatan} AND pegawai.id_unit = 3 AND kpi < kpi_standar GROUP BY pegawai.id LIMIT 10
        ";

        $data = DB::select($query);

        foreach ($data as $item) {
            Assignment::create([
                "id_program" => $this->program->id,
                "id_pegawai" => $item->id,
                "status" => 1,
                "assigned_by_id" => 1246,
                "assigned_by_name" => "Biro SDM"
            ]);
        }
    }
}

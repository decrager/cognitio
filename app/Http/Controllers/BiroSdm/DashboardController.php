<?php

namespace App\Http\Controllers\BiroSdm;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Pegawai;
use App\Models\Program;
use App\Service\BiroSdmDashboard;

class DashboardController extends Controller
{
    public function index()
    {
        $serviceDashboard = new BiroSdmDashboard();

        $jumlah_pegawai = Pegawai::get()->count();

        $program = new Program();
        $jumlah_program = $program->get()->count();
        $program_limit = $program->limit(10)->get();
        $pegawai_non_kompeten = $serviceDashboard->getPegawaiNonKompeten()->count();
        $pegawai_kompeten = $jumlah_pegawai - $pegawai_non_kompeten;

        $assignmet = Assignment::get();

        return view('pages.biro-sdm.dashboard', [
            'jumlah_program' => $jumlah_program,
            'jumlah_pegawai' => $jumlah_pegawai,
            'pegawai_non_kompeten' => $pegawai_non_kompeten,
            'pegawai_kompeten' => $pegawai_kompeten,
            'program' => $program_limit,
            'pegawai' => $serviceDashboard->getPegawaiNonKompeten(false)->limit(10)->get(),
            'jumlah_assignment_usulan' => $assignmet->where('status', 1)->count(),
            'jumlah_assignment_konfirmasi' => $assignmet->where('status', 2)->count(),
            'jumlah_assignment_tidak_dikonfirmasi' => $assignmet->where('status', 3)->count(),
            'jumlah_assignment_ditetapkan' => $assignmet->where('status', 4)->count(),
        ]);
    }
}

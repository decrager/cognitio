<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\KompetensiPegawai;
use App\Models\Assignment;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        // Mendapatkan ID user yang sedang login
        $id_user = Auth::id();

        // Mengambil data pegawai berdasarkan id_user
        $pegawai = Pegawai::where('id_user', $id_user)->first();

        // Jika pegawai ditemukan, ambil kompetensi pegawai berdasarkan id_pegawai
        $kompetensi = [];
        if ($pegawai) {
            $kompetensi = KompetensiPegawai::with('standarKompetensi')
                ->where('id_pegawai', $pegawai->id)
                ->get();

            $jumlah_kpi_kurang = KompetensiPegawai::join('standar_kompetensi', 'kompetensi_pegawai.id_standar_kompetensi', '=', 'standar_kompetensi.id')
                ->where('kompetensi_pegawai.id_pegawai', $pegawai->id)
                ->whereColumn('kompetensi_pegawai.kpi', '<', 'standar_kompetensi.kpi_standar')
                ->count();

            $jumlah_kpi_cukup = KompetensiPegawai::join('standar_kompetensi', 'kompetensi_pegawai.id_standar_kompetensi', '=', 'standar_kompetensi.id')
                ->where('kompetensi_pegawai.id_pegawai', $pegawai->id)
                ->whereColumn('kompetensi_pegawai.kpi', '>=', 'standar_kompetensi.kpi_standar')
                ->count();
        }

        // Jika pegawai ditemukan, ambil assignment pegawai berdasarkan id_pegawai
        $assignment = [];
        if ($pegawai) {
            $assignment = Assignment::with('Program')
                ->where('id_pegawai', $pegawai->id)
                ->get();

            $jumlah_assignment_usulan = Assignment::where('id_pegawai', $pegawai->id)->where('status', 1)->count();
            $jumlah_assignment_konfirmasi = Assignment::where('id_pegawai', $pegawai->id)->where('status', 2)->count();
            $jumlah_assignment_tidak_dikonfirmasi = Assignment::where('id_pegawai', $pegawai->id)->where('status', 3)->count();
            $jumlah_assignment_ditetapkan = Assignment::where('id_pegawai', $pegawai->id)->where('status', 4)->count();
        }

        // Passing data pegawai ke view dashboard
        return view('pages.pegawai.dashboard', compact('pegawai', 'kompetensi', 'jumlah_kpi_kurang', 'jumlah_kpi_cukup', 'assignment', 'jumlah_assignment_usulan', 'jumlah_assignment_konfirmasi', 'jumlah_assignment_tidak_dikonfirmasi', 'jumlah_assignment_ditetapkan'));
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

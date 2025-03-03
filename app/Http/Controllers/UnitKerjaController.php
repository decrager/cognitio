<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\Assignment;

class UnitKerjaController extends Controller
{
    public function dashboard(){
        // Mendapatkan ID user yang sedang login
        $id_user = Auth::id();

        // Mengambil data pegawai berdasarkan id_user
        $pegawai = Pegawai::where('id_user', $id_user)->first();

        // Jika pegawai ditemukan, ambil assignment pegawai berdasarkan unit kerja
        $assignment = [];
        if ($pegawai) {
            $assignment = Assignment::with('Program')
            ->join('pegawai', 'pegawai.id', '=', 'assignment.id_pegawai')
            ->where('pegawai.id_unit', $pegawai->id_unit) 
            // ->select('assignment.*') // Memilih hanya data dari assignment
            ->get();

            $jumlah_assignment_usulan = Assignment::join('pegawai', 'pegawai.id', '=', 'assignment.id_pegawai')
                ->where('pegawai.id_unit', $pegawai->id_unit)
                ->where('assignment.status', 1)
                ->count();

            $jumlah_assignment_konfirmasi = Assignment::join('pegawai', 'pegawai.id', '=', 'assignment.id_pegawai')
                ->where('pegawai.id_unit', $pegawai->id_unit)
                ->where('assignment.status', 2)
                ->count();

            $jumlah_assignment_tidak_dikonfirmasi = Assignment::join('pegawai', 'pegawai.id', '=', 'assignment.id_pegawai')
                ->where('pegawai.id_unit', $pegawai->id_unit)
                ->where('assignment.status', 3)
                ->count();

            $jumlah_assignment_ditetapkan = Assignment::join('pegawai', 'pegawai.id', '=', 'assignment.id_pegawai')
                ->where('pegawai.id_unit', $pegawai->id_unit)
                ->where('assignment.status', 4)
                ->count();
        }

        // Passing data pegawai ke view dashboard
        return view('pages.unit-kerja.dashboard', compact('pegawai', 'assignment', 'jumlah_assignment_usulan', 'jumlah_assignment_konfirmasi', 'jumlah_assignment_tidak_dikonfirmasi', 'jumlah_assignment_ditetapkan'));
    }

    public function usulan_pelatihan(){
        echo "usulan pelatihan";
        die();
    }

    /**
     * Display a listing of the resource.
     */
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

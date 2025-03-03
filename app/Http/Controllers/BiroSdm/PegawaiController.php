<?php

namespace App\Http\Controllers\BiroSdm;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $results = new Pegawai();

        $results = $results->with('jabatan')->isPegawai();
        $results = $this->withFilter($results, $request);

        $results = $results->paginate(10);
        return view('pages.biro-sdm.pegawai', compact(['results','request']));
    }

    private function withFilter($query, $request)
    {
        $search = $request->input('search');
        if ($search) {
            $query = $query->where(function($query) use ($search) {
                $query->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(nip) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(nik) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(telepon) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(alamat) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereHas('jabatan', function($query) use ($search) {
                          $query->whereRaw('LOWER(tipe_jabatan) LIKE ?', ['%' . strtolower($search) . '%'])
                                ->orWhereRaw('LOWER(nama_jabatan) LIKE ?', ['%' . strtolower($search) . '%']);
                      });
            });
        }

        if ($request->input('tipe')){
            $query = $query->where('tipe', $request->input('tipe'));
        }

        return $query;
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
        $data = Pegawai::with('standar_kompetensi','jabatan')->find($id);

        // Return The Modal Component
        return view('pages.biro-sdm.modal-pegawai', compact('data'));
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

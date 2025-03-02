<?php

namespace App\Http\Controllers\BiroSdm;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $results = new Program();

        $results = $this->withFilter($results, $request);

        $results = $results->paginate(10);

        return view('pages.biro-sdm.program', compact(['results', 'request']));
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class BiroSdmController extends Controller
{
    public function index()
    {
        return view('pages.biro-sdm.dashboard');
    }
}

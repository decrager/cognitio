<?php

use App\Models\Pegawai;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\BiroSdm\PegawaiController as BiroSdmPegawaiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // Redirect to the login page
    return redirect('/login');
})->name('login-form');

Route::get('/login', [Authentication::class, 'form'])->name('login-form');

Route::post('/login', [Authentication::class, 'authenticate'])->name('login-action');
Route::get('/logout', [Authentication::class, 'logout'])->name('logout-action');

Route::group(['prefix' => 'biro-sdm', 'middleware' => 'cekRole:biro-sdm'], function () {
    Route::get('/dashboard', function () {
        return view('pages.biro-sdm.dashboard');
    })->name('dashboard.biro-sdm');

    Route::get('/pegawai', [BiroSdmPegawaiController::class,'index'])->name('pegawai.biro-sdm');
});

Route::group(['prefix' => 'unit-kerja', 'middleware' => 'cekRole:unit-kerja'], function () {
    Route::get('/dashboard', function () {
        return view('pages.unit-kerja.dashboard');
    })->name('dashboard.unit-kerja');
});

Route::group(['prefix' => 'pegawai', 'middleware' => 'cekRole:pegawai'], function () {
    // Route::get('/dashboard', function () {
    //     return view('pages.pegawai.dashboard');
    // })->name('dashboard.pegawai');
    Route::get('/dashboard', [PegawaiController::class, 'dashboard'])->name('dashboard.pegawai');
});


Route::get('/test', function () {
    $user = Auth::user();
    $user['name'] = Pegawai::where('id_user', $user->id)->first()->nama;

    return $user;
});

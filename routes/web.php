<?php

use App\Models\Pegawai;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\BiroSdm\PegawaiController as BiroSdmPegawaiController;
use App\Http\Controllers\BiroSdm\ProgramController as BiroSdmProgramController;
use App\Http\Controllers\BiroSdm\DashboardController as BiroSdmDashboardController;

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

// Example of generating PDF
Route::get('/generate-pdf-example', [\App\Http\Controllers\PDFExampleController::class, 'generatePDF']);

Route::get('/', function () {
    // Redirect to the login page
    return redirect('/login');
})->name('login-form');

Route::get('/login', [Authentication::class, 'form'])->name('login-form');

Route::post('/login', [Authentication::class, 'authenticate'])->name('login-action');
Route::get('/logout', [Authentication::class, 'logout'])->name('logout-action');

Route::group(['prefix' => 'biro-sdm', 'middleware' => 'cekRole:biro-sdm'], function () {
    Route::get('/dashboard', [BiroSdmDashboardController::class, 'index'])->name('dashboard.biro-sdm');

    Route::get('/pegawai', [BiroSdmPegawaiController::class,'index'])->name('biro-sdm.pegawai.index');
    Route::get('/pegawai/{id}', [BiroSdmPegawaiController::class,'show'])->name('biro-sdm.pegawai.show');

    Route::get('/program-pelatihan', [BiroSdmProgramController::class, 'index'])->name('biro-sdm.program.index');
});

Route::group(['prefix' => 'unit-kerja', 'middleware' => 'cekRole:unit-kerja'], function () {
    // Route::get('/dashboard', function () {
    //     return view('pages.unit-kerja.dashboard');
    // })->name('dashboard.unit-kerja');
    Route::get('/dashboard', [UnitKerjaController::class, 'dashboard'])->name('dashboard.unit-kerja');
    Route::get('/usulan_pelatihan', [UnitKerjaController::class, 'usulan_pelatihan'])->name('usulan_pelatihan.unit-kerja');
    Route::post('/update_status_assignment/{id}', [UnitKerjaController::class, 'update_status_assignment'])->name('update_status_assignment.unit-kerja');
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

<?php

use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\BiroSdm\PegawaiController as BiroSdmPegawaiController;
use App\Http\Controllers\BiroSdm\ProgramController as BiroSdmProgramController;
use App\Http\Controllers\BiroSdm\DashboardController as BiroSdmDashboardController;
use App\Http\Controllers\BiroSdm\PenetapanController as BiroSdmPenetapanController;
use App\Http\Controllers\BiroSdm\PengusulanController as BiroSdmPengusulanController;
use App\Models\KompetensiPegawai;

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

Route::get('/modal-pegawai/{id}', [BiroSdmPegawaiController::class,'show'])->name('biro-sdm.pegawai.show');
Route::get('/modal-program/get/{id}', [BiroSdmPengusulanController::class, 'show'])->name('biro-sdm.pengusulan.show');

Route::group(['prefix' => 'biro-sdm', 'middleware' => 'cekRole:biro-sdm'], function () {
    Route::get('/dashboard', [BiroSdmDashboardController::class, 'index'])->name('dashboard.biro-sdm');

    Route::get('/pegawai', [BiroSdmPegawaiController::class,'index'])->name('biro-sdm.pegawai.index');

    Route::get('/program-pelatihan', [BiroSdmProgramController::class, 'index'])->name('biro-sdm.program.index');
    Route::get('/program-pelatihan/{id}', [BiroSdmProgramController::class, 'show'])->name('biro-sdm.program.show');
    Route::post('/program-pelatihan/create', [BiroSdmProgramController::class, 'create'])->name('biro-sdm.program.create');
    Route::post('/program-pelatihan/update/{id}', [BiroSdmProgramController::class, 'update'])->name('biro-sdm.program.update');
    Route::post('/program-pelatihan/delete/{id}', [BiroSdmProgramController::class, 'delete'])->name('biro-sdm.program.delete');

    Route::get('/pengusulan', [BiroSdmPengusulanController::class, 'index'])->name('biro-sdm.pengusulan.index');
    Route::get('/pengusulan/form', [BiroSdmPengusulanController::class, 'form'])->name('biro-sdm.pengusulan.form');
    Route::get('/listProgram', [BiroSdmPengusulanController::class, 'listProgram'])->name('biro-sdm.pengusulan.listProgram');
    Route::get('/listPegawai', [BiroSdmPengusulanController::class, 'listPegawai'])->name('biro-sdm.pengusulan.listPegawai');
    Route::post('/pengusulan/updateOrCreate', [BiroSdmPengusulanController::class, 'updateOrCreate'])->name('biro-sdm.pengusulan.updateOrCreate');
    Route::get('/pengusulan/edit', [BiroSdmPengusulanController::class, 'edit'])->name('biro-sdm.pengusulan.edit');
    Route::post('/pengusulan/delete', [BiroSdmPengusulanController::class, 'delete'])->name('biro-sdm.pengusulan.delete');

    Route::get('/penetapan', [BiroSdmPenetapanController::class, 'index'])->name('biro-sdm.penetapan.index');
    Route::get('/penetapan/listPegawai', [BiroSdmPenetapanController::class, 'listPegawai'])->name('biro-sdm.penetapan.listPegawai');
    Route::post('/penetapan/update', [BiroSdmPenetapanController::class, 'update'])->name('biro-sdm.penetapan.update');
    Route::post('/penetapan/delete', [BiroSdmPenetapanController::class, 'delete'])->name('biro-sdm.penetapan.delete');
    Route::get('/program-finalization/{id_program}', [BiroSdmProgramController::class, 'programFinalization'])->name('biro-sdm.penetapan.finalization');
    Route::put('/update-status-assignment-final/{id_assignment}', [BiroSdmProgramController::class, 'updateStatusAssignmentFinal'])->name('biro-sdm.penetapan.update-assignment-final');
    Route::get('/program-finalization/{id_program}/print', [BiroSdmProgramController::class, 'printFinalization'])->name('biro-sdm.penetapan.print-finalization');
});

Route::get('/test-query', function() {
    $pegawai = Pegawai::select('pegawai.id as id_pegawai', 'pegawai.id_jabatan', 'standar_kompetensi.id as id_standar_kompetensi')
    ->join('jabatan', 'pegawai.id_jabatan', 'jabatan.id')
    ->join('standar_kompetensi', 'jabatan.id', 'standar_kompetensi.id_jabatan')
    ->join('users', 'pegawai.id_user', 'users.id')
    ->where('users.role', 'pegawai')
    ->get();

    // DB::beginTransaction();
    foreach ($pegawai as $p)
    {
        KompetensiPegawai::updateOrCreate(
            [
            'id_pegawai' => $p->id_pegawai,
            'id_standar_kompetensi' => $p->id_standar_kompetensi
            ],
            [
                'kpi' => rand(50, 90)
            ]
        );    
    }
    
    return $pegawai;
});

Route::group(['prefix' => 'unit-kerja', 'middleware' => 'cekRole:unit-kerja'], function () {
    // Route::get('/dashboard', function () {
    //     return view('pages.unit-kerja.dashboard');
    // })->name('dashboard.unit-kerja');
    Route::get('/getProgramDetail/{id_program}', [UnitKerjaController::class, 'getProgramDetail'])->name('getProgramDetail');
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

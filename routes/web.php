<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FuzzyController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KriteriaController;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::prefix('admin')->group(function () {
    Route::resource('kriteria', KriteriaController::class);
});

Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/kriteria', [KriteriaController::class, 'index'])->name('admin.kriteria');
Route::post('/admin/kriteria', [KriteriaController::class, 'store'])->name('admin.kriteria.store');
Route::post('/admin/kriteria/{id}', [KriteriaController::class, 'update'])->name('admin.kriteria.update');
Route::delete('/kriteria/{id}', [KriteriaController::class, 'destroy'])->name('delete-kriteria');

Route::get('/admin/jabatan', [JabatanController::class, 'index'])->name('index');
Route::delete('/jabatan/{id}', [JabatanController::class, 'destroyJabatan'])->name('delete-jabatan');
Route::delete('/bidang/{id}', [JabatanController::class, 'destroyBidang'])->name('delete-bidang');
Route::post('/store-jabatan', [JabatanController::class, 'storeJabatan'])->name('store-jabatan');
Route::post('/store-bidang', [JabatanController::class, 'storeBidang'])->name('store-bidang');
Route::post('/update-jabatan', [JabatanController::class, 'updateJabatan'])->name('update-jabatan');
Route::post('/update-bidang', [JabatanController::class, 'updateBidang'])->name('update-bidang');

Route::get('/admin/periode', [AdminController::class, 'periode'])->name('periode');
Route::post('/admin/periode/input', [AdminController::class, 'inputPeriode'])->name('periode.input');
Route::put('/admin/periode/update/{id}', [AdminController::class, 'updatePeriode'])->name('periode.update');
Route::post('/admin/periode/check', [AdminController::class, 'checkPeriode'])->name('periode.check');

Route::post('/admin/update_var', [AdminController::class, 'updateVariable'])->name('update-variable');
Route::get('/admin/datakaryawann', [AdminController::class, 'datakaryawan'])->name('karyawan_index');
Route::post('/admin/datakaryawan/postkaryawan', [AdminController::class, 'store']);
Route::put('/admin/datakaryawan/update/{id}', [AdminController::class, 'edit']);
Route::get('/admin/datakaryawan/{id}', [AdminController::class, 'detailkaryawan']);
Route::post('/admin/editKriteria/{id}', [AdminController::class, 'editKriteria']);

Route::post('/admin/update_nilai', [AdminController::class, 'updateNilai']);
Route::get('/admin/fuzzy/{id}/{tahun}', [FuzzyController::class, 'fuzzy'])->name('admin.fuzzy');
Route::post('/admin/fuzifikasi', [FuzzyController::class, 'fuzifikasi'])->name('admin.d');

Route::get('/admin/hasil', [HasilController::class, 'index'])->name('admin.hasil');
Route::get('/export/pdf', [HasilController::class, 'exportPdf'])->name('export.pdf');
// routes/web.php
Route::get('/admin/filter-karyawan/{bulan}', [AdminController::class, 'filterKaryawan']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/api/chart-data', [AdminController::class, 'getChartData']);
Route::get('/api/getChartData', [HasilController::class, 'getChartData']);
Route::get('/postFuzzy', [ExampleController::class, 'postData']);
Route::post('/admin/fuzzy/view', [FuzzyController::class, 'viewFuzzy'])->name('admin.fuzzy.view');

// getChartData

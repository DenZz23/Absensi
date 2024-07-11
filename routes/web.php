<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IzinAbsenController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\KonfigurasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['guest:pegawai'])->group(function (){
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:user'])->group(function (){
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:pegawai'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    // presensi
Route::get('/presensi/create',[PresensiController::class, 'create']);
Route::post('/presensi/store',[PresensiController::class, 'store']);

// Edit profile
Route::get('/editprofile',[PresensiController::class, 'editprofile']);
Route::post('/presensi/{nik}/updateprofile',[PresensiController::class, 'updateprofile']);

//Histori
Route::get('/presensi/histori', [PresensiController::class, 'histori']);
Route::post('/gethistori', [PresensiController::class, 'gethistori']);

//Izin
Route::get('/presensi/izin', [PresensiController::class, 'izin']);
Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);

// Izin Absen
Route::get('/izinabsen', [IzinAbsenController::class, 'create']);
Route::post('/izinabsen/store', [IzinAbsenController::class, 'store']);
Route::get('/izinabsen/{kode_izin}/edit', [IzinAbsenController::class, 'edit']);
Route::post('izinabsen/{kode_izin}/update', [IzinAbsenController::class, 'update']);

// Izin Sakit
Route::get('/izinsakit', [IzinsakitController::class, 'create']);
Route::post('/izinsakit/store', [IzinsakitController::class, 'store']);
Route::get('/izinsakit/{kode_izin}/edit', [IzinSakitController::class, 'edit']);
Route::post('izinsakit/{kode_izin}/update', [IzinSakitController::class, 'update']);

// Izin Cuti
Route::get('/izincuti', [IzincutiController::class, 'create']);
Route::post('/izincuti/store', [IzincutiController::class, 'store']);
Route::get('/izincuti/{kode_izin}/edit', [IzincutiController::class, 'edit']);
Route::post('izincuti/{kode_izin}/update', [IzincutiController::class, 'update']);

//Edit Izin
Route::get('/izin/{kode_izin}/showact', [PresensiController::class, 'showact']);
Route::get('/izin/{kode_izin}/delete', [PresensiController::class, 'deleteizin']);
});

Route::middleware(['auth:user'])->group(function () {
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    // Pegawai
    Route::get('/pegawai', [PegawaiController::class, 'index']);
    Route::post('/pegawai/store', [PegawaiController::class, 'store']);
    Route::post('/pegawai/edit', [PegawaiController::class, 'edit']);
    Route::post('/pegawai/{nik}/update', [PegawaiController::class, 'update']);
    Route::post('/pegawai/{nik}/delete', [PegawaiController::class, 'delete']);

    //Divisi
    Route::get('/divisi', [DivisiController::class, 'index']);
    Route::post('/divisi/store', [DivisiController::class, 'store']);
    Route::post('/divisi/edit', [DivisiController::class, 'edit']);
    Route::post('/divisi/{kode_div}/update', [DivisiController::class, 'update']);
    Route::post('/divisi/{nik}/delete', [DivisiController::class, 'delete']);

    //presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);

    //Konfigurasi
    Route::get('/konfigurasi/lokasikantor',[KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor',[KonfigurasiController::class, 'updatelokasikantor']);
    Route::get('/konfigurasi/jamkerja',[KonfigurasiController::class, 'jamkerja']);
    Route::post('/konfigurasi/storejamkerja',[KonfigurasiController::class, 'storejamkerja']);
    Route::post('/konfigurasi/editjamkerja',[KonfigurasiController::class, 'editjamkerja']);
    Route::post('/konfigurasi/updatejamkerja',[KonfigurasiController::class, 'updatejamkerja']);
    Route::post('/konfigurasi/{kode_jam_kerja}/delete',[KonfigurasiController::class, 'deletejamkerja']);
    Route::get('/konfigurasi/{nik}/setjamkerja',[KonfigurasiController::class, 'setjamkerja']);
    Route::post('/konfigurasi/storesetjamkerja',[KonfigurasiController::class, 'storesetjamkerja']);
    Route::post('/konfigurasi/updatesetjamkerja',[KonfigurasiController::class, 'updatesetjamkerja']);

    // Cuti
    Route::get('/cuti', [CutiController::class, 'index']);
    Route::post('/cuti/store', [CutiController::class, 'store']);
    Route::post('/cuti/edit', [CutiController::class, 'edit']);
    Route::post('/cuti/{kode_cuti}/update', [CutiController::class, 'update']);
    Route::post('/cuti/{kode_cuti}/delete', [CutiController::class, 'delete']);

});


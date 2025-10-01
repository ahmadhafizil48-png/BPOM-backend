<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DataBimbinganController;
use App\Http\Controllers\RiwayatBimbinganController;
use App\Http\Controllers\ProyekUserController;
use App\Http\Controllers\ProyekProgressController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenilaianUserController;
use App\Http\Controllers\KomplainNilaiController;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


/*
|--------------------------------------------------------------------------
| PUBLIC – Formulir
|--------------------------------------------------------------------------
*/
Route::post('/formulir', [FormulirController::class, 'store']);
Route::post('/formulir/cek-status', [FormulirController::class, 'cekStatus']);


/*
|--------------------------------------------------------------------------
| PUBLIC – Pelamar
|--------------------------------------------------------------------------
*/
Route::get('/pelamar', [PelamarController::class, 'index']);
Route::get('/pelamar/{id}', [PelamarController::class, 'show']);


/*
|--------------------------------------------------------------------------
| PROTECTED – Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn () => response()->json(['message' => 'Selamat datang Admin!']));

    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Formulir Management
    Route::get('/formulir', [FormulirController::class, 'index']);
    Route::get('/formulir/{id}', [FormulirController::class, 'show']);
    Route::put('/formulir/{id}', [FormulirController::class, 'update']);
    Route::put('/formulir/{id}/status', [FormulirController::class, 'updateStatus']);
    Route::delete('/formulir/{id}', [FormulirController::class, 'destroy']);
});


/*
|--------------------------------------------------------------------------
| PROTECTED – Pembimbing
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:pembimbing'])->group(function () {
    Route::get('/pembimbing/dashboard', fn () => response()->json(['message' => 'Selamat datang Pembimbing!']));

    // Formulir untuk pembimbing
    Route::get('/formulir', [FormulirController::class, 'index']);
    Route::get('/formulir/{id}', [FormulirController::class, 'show']);
    Route::put('/formulir/{id}', [FormulirController::class, 'update']);
    Route::put('/formulir/{id}/status', [FormulirController::class, 'updateStatus']);
    Route::delete('/formulir/{id}', [FormulirController::class, 'destroy']);

    // Data Bimbingan
    Route::get('/pembimbing/data-bimbingan', [DataBimbinganController::class, 'index']);
    Route::get('/pembimbing/data-bimbingan/{id}', [DataBimbinganController::class, 'show']);

    // Penilaian User
    Route::apiResource('penilaian-user', PenilaianUserController::class);
});


/*
|--------------------------------------------------------------------------
| PROTECTED – Mahasiswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', fn () => response()->json(['message' => 'Selamat datang Mahasiswa!']));

    // Komplain Nilai
    Route::post('komplain-nilai', [KomplainNilaiController::class, 'store']);
});


/*
|--------------------------------------------------------------------------
| PROTECTED – Pembimbing & Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum','role:pembimbing,admin'])->group(function () {
    Route::get('komplain-nilai', [KomplainNilaiController::class, 'index']);
    Route::get('komplain-nilai/{id}', [KomplainNilaiController::class, 'show']);
    Route::put('komplain-nilai/{id}', [KomplainNilaiController::class, 'update']);
    Route::delete('komplain-nilai/{id}', [KomplainNilaiController::class, 'destroy']);
});


/*
|--------------------------------------------------------------------------
| PUBLIC – Pembimbing & Divisi
|--------------------------------------------------------------------------
*/
Route::prefix('pembimbing')->group(function () {
    Route::get('/', [PembimbingController::class, 'index']);
    Route::post('/', [PembimbingController::class, 'store']);
    Route::post('/{id}/assign', [PembimbingController::class, 'assignUser']);
    Route::delete('/{id}', [PembimbingController::class, 'destroy']);
});

Route::prefix('divisi')->group(function () {
    Route::get('/', [DivisiController::class, 'index']);
    Route::get('/{id}', [DivisiController::class, 'show']);
    Route::post('/', [DivisiController::class, 'store']);
    Route::put('/{id}', [DivisiController::class, 'update']);
    Route::delete('/{id}', [DivisiController::class, 'destroy']);

    // ✅ tambahan endpoint khusus untuk mahasiswa lihat kuota
    Route::get('/kuota/list', [DivisiController::class, 'listKuota']);
});

Route::get('/pimpinan', [PimpinanController::class, 'index']);
Route::post('/pimpinan', [PimpinanController::class, 'store']);
Route::put('/pimpinan/{id}', [PimpinanController::class, 'update']);
Route::delete('/pimpinan/{id}', [PimpinanController::class, 'destroy']);
Route::put('/pimpinan/{id}/aktif', [PimpinanController::class, 'setActive']);


/*
|--------------------------------------------------------------------------
| API Resources
|--------------------------------------------------------------------------
*/
Route::apiResource('riwayat', RiwayatController::class);
Route::apiResource('laporan', LaporanController::class);


/*
|--------------------------------------------------------------------------
| Riwayat Bimbingan
|--------------------------------------------------------------------------
*/
Route::get('/riwayat-bimbingan', [RiwayatBimbinganController::class, 'index']);
Route::get('/riwayat-bimbingan/{id}', [RiwayatBimbinganController::class, 'show']);
Route::post('/riwayat-bimbingan', [RiwayatBimbinganController::class, 'store']);
Route::put('/riwayat-bimbingan/{id}', [RiwayatBimbinganController::class, 'update']);
Route::delete('/riwayat-bimbingan/{id}', [RiwayatBimbinganController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| Proyek User (Assign Tugas / Proyek ke Mahasiswa)
|--------------------------------------------------------------------------
*/
Route::apiResource('proyek-user', ProyekUserController::class);


/*
|--------------------------------------------------------------------------
| Progress Proyek (Laporan Pengerjaan)
|--------------------------------------------------------------------------
*/
Route::apiResource('proyek-progress', ProyekProgressController::class);


/*
|--------------------------------------------------------------------------
| Absensi
|--------------------------------------------------------------------------
*/
Route::prefix('absensi')->group(function () {
    Route::post('/', [AbsensiController::class, 'store']);           // Buat request absensi
    Route::get('/pending', [AbsensiController::class, 'pending']);  // List pending
    Route::get('/riwayat', [AbsensiController::class, 'riwayat']);  // Riwayat absensi
    Route::post('/approve/{id}', [AbsensiController::class, 'approve']); // Terima
    Route::post('/reject/{id}', [AbsensiController::class, 'reject']);   // Tolak
});
/*
|--------------------------------------------------------------------------
| PENILAIAN & KOMPLAIN NILAI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:pembimbing'])->group(function () {
    Route::apiResource('penilaian-user', PenilaianUserController::class);
});

Route::post('komplain-nilai', [KomplainNilaiController::class, 'store'])
    ->middleware(['auth:sanctum','role:mahasiswa']);

Route::middleware(['auth:sanctum','role:pembimbing,admin'])->group(function () {
    Route::get('komplain-nilai', [KomplainNilaiController::class, 'index']);
    Route::get('komplain-nilai/{id}', [KomplainNilaiController::class, 'show']);
    Route::put('komplain-nilai/{id}', [KomplainNilaiController::class, 'update']);
    Route::delete('komplain-nilai/{id}', [KomplainNilaiController::class, 'destroy']);
});

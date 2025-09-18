<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\DetailPelamarController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\LaporanRiwayatController;
use App\Http\Controllers\LaporanPembimbingController;
use App\Http\Controllers\LaporanUserAktifController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 👨‍🎓 Mahasiswa daftar magang & cek status
Route::post('/magang', [MagangController::class, 'store']);
Route::post('/magang/cek-status', [MagangController::class, 'cekStatus']);

// 👥 Public user list
Route::get('/users', [UserController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Protected Routes (butuh login via Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    // 🔑 Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // 📊 Dashboard berdasarkan role
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->middleware('role:admin');
    Route::get('/dashboard/pembimbing', [DashboardController::class, 'pembimbingDashboard'])->middleware('role:pembimbing');
    Route::get('/dashboard/user', [DashboardController::class, 'userDashboard'])->middleware('role:user');

    // 📂 CRUD Magang (kecuali store karena sudah public)
    Route::apiResource('magang', MagangController::class)->except(['store']);

    // 📝 Update status magang (hanya admin)
    Route::put('/magang/{id}/status', [MagangController::class, 'updateStatus'])->middleware('role:admin');

    // 👤 Admin kelola users
    Route::middleware('role:admin')->group(function () {
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Pembimbing Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/pembimbing', [PembimbingController::class, 'index']);
    Route::get('/pembimbing/{id}', [PembimbingController::class, 'show']);
    Route::post('/pembimbing', [PembimbingController::class, 'store']);
    Route::put('/pembimbing/{id}', [PembimbingController::class, 'update']);
    Route::delete('/pembimbing/{id}', [PembimbingController::class, 'destroy']);
    Route::post('/pembimbing/{id}/assign', [PembimbingController::class, 'assignUser']);

    /*
    |--------------------------------------------------------------------------
    | Detail Pelamar Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('detail-pelamar')->group(function () {
        Route::get('/', [DetailPelamarController::class, 'index']);
        Route::get('/{id}', [DetailPelamarController::class, 'show']);
        Route::post('/', [DetailPelamarController::class, 'store']);
        Route::put('/{id}', [DetailPelamarController::class, 'update']);
    });

    /*
    |--------------------------------------------------------------------------
    | Sertifikat Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('sertifikat')->group(function () {
        Route::get('/', [SertifikatController::class, 'index']);
        Route::get('/{id}', [SertifikatController::class, 'show']);
        Route::post('/', [SertifikatController::class, 'store']);
        Route::put('/{id}', [SertifikatController::class, 'update']);
        Route::delete('/{id}', [SertifikatController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Pimpinan Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('pimpinan')->group(function () {
        Route::get('/', [PimpinanController::class, 'index']);
        Route::get('/{id}', [PimpinanController::class, 'show']);
        Route::post('/', [PimpinanController::class, 'store']);
        Route::put('/{id}', [PimpinanController::class, 'update']);
        Route::delete('/{id}', [PimpinanController::class, 'destroy']);
        Route::put('/{id}/status', [PimpinanController::class, 'updateStatus']);
    });

    /*
    |--------------------------------------------------------------------------
    | Laporan & Riwayat Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan-riwayat')->group(function () {
        Route::get('/', [LaporanRiwayatController::class, 'index']);
        Route::get('/riwayat-admin', [LaporanRiwayatController::class, 'riwayatAdmin']);
        Route::get('/riwayat-user-selesai', [LaporanRiwayatController::class, 'riwayatUserSelesai']);
        Route::get('/riwayat-user-ditolak', [LaporanRiwayatController::class, 'riwayatUserDitolak']);
        Route::get('/riwayat-pelamar', [LaporanRiwayatController::class, 'riwayatPelamar']);
        Route::post('/export', [LaporanRiwayatController::class, 'export']);
        Route::get('/filter-options', [LaporanRiwayatController::class, 'getFilterOptions']);
    });

    /*
    |--------------------------------------------------------------------------
    | Laporan Pembimbing Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan-pembimbing')->group(function () {
        Route::get('/', [LaporanPembimbingController::class, 'index']);
        Route::post('/', [LaporanPembimbingController::class, 'store']);
        Route::get('/{id}', [LaporanPembimbingController::class, 'show']);
        Route::put('/{id}', [LaporanPembimbingController::class, 'update']);
        Route::delete('/{id}', [LaporanPembimbingController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Laporan User Aktif Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan-user-aktif')->group(function () {
        Route::get('/', [LaporanUserAktifController::class, 'index']);
        Route::post('/', [LaporanUserAktifController::class, 'store']);
        Route::get('/{id}', [LaporanUserAktifController::class, 'show']);
        Route::put('/{id}', [LaporanUserAktifController::class, 'update']);
        Route::delete('/{id}', [LaporanUserAktifController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Divisi Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('divisi')->group(function () {
        Route::get('/', [DivisiController::class, 'index']);
        Route::get('/{id}', [DivisiController::class, 'show']);
        Route::post('/', [DivisiController::class, 'store']);
        Route::put('/{id}', [DivisiController::class, 'update']);
        Route::delete('/{id}', [DivisiController::class, 'destroy']);
    });
});

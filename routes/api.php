<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\WeeklyReportController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// FORMULIR (Public)
Route::post('/formulir', [FormulirController::class, 'store']);
Route::post('/formulir/cek-status', [FormulirController::class, 'cekStatus']);

// DIVISI (Public)
Route::get('/divisi', [DivisiController::class, 'index']);
Route::get('/divisi/{id}', [DivisiController::class, 'show']);

// AUTH (Public)
Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // User profile check
    Route::get('/me', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user()->load('role', 'divisi')
        ]);
    });
});



/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // ================== DIVISI ==================
    Route::post('/divisi', [DivisiController::class, 'store']);
    Route::put('/divisi/{id}', [DivisiController::class, 'update']);
    Route::delete('/divisi/{id}', [DivisiController::class, 'destroy']);

    // ================== FORMULIR ==================
    Route::get('/formulir', [FormulirController::class, 'index']);
    Route::get('/formulir/{id}', [FormulirController::class, 'show']);
    Route::post('/formulir/terima/{id}', [FormulirController::class, 'terima']);
    Route::post('/formulir/tolak/{id}', [FormulirController::class, 'tolak']);

    // ================== PEMBIMBING ==================
    Route::post('/pembimbing', [PembimbingController::class, 'store']);
    Route::put('/pembimbing/{id}', [PembimbingController::class, 'update']);
    Route::delete('/pembimbing/{id}', [PembimbingController::class, 'destroy']);
    Route::get('/pembimbing', [PembimbingController::class, 'index']);

    // Semua bimbingan dari seluruh pembimbing
    Route::get('/pembimbing/bimbingan', [PembimbingController::class, 'allBimbingan']);
});



/*
|--------------------------------------------------------------------------
| PEMBIMBING ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:pembimbing'])->group(function () {
    Route::get('/pembimbing/mahasiswa', [PembimbingController::class, 'mahasiswaSaya']);
});



/*
|--------------------------------------------------------------------------
| MAHASISWA ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->group(function () {

        // --------------------- PROFIL ---------------------
        Route::get('/profile', [MahasiswaController::class, 'profile']);


        // ----------------- LOGBOOK HARIAN -----------------

        // ----------------- LAPORAN AKHIR -----------------
        Route::get('/laporan-akhir',    [MahasiswaController::class, 'showLaporanAkhir']);
        Route::post('/laporan-akhir',   [MahasiswaController::class, 'uploadLaporanAkhir']);
        Route::delete('/laporan-akhir', [MahasiswaController::class, 'deleteLaporanAkhir']);


        // ----------------- PROGRESS TRACKING -----------------
        Route::get('/progress', [MahasiswaController::class, 'progress']);
    });


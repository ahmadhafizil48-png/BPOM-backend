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
use App\Http\Controllers\UserReportController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserAktifController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\SertifikatController;

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

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

    // ✅ Manajemen User
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::get('/admin/users/{id}/detail', [UserController::class, 'showDetailPelamar']);

    // ✅ Formulir (Admin)
    Route::get('/formulir', [FormulirController::class, 'index']);
    Route::get('/formulir/{id}', [FormulirController::class, 'show']);
    Route::put('/formulir/{id}', [FormulirController::class, 'update']);
    Route::put('/formulir/{id}/status', [FormulirController::class, 'updateStatus']);
    Route::delete('/formulir/{id}', [FormulirController::class, 'destroy']);

    // ✅ USER AKTIF (Admin)
    Route::prefix('admin/user-aktif')->group(function () {
        Route::get('/', [UserAktifController::class, 'index']);
        Route::get('/{id}', [UserAktifController::class, 'show']);
        Route::post('/', [UserAktifController::class, 'store']);
        Route::put('/{id}', [UserAktifController::class, 'update']);
        Route::delete('/{id}', [UserAktifController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| PROTECTED – Pembimbing
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:pembimbing'])->group(function () {

    Route::get('/pembimbing/dashboard', fn() => response()->json(['message' => 'Selamat datang Pembimbing!']));

    Route::get('/pembimbing/data-bimbingan', [DataBimbinganController::class, 'index']);
    Route::get('/pembimbing/data-bimbingan/{id}', [DataBimbinganController::class, 'show']);

    Route::apiResource('penilaian-user', PenilaianUserController::class);

    Route::get('/user-aktif', [UserReportController::class, 'userAktif']);
    Route::get('/riwayat-penilaian/{user_id}', [UserReportController::class, 'riwayatPenilaian']);

    // ✅ PEMBIMBING AKSES USER_AKTIF (pakai indexPembimbing)
    Route::prefix('pembimbing/user-aktif')->group(function () {
        Route::get('/', [UserAktifController::class, 'indexPembimbing']); // ← fungsi baru khusus pembimbing
        Route::get('/{id}', [UserAktifController::class, 'show']);
        Route::put('/{id}', [UserAktifController::class, 'update']);
    });
});

/*
|--------------------------------------------------------------------------
| PROTECTED – Mahasiswa / User
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {

    Route::get('/user/dashboard', [UserDashboardController::class, 'index']);

    // 🔹 Profil user
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::put('/user/change-password', [UserController::class, 'changePassword']);

    // 🔹 Komplain dan nilai
    Route::post('/komplain', [KomplainNilaiController::class, 'store']);
    Route::get('/penilaian-user/me', [PenilaianUserController::class, 'myScore']);

    // 🔹 Divisi dan proyek
    Route::get('/divisi/kuota/list', [DivisiController::class, 'listKuota']);
    Route::apiResource('proyek-progress', ProyekProgressController::class);

    // 🔹 SERTIFIKAT + FEEDBACK + UPLOAD LAPORAN
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::get('/feedback/check/{user_id}', [FeedbackController::class, 'checkFeedback']);
    Route::post('/upload-laporan', [SertifikatController::class, 'uploadLaporan']);
    Route::get('/sertifikat/download', [SertifikatController::class, 'download']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED – Kalender (Semua Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin,pembimbing,user'])->group(function () {
    Route::get('/kalender', [KalenderController::class, 'index']);
    Route::post('/kalender', [KalenderController::class, 'store']);
    Route::get('/kalender/{id}', [KalenderController::class, 'show']);
    Route::put('/kalender/{id}', [KalenderController::class, 'update']);
    Route::delete('/kalender/{id}', [KalenderController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED – Komplain Nilai
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:pembimbing,admin'])->group(function () {
    Route::get('/komplain', [KomplainNilaiController::class, 'index']);
    Route::get('/komplain/{id}', [KomplainNilaiController::class, 'show']);
    Route::put('/komplain/{id}', [KomplainNilaiController::class, 'update']);
    Route::delete('/komplain/{id}', [KomplainNilaiController::class, 'destroy']);
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
    Route::get('/{id}/dashboard', [PembimbingController::class, 'dashboard']);
});

Route::prefix('divisi')->group(function () {
    Route::get('/', [DivisiController::class, 'index']);
    Route::get('/{id}', [DivisiController::class, 'show']);
    Route::post('/', [DivisiController::class, 'store']);
    Route::put('/{id}', [DivisiController::class, 'update']);
    Route::delete('/{id}', [DivisiController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| PIMPINAN
|--------------------------------------------------------------------------
*/
Route::get('/pimpinan', [PimpinanController::class, 'index']);
Route::post('/pimpinan', [PimpinanController::class, 'store']);
Route::put('/pimpinan/{id}', [PimpinanController::class, 'update']);
Route::delete('/pimpinan/{id}', [PimpinanController::class, 'destroy']);
Route::put('/pimpinan/{id}/aktif', [PimpinanController::class, 'setActive']);

/*
|--------------------------------------------------------------------------
| RIWAYAT & LAPORAN
|--------------------------------------------------------------------------
*/
Route::apiResource('riwayat', RiwayatController::class);
Route::apiResource('laporan', LaporanController::class);

/*
|--------------------------------------------------------------------------
| RIWAYAT BIMBINGAN
|--------------------------------------------------------------------------
*/
Route::get('/riwayat-bimbingan', [RiwayatBimbinganController::class, 'index']);
Route::get('/riwayat-bimbingan/{id}', [RiwayatBimbinganController::class, 'show']);
Route::post('/riwayat-bimbingan', [RiwayatBimbinganController::class, 'store']);
Route::put('/riwayat-bimbingan/{id}', [RiwayatBimbinganController::class, 'update']);
Route::delete('/riwayat-bimbingan/{id}', [RiwayatBimbinganController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| PROYEK USER & PROGRESS
|--------------------------------------------------------------------------
*/
Route::apiResource('proyek-user', ProyekUserController::class);
Route::apiResource('proyek-progress', ProyekProgressController::class);

/*
|--------------------------------------------------------------------------
| ABSENSI
|--------------------------------------------------------------------------
*/
Route::prefix('absensi')->group(function () {
    Route::post('/', [AbsensiController::class, 'store']);
    Route::get('/pending', [AbsensiController::class, 'pending']);
    Route::get('/riwayat', [AbsensiController::class, 'riwayat']);
    Route::post('/approve/{id}', [AbsensiController::class, 'approve']);
    Route::post('/reject/{id}', [AbsensiController::class, 'reject']);
});

/*
|--------------------------------------------------------------------------
| LOGBOOK
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin,pembimbing,user'])->group(function () {
    Route::get('/logbook', [LogbookController::class, 'index']);
    Route::post('/logbook', [LogbookController::class, 'store']);
    Route::post('/logbook/{id}', [LogbookController::class, 'update']);
    Route::delete('/logbook/{id}', [LogbookController::class, 'destroy']);
});

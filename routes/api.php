<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\UserController;

// 🔑 Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// 👥 Public route (ambil semua user)
Route::get('/users', [UserController::class, 'index']);

// 👨‍🎓 Guest (mahasiswa) bisa daftar & cek status tanpa login
Route::post('/magang', [MagangController::class, 'store']);
Route::post('/magang/cek-status', [MagangController::class, 'cekStatus']);

// 🔒 Protected routes (hanya untuk user login)
Route::middleware(['auth:sanctum'])->group(function () {
    // Dashboard
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->middleware('role:admin');
    Route::get('/dashboard/pembimbing', [DashboardController::class, 'pembimbingDashboard'])->middleware('role:pembimbing');
    Route::get('/dashboard/user', [DashboardController::class, 'userDashboard'])->middleware('role:user');

    // CRUD Magang
    Route::apiResource('magang', MagangController::class);

    // Admin / pembimbing bisa update status magang
    Route::put('/magang/{id}/status', [MagangController::class, 'updateStatus'])->middleware('role:admin');

    // Admin only: kelola users
    Route::middleware('role:admin')->group(function () {
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});

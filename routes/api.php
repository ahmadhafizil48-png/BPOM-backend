<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MagangController;

// 🔹 Auth routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// 🔹 Routes dengan proteksi auth
Route::middleware(['auth:sanctum'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->middleware('role:admin');
    Route::get('/dashboard/pembimbing', [DashboardController::class, 'pembimbingDashboard'])->middleware('role:pembimbing');
    Route::get('/dashboard/user', [DashboardController::class, 'userDashboard'])->middleware('role:user');

    // Magang routes
    Route::apiResource('magang', MagangController::class);
});

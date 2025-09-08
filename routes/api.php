<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\AuthController; // pastikan AuthController dikenali

// Route untuk autentikasi user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('magang', MagangController::class);
// Route untuk ambil data user yang sedang login
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

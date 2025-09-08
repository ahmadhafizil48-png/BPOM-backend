<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk autentikasi user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Resource untuk Magang
Route::resource('magang', MagangController::class);

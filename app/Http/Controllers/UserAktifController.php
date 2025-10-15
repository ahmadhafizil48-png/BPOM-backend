<?php

namespace App\Http\Controllers;

use App\Models\UserAktif;

class UserAktifController extends Controller
{
    public function index()
    {
        $data = UserAktif::with(['user', 'divisi', 'pembimbing'])->get();

        return response()->json([
            'message' => 'Daftar User Aktif',
            'data' => $data
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProyekUser;
use Illuminate\Http\Request;

class ProyekUserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'divisi_id' => 'required|exists:divisis,id',
            'nama_proyek' => 'nullable|string|max:255',
        ]);

        $proyek = ProyekUser::create($validated);

        return response()->json([
            'message' => 'Proyek berhasil dibuat untuk user',
            'data' => $proyek
        ], 201);
    }

    public function index()
    {
        return ProyekUser::with(['user', 'divisi', 'progress'])->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Formulir;
use Illuminate\Support\Facades\Auth; // <-- WAJIB

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Gunakan Auth::user()

        if (!$user) {
            return response()->json(['message' => 'User tidak terautentikasi'], 401);
        }

        $pembimbing = $user->pembimbing ?? null;
        $divisi = $pembimbing?->divisi?->nama_divisi ?? null;

        $formulir = Formulir::where('nama', $user->name)->latest()->first();

        $periode_mulai = $formulir?->waktu_mulai ? $formulir->waktu_mulai->format('d M Y') : null;
        $periode_selesai = $formulir?->waktu_selesai ? $formulir->waktu_selesai->format('d M Y') : null;

        return response()->json([
            'profil_singkat' => [
                'nama' => $user->name,
                'divisi' => $divisi,
                'periode' => $periode_mulai && $periode_selesai ? "$periode_mulai - $periode_selesai" : null,
            ],
            'info_pembimbing' => $pembimbing ? [
                'nama' => $pembimbing->nama,
                'divisi' => $divisi,
            ] : null,
            'reminder_terdekat' => null
        ]);
    }
}

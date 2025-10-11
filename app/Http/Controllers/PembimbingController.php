<?php

namespace App\Http\Controllers;

use App\Models\Pembimbing;
use App\Models\User;
use App\Models\Formulir;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PembimbingController extends Controller
{
    public function index()
    {
        return Pembimbing::with('divisi', 'users')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'divisi_id' => 'required|exists:divisis,id',
            'no_hp' => 'required|string',
            'email' => 'required|email|unique:pembimbings,email',
        ]);

        $pembimbing = Pembimbing::create($validated);

        return response()->json([
            'message' => 'Pembimbing berhasil ditambahkan',
            'data' => $pembimbing
        ], 201);
    }

    public function assignUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $pembimbing = Pembimbing::findOrFail($id);
        $pembimbing->users()->syncWithoutDetaching([$request->user_id]);

        return response()->json([
            'message' => 'User berhasil di-assign ke pembimbing',
            'data' => $pembimbing->load('users')
        ]);
    }

    public function destroy($id)
    {
        Pembimbing::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Pembimbing berhasil dihapus'
        ]);
    }

    // ✅ Dashboard Pembimbing (fungsi baru)
    public function dashboard($id)
    {
        $pembimbing = Pembimbing::with(['users.formulir'])->find($id);

        if (!$pembimbing) {
            return response()->json(['message' => 'Pembimbing tidak ditemukan'], 404);
        }

        $users = $pembimbing->users;

        // Hitung user aktif & selesai
        $userAktif = $users->where('is_active', true)->count();
        $userSelesai = $users->filter(function ($u) {
            return optional($u->formulir)->status_pengajuan === 'selesai';
        })->count();

        // Reminder meeting terdekat (contoh hardcoded dulu)
        $reminder = [
            'judul' => 'Meeting bimbingan',
            'tanggal' => Carbon::create(2025, 9, 5, 10, 0, 0)->format('d M Y, H:i') . ' WIB',
        ];

        // Data untuk chart
        $statusChart = [
            'Aktif' => $userAktif,
            'Selesai' => $userSelesai,
            'Belum Mulai' => $users->count() - ($userAktif + $userSelesai),
        ];

        return response()->json([
            'message' => 'Dashboard pembimbing berhasil diambil',
            'data' => [
                'pembimbing' => $pembimbing->nama,
                'user_aktif' => $userAktif,
                'user_selesai' => $userSelesai,
                'reminder' => $reminder,
                'status_chart' => $statusChart,
            ]
        ]);
    }
}

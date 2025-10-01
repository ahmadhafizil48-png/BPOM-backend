<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    // 🔹 Buat request absensi (pending)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'tanggal'   => 'required|date',
            'aktivitas' => 'required|string|max:255',
        ]);

        $absensi = Absensi::create($validated);

        return response()->json([
            'message' => 'Permintaan absensi berhasil dibuat',
            'data' => $absensi
        ], 201);
    }

    // 🔹 Ambil semua permintaan absensi pending
    public function pending()
    {
        $data = Absensi::with('user')
            ->where('status', 'pending')
            ->get();

        return response()->json($data);
    }

    // 🔹 Riwayat absensi (selain pending)
    public function riwayat()
    {
        $data = Absensi::with('user')
            ->where('status', '!=', 'pending')
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json($data);
    }

    // 🔹 Approve absensi
    public function approve($id, Request $request)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'status' => 'hadir',
            'approved_by' => $request->user()->id
        ]);

        return response()->json(['message' => 'Absensi diterima', 'data' => $absensi]);
    }

    // 🔹 Tolak absensi
    public function reject($id, Request $request)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'status' => 'tidak_hadir',
            'approved_by' => $request->user()->id
        ]);

        return response()->json(['message' => 'Absensi ditolak', 'data' => $absensi]);
    }
}

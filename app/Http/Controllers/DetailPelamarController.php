<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPelamar;

class DetailPelamarController extends Controller
{
    // ✅ ambil semua pelamar beserta detail magang
    public function index()
    {
        $pelamar = DetailPelamar::with('magang')->get();
        return response()->json($pelamar);
    }

    // ✅ ambil pelamar by ID beserta detail magang
    public function show($id)
    {
        $pelamar = DetailPelamar::with('magang')->find($id);
        if (!$pelamar) {
            return response()->json(['message' => 'Detail pelamar tidak ditemukan'], 404);
        }
        return response()->json($pelamar);
    }

    // ✅ Admin bisa update status_pengajuan
    public function update(Request $request, $id)
    {
        $pelamar = DetailPelamar::find($id);
        if (!$pelamar) {
            return response()->json(['message' => 'Detail pelamar tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'status_pengajuan' => 'required|in:belum diproses,sedang diproses,diterima,ditolak',
        ]);

        $pelamar->update($validated);

        return response()->json([
            'message' => 'Status berhasil diperbarui',
            'data' => $pelamar
        ], 200);
    }
}

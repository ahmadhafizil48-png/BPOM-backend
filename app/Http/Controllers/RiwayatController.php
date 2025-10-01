<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Riwayat;

class RiwayatController extends Controller
{
    /**
     * Tampilkan semua riwayat (bisa filter tipe via query string).
     * contoh: /api/riwayat?tipe=Admin
     */
    public function index(Request $request)
    {
        $tipe = $request->query('tipe');

        if ($tipe) {
            $riwayat = Riwayat::where('tipe', $tipe)->orderBy('created_at', 'desc')->get();
        } else {
            $riwayat = Riwayat::orderBy('created_at', 'desc')->get();
        }

        return response()->json($riwayat);
    }

    /**
     * Simpan data riwayat baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:Admin,User_Selesai,User_Ditolak',
            'tanggal' => 'nullable|date',
            'admin' => 'nullable|string',
            'aksi' => 'nullable|string',
            'user' => 'nullable|string',
            'divisi' => 'nullable|string',
            'pembimbing' => 'nullable|string',
            'periode' => 'nullable|string',
            'nilai' => 'nullable|string',
            'sertifikat' => 'nullable|string',
            'tanggal_selesai' => 'nullable|date',
            'no_formulir' => 'nullable|string',
            'tanggal_tolak' => 'nullable|date',
            'alasan' => 'nullable|string',
        ]);

        $riwayat = Riwayat::create($validated);

        return response()->json([
            'message' => 'Riwayat berhasil ditambahkan',
            'data' => $riwayat
        ], 201);
    }

    /**
     * Lihat detail riwayat
     */
    public function show($id)
    {
        $riwayat = Riwayat::findOrFail($id);

        return response()->json($riwayat);
    }

    /**
     * Update data riwayat
     */
    public function update(Request $request, $id)
    {
        $riwayat = Riwayat::findOrFail($id);

        $validated = $request->validate([
            'tipe' => 'sometimes|in:Admin,User_Selesai,User_Ditolak',
            'tanggal' => 'nullable|date',
            'admin' => 'nullable|string',
            'aksi' => 'nullable|string',
            'user' => 'nullable|string',
            'divisi' => 'nullable|string',
            'pembimbing' => 'nullable|string',
            'periode' => 'nullable|string',
            'nilai' => 'nullable|string',
            'sertifikat' => 'nullable|string',
            'tanggal_selesai' => 'nullable|date',
            'no_formulir' => 'nullable|string',
            'tanggal_tolak' => 'nullable|date',
            'alasan' => 'nullable|string',
        ]);

        $riwayat->update($validated);

        return response()->json([
            'message' => 'Riwayat berhasil diperbarui',
            'data' => $riwayat
        ]);
    }

    /**
     * Hapus data riwayat
     */
    public function destroy($id)
    {
        $riwayat = Riwayat::findOrFail($id);
        $riwayat->delete();

        return response()->json([
            'message' => 'Riwayat berhasil dihapus'
        ]);
    }
}

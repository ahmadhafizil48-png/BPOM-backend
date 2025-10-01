<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Ambil semua laporan (bisa filter tipe)
    public function index(Request $request)
    {
        $query = Laporan::query();

        if ($request->has('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        return response()->json($query->get());
    }

    // Simpan laporan baru
    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|string',
        ]);

        $laporan = Laporan::create($request->all());

        return response()->json([
            'message' => 'Laporan berhasil dibuat',
            'data' => $laporan
        ], 201);
    }

    // Lihat laporan by ID
    public function show($id)
    {
        $laporan = Laporan::findOrFail($id);
        return response()->json($laporan);
    }

    // Update laporan
    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update($request->all());

        return response()->json([
            'message' => 'Laporan berhasil diupdate',
            'data' => $laporan
        ]);
    }

    // Hapus laporan
    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();

        return response()->json([
            'message' => 'Laporan berhasil dihapus'
        ]);
    }
}

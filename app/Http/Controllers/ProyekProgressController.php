<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyekProgress;

class ProyekProgressController extends Controller
{
    /**
     * 🔹 Tampilkan semua progress proyek
     */
    public function index()
    {
        $progress = ProyekProgress::with('proyek')->get();
        return response()->json($progress);
    }

    /**
     * 🔹 Simpan progress baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proyek_user_id' => 'required|exists:proyek_user,id',
            'deskripsi_progress' => 'required|string',
            'tanggal' => 'nullable|date',
        ]);

        $progress = ProyekProgress::create($validated);

        return response()->json([
            'message' => 'Progress proyek berhasil ditambahkan.',
            'data' => $progress
        ], 201);
    }

    /**
     * 🔹 Lihat detail progress tertentu
     */
    public function show($id)
    {
        $progress = ProyekProgress::with('proyek')->findOrFail($id);
        return response()->json($progress);
    }

    /**
     * 🔹 Update progress tertentu
     */
    public function update(Request $request, $id)
    {
        $progress = ProyekProgress::findOrFail($id);

        $validated = $request->validate([
            'deskripsi_progress' => 'sometimes|required|string',
            'tanggal' => 'nullable|date',
        ]);

        $progress->update($validated);

        return response()->json([
            'message' => 'Progress proyek berhasil diperbarui.',
            'data' => $progress
        ]);
    }

    /**
     * 🔹 Hapus progress
     */
    public function destroy($id)
    {
        $progress = ProyekProgress::findOrFail($id);
        $progress->delete();

        return response()->json(['message' => 'Progress proyek berhasil dihapus.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kalender;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    // 🔹 GET /api/kalender
    public function index()
    {
        $data = Kalender::orderBy('tanggal', 'asc')->get();
        return response()->json($data);
    }

    // 🔹 POST /api/kalender
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
        ]);

        $kalender = Kalender::create($validated);
        return response()->json([
            'message' => 'Kegiatan berhasil ditambahkan!',
            'data' => $kalender
        ], 201);
    }

    // 🔹 GET /api/kalender/{id}
    public function show($id)
    {
        $kalender = Kalender::find($id);
        if (!$kalender) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($kalender);
    }

    // 🔹 PUT /api/kalender/{id}
    public function update(Request $request, $id)
    {
        $kalender = Kalender::find($id);
        if (!$kalender) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
        ]);

        $kalender->update($validated);
        return response()->json([
            'message' => 'Data berhasil diperbarui!',
            'data' => $kalender
        ]);
    }

    // 🔹 DELETE /api/kalender/{id}
    public function destroy($id)
    {
        $kalender = Kalender::find($id);
        if (!$kalender) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $kalender->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}

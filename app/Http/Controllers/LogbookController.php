<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logbook; // <- INI WAJIB ADA

class LogbookController extends Controller
{
    // 🔹 Ambil semua logbook milik user tertentu
    public function index(Request $request)
    {
        $logbooks = Logbook::where('user_id', $request->user_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json($logbooks);
    }

    // 🔹 Simpan logbook baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kendala' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $logbook = Logbook::create($validated);

        return response()->json([
            'message' => 'Logbook disimpan',
            'data' => $logbook
        ], 201);
    }

    // 🔹 Update logbook
    public function update(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kendala' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $logbook->update($validated);

        return response()->json(['message' => 'Logbook diperbarui', 'data' => $logbook]);
    }

    // 🔹 Hapus logbook
    public function destroy($id)
    {
        $logbook = Logbook::findOrFail($id);
        $logbook->delete();

        return response()->json(['message' => 'Logbook dihapus']);
    }
}

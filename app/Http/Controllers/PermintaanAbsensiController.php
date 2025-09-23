<?php

namespace App\Http\Controllers;

use App\Models\PermintaanAbsensi;
use Illuminate\Http\Request;

class PermintaanAbsensiController extends Controller
{
    // list semua permintaan
    public function index()
    {
        $data = PermintaanAbsensi::with('user:id,name')->get();
        return response()->json($data);
    }

    // simpan permintaan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string|max:255',
        ]);

        $data = PermintaanAbsensi::create($validated);
        return response()->json($data, 201);
    }

    // ubah status (terima/tolak)
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Diterima,Ditolak'
        ]);

        $data = PermintaanAbsensi::findOrFail($id);
        $data->update($validated);

        return response()->json($data);
    }

    // hapus permintaan
    public function destroy($id)
    {
        $data = PermintaanAbsensi::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Permintaan absensi dihapus']);
    }
}

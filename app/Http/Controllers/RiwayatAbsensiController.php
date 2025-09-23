<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAbsensi;
use Illuminate\Http\Request;

class RiwayatAbsensiController extends Controller
{
    // list riwayat absensi
    public function index()
    {
        $data = RiwayatAbsensi::with('user:id,name')->get();
        return response()->json($data);
    }

    // tambah riwayat absensi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string|max:255',
            'status'   => 'required|in:Hadir,Tidak Hadir',
        ]);

        $data = RiwayatAbsensi::create($validated);
        return response()->json($data, 201);
    }

    // update data absensi
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'sometimes|date',
            'aktivitas' => 'sometimes|string|max:255',
            'status'   => 'sometimes|in:Hadir,Tidak Hadir',
        ]);

        $data = RiwayatAbsensi::findOrFail($id);
        $data->update($validated);

        return response()->json($data);
    }

    // hapus riwayat
    public function destroy($id)
    {
        $data = RiwayatAbsensi::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Riwayat absensi dihapus']);
    }
}

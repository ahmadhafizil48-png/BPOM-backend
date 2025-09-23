<?php

namespace App\Http\Controllers;

use App\Models\RiwayatUserBimbingan;
use Illuminate\Http\Request;

class RiwayatUserBimbinganController extends Controller
{
    public function index()
    {
        $data = RiwayatUserBimbingan::with(['user', 'divisi', 'pembimbing'])->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pembimbing_id' => 'nullable|exists:pembimbings,id',
            'divisi_id' => 'nullable|exists:divisis,id',
            'nama_proyek' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nilai_akhir' => 'nullable|string|max:5',
            'sertifikat' => 'in:sudah_diberikan,belum_diberikan',
        ]);

        $riwayat = RiwayatUserBimbingan::create($validated);
        return response()->json($riwayat, 201);
    }

    public function show($id)
    {
        $riwayat = RiwayatUserBimbingan::with(['user','divisi','pembimbing'])->findOrFail($id);
        return response()->json($riwayat);
    }

    public function update(Request $request, $id)
    {
        $riwayat = RiwayatUserBimbingan::findOrFail($id);

        $validated = $request->validate([
            'nama_proyek' => 'sometimes|required|string|max:255',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date|after_or_equal:tanggal_mulai',
            'nilai_akhir' => 'nullable|string|max:5',
            'sertifikat' => 'in:sudah_diberikan,belum_diberikan',
        ]);

        $riwayat->update($validated);
        return response()->json($riwayat);
    }

    public function destroy($id)
    {
        RiwayatUserBimbingan::findOrFail($id)->delete();
        return response()->json(['message' => 'Riwayat user bimbingan dihapus']);
    }
}

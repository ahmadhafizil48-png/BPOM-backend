<?php

namespace App\Http\Controllers;

use App\Models\LaporanUserAktif;
use Illuminate\Http\Request;

class LaporanUserAktifController extends Controller
{
    public function index()
    {
        return response()->json(LaporanUserAktif::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string',
            'divisi' => 'required|string',
            'pembimbing' => 'required|string',
            'periode' => 'required|string',
            'proyek' => 'required|string',
            'kehadiran' => 'required|integer',
            'progres' => 'required|integer',
            'tanggal_mulai' => 'required|date',
        ]);

        $laporan = LaporanUserAktif::create($data);

        return response()->json($laporan, 201);
    }

    public function show($id)
    {
        return response()->json(LaporanUserAktif::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanUserAktif::findOrFail($id);
        $laporan->update($request->all());

        return response()->json($laporan);
    }

    public function destroy($id)
    {
        $laporan = LaporanUserAktif::findOrFail($id);
        $laporan->delete();

        return response()->json(['message' => 'Laporan User Aktif dihapus']);
    }
}

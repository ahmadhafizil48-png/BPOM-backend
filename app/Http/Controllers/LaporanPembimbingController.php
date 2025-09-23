<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanPembimbing;

class LaporanPembimbingController extends Controller
{
    public function index()
    {
        return LaporanPembimbing::all();
    }c

    public function show($id)
    {
        return LaporanPembimbing::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string',
            'divisi' => 'required|string',
            'jumlah_user' => 'required|integer',
            'selesai' => 'required|integer',
            'rata_nilai' => 'required|numeric',
        ]);

        return LaporanPembimbing::create($data);
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanPembimbing::findOrFail($id);

        $laporan->update($request->only(['nama', 'divisi', 'jumlah_user', 'selesai', 'rata_nilai']));

        return $laporan;
    }

    public function destroy($id)
    {
        $laporan = LaporanPembimbing::findOrFail($id);
        $laporan->delete();

        return response()->json(['message' => 'Laporan pembimbing deleted']);
    }
}

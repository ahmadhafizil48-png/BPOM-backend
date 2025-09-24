<?php

namespace App\Http\Controllers;

use App\Models\KomplainNilai;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    // Tampilkan semua komplain
    public function index()
    {
        return response()->json(KomplainNilai::all());
    }

    // Tambah komplain
    public function store(Request $request)
    {
        $data = $request->validate([
            'iduser' => 'required|integer',
            'divisi' => 'required|string|max:100',
            'proyek' => 'required|string|max:150',
            'isi_komplain' => 'required|string',
        ]);

        $komplain = KomplainNilai::create($data);

        return response()->json([
            'message' => 'Komplain berhasil ditambahkan',
            'data' => $komplain,
        ], 201);
    }

    // Update status komplain
    public function update(Request $request, $id)
    {
        $komplain = KomplainNilai::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:Pending,Setuju,Tolak',
        ]);

        $komplain->update($data);

        return response()->json([
            'message' => 'Status komplain diperbarui',
            'data' => $komplain,
        ]);
    }

    // Hapus komplain
    public function destroy($id)
    {
        $komplain = KomplainNilai::findOrFail($id);
        $komplain->delete();

        return response()->json(['message' => 'Komplain dihapus']);
    }
}

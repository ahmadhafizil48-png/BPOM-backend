<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Divisi;

class DivisiController extends Controller
{
    public function index()
    {
        return Divisi::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        $divisi = Divisi::create($validated);

        return response()->json([
            'message' => 'Divisi berhasil ditambahkan',
            'data' => $divisi
        ], 201);
    }

    public function show($id)
    {
        return Divisi::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $validated = $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        $divisi->update($validated);

        return response()->json([
            'message' => 'Divisi berhasil diperbarui',
            'data' => $divisi
        ]);
    }

    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);
        $divisi->delete();

        return response()->json([
            'message' => 'Divisi berhasil dihapus'
        ]);
    }
}

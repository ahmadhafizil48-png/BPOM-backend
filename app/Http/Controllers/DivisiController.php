<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Divisi;

class DivisiController extends Controller
{
    public function index()
    {
        return response()->json(Divisi::all());
    }

    public function show($id)
    {
        $divisi = Divisi::findOrFail($id);
        return response()->json($divisi);
    }

    public function store(Request $request)
    {
        $request->validate([
    'nama_divisi'    => 'required|string|max:255',
    'deskripsi'      => 'nullable|string',
    'kuota'          => 'required|integer|min:0',
    'maksimal_kuota' => 'required|integer|min:0',
]);


        $divisi = Divisi::create($request->all());
        return response()->json($divisi, 201);
    }

    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $request->validate([
    'nama_divisi'    => 'sometimes|required|string|max:255',
    'deskripsi'      => 'nullable|string',
    'kuota'          => 'sometimes|required|integer|min:0',
    'maksimal_kuota' => 'sometimes|required|integer|min:0',
]);


        $divisi->update($request->all());
        return response()->json($divisi);
    }

    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);
        $divisi->delete();

        return response()->json(['message' => 'Divisi deleted successfully']);
    }

    // ✅ tambahan fungsi untuk mahasiswa lihat kuota
    public function listKuota()
    {
        $divisi = Divisi::select('id', 'nama_divisi', 'kuota')->get();
        return response()->json($divisi);
    }
}

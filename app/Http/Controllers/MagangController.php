<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Http\Request;

class MagangController extends Controller
{
    public function index()
    {
        return Magang::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'nim' => 'required',
            'jurusan' => 'required',
            'universitas' => 'required',
            'email' => 'required|email|unique:magangs',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        $magang = Magang::create($validated);
        return response()->json($magang, 201);
    }

    public function show($id)
    {
        return Magang::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $magang = Magang::findOrFail($id);
        $magang->update($request->all());
        return response()->json($magang, 200);
    }

    public function destroy($id)
    {
        $magang = Magang::findOrFail($id);
        $magang->delete();
        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}

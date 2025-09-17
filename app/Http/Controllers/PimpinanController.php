<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pimpinan;

class PimpinanController extends Controller
{
    public function index()
    {
        return response()->json(Pimpinan::all());
    }

    public function show($id)
    {
        return response()->json(Pimpinan::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pimpinan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'kantor' => 'nullable|string|max:255',
            'tanda_tangan' => 'nullable|string',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $pimpinan = Pimpinan::create($validated);

        return response()->json([
            'message' => 'Pimpinan berhasil ditambahkan',
            'data' => $pimpinan
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_pimpinan' => 'sometimes|string|max:255',
            'jabatan' => 'sometimes|string|max:255',
            'kantor' => 'sometimes|string|max:255',
            'tanda_tangan' => 'nullable|string',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $pimpinan = Pimpinan::findOrFail($id);
        $pimpinan->update($validated);

        return response()->json([
            'message' => 'Pimpinan berhasil diperbarui',
            'data' => $pimpinan
        ]);
    }

    public function destroy($id)
    {
        $pimpinan = Pimpinan::findOrFail($id);
        $pimpinan->delete();

        return response()->json([
            'message' => 'Pimpinan berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $pimpinan = Pimpinan::findOrFail($id);
        $pimpinan->status = $request->status;
        $pimpinan->save();

        return response()->json([
            'message' => 'Status pimpinan berhasil diubah',
            'data' => $pimpinan
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Pimpinan;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    // Lihat semua pimpinan
    public function index()
    {
        return Pimpinan::all();
    }

    // Tambah pimpinan (kalau mau simpan arsip lama)
    public function store(Request $request)
    {
        // Cek kalau sudah ada yang aktif
        if ($request->status === 'Aktif') {
            Pimpinan::where('status', 'Aktif')->update(['status' => 'Nonaktif']);
        }

        $pimpinan = Pimpinan::create($request->all());

        return response()->json($pimpinan, 201);
    }

    // Update pimpinan
    public function update(Request $request, $id)
    {
        $pimpinan = Pimpinan::findOrFail($id);

        if ($request->status === 'Aktif') {
            Pimpinan::where('status', 'Aktif')->update(['status' => 'Nonaktif']);
        }

        $pimpinan->update($request->all());

        return response()->json($pimpinan, 200);
    }

    // Hapus pimpinan
    public function destroy($id)
    {
        Pimpinan::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    // Aktifkan pimpinan
    public function setActive($id)
    {
        Pimpinan::where('status', 'Aktif')->update(['status' => 'Nonaktif']);

        $pimpinan = Pimpinan::findOrFail($id);
        $pimpinan->update(['status' => 'Aktif']);

        return response()->json($pimpinan, 200);
    }
}

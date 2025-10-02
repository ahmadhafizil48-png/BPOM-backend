<?php

namespace App\Http\Controllers;

use App\Models\KomplainNilai;
use Illuminate\Http\Request;

class KomplainNilaiController extends Controller
{
    // daftar komplain (admin/pembimbing)
    public function index(Request $request)
    {
        $q = KomplainNilai::with(['user','penilaian']);
        if ($request->has('status')) {
            $q->where('status', $request->status);
        }
        return response()->json($q->get());
    }

    // buat komplain (mahasiswa)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'penilaian_id' => 'nullable|exists:penilaian_user,id',
            'proyek' => 'nullable|string|max:255',
            'isi_komplain' => 'required|string',
        ]);

        $komplain = KomplainNilai::create($validated);

        return response()->json([
            'message' => 'Komplain diterima (Pending)',
            'data' => $komplain
        ], 201);
    }

    // show
    public function show($id)
    {
        $k = KomplainNilai::with(['user','penilaian'])->findOrFail($id);
        return response()->json($k);
    }

    // update status (admin/pembimbing)
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Setuju,Tolak'
        ]);

        $k = KomplainNilai::findOrFail($id);
        $k->status = $request->status;
        $k->save();

        return response()->json([
            'message' => 'Status komplain diperbarui',
            'data' => $k
        ]);
    }

    // delete
    public function destroy($id)
    {
        $k = KomplainNilai::findOrFail($id);
        $k->delete();

        return response()->json(['message' => 'Komplain dihapus']);
    }
}

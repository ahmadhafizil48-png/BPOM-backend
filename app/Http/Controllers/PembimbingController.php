<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembimbing;

class PembimbingController extends Controller
{
    public function index()
    {
        return response()->json(Pembimbing::withCount('users')->get());
    }

    public function show($id)
    {
        $pembimbing = Pembimbing::with('users')->find($id);
        if (!$pembimbing) {
            return response()->json(['message' => 'Pembimbing tidak ditemukan'], 404);
        }
        return response()->json($pembimbing);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'divisi' => 'required|string',
            'no_hp' => 'nullable|string',
            'email' => 'required|email|unique:pembimbings',
        ]);

        $pembimbing = Pembimbing::create($validated);

        return response()->json([
            'message' => 'Pembimbing berhasil ditambahkan',
            'data' => $pembimbing
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pembimbing = Pembimbing::find($id);
        if (!$pembimbing) {
            return response()->json(['message' => 'Pembimbing tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama' => 'required|string',
            'divisi' => 'required|string',
            'no_hp' => 'nullable|string',
            'email' => 'required|email|unique:pembimbings,email,' . $id,
        ]);

        $pembimbing->update($validated);

        return response()->json([
            'message' => 'Pembimbing berhasil diperbarui',
            'data' => $pembimbing
        ]);
    }

    public function destroy($id)
    {
        $pembimbing = Pembimbing::find($id);
        if (!$pembimbing) {
            return response()->json(['message' => 'Pembimbing tidak ditemukan'], 404);
        }

        $pembimbing->delete();

        return response()->json(['message' => 'Pembimbing berhasil dihapus']);
    }

    public function assignUser(Request $request, $id)
{
    $pembimbing = Pembimbing::find($id);
    if (!$pembimbing) {
        return response()->json(['message' => 'Pembimbing tidak ditemukan'], 404);
    }

    $validated = $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:users,id',
    ]);

    // ✅ Update kolom pembimbing_id di tabel users
    \App\Models\User::whereIn('id', $validated['user_ids'])
        ->update(['pembimbing_id' => $pembimbing->id]);

    return response()->json([
        'message' => 'User berhasil di-assign ke pembimbing',
        'data' => $pembimbing->load('users')
    ]);
}

}

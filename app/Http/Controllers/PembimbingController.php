<?php

namespace App\Http\Controllers;

use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Http\Request;

class PembimbingController extends Controller
{
    public function index()
    {
        return Pembimbing::with('divisi', 'users')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'divisi_id' => 'required|exists:divisis,id',
            'no_hp' => 'required|string',
            'email' => 'required|email|unique:pembimbings,email',
        ]);

        $pembimbing = Pembimbing::create($validated);

        return response()->json([
            'message' => 'Pembimbing berhasil ditambahkan',
            'data' => $pembimbing
        ], 201);
    }

    public function assignUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $pembimbing = Pembimbing::findOrFail($id);
        $pembimbing->users()->syncWithoutDetaching([$request->user_id]);

        return response()->json([
            'message' => 'User berhasil di-assign ke pembimbing',
            'data' => $pembimbing->load('users')
        ]);
    }

    public function destroy($id)
    {
        Pembimbing::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Pembimbing berhasil dihapus'
        ]);
    }
}

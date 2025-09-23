<?php

namespace App\Http\Controllers;

use App\Models\ProyekUser;
use App\Models\User;
use Illuminate\Http\Request;

class ProyekUserController extends Controller
{
    // ✅ List semua proyek user
    public function index()
    {
        $proyek = ProyekUser::with(['user', 'pembimbing', 'divisi'])->get();
        return response()->json($proyek);
    }

    // ✅ Assign proyek ke user
    public function assignProject(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pembimbing_id' => 'nullable|exists:pembimbings,id',
            'divisi_id' => 'nullable|exists:divisis,id',
            'nama_proyek' => 'required|string|max:255',
        ]);

        $proyek = ProyekUser::create([
            'user_id' => $request->user_id,
            'pembimbing_id' => $request->pembimbing_id,
            'divisi_id' => $request->divisi_id,
            'nama_proyek' => $request->nama_proyek,
            'status' => 'belum_mulai',
        ]);

        return response()->json([
            'message' => 'Proyek berhasil ditugaskan!',
            'data' => $proyek
        ], 201);
    }

    // ✅ Update status proyek
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum_mulai,berjalan,selesai',
        ]);

        $proyek = ProyekUser::findOrFail($id);
        $proyek->status = $request->status;
        $proyek->save();

        return response()->json([
            'message' => 'Status proyek diperbarui!',
            'data' => $proyek
        ]);
    }

    // ✅ Progress (contoh dummy, bisa dikembangkan)
    public function progress($id)
    {
        $proyek = ProyekUser::with('user')->findOrFail($id);

        return response()->json([
            'message' => 'Progress proyek',
            'proyek' => $proyek,
            'progress' => rand(20, 100) . '%' // contoh dummy
        ]);
    }
}

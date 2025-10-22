<?php

namespace App\Http\Controllers;

use App\Models\UserAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAktifController extends Controller
{
    /**
     * Menampilkan daftar user aktif (hanya admin)
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat melihat data ini.'], 403);
        }

        $data = UserAktif::with(['user', 'divisi', 'pembimbing'])->get();

        return response()->json([
            'message' => 'Daftar User Aktif',
            'data' => $data
        ]);
    }

    /**
     * Menampilkan detail user aktif berdasarkan ID
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat melihat detail ini.'], 403);
        }

        $userAktif = UserAktif::with(['user', 'divisi', 'pembimbing'])->findOrFail($id);

        return response()->json([
            'message' => 'Detail User Aktif',
            'data' => [
                'nama' => $userAktif->user->name ?? '-',
                'email' => $userAktif->user->email ?? '-',
                'divisi' => $userAktif->divisi->nama_divisi ?? '-',
                'pembimbing' => $userAktif->pembimbing->nama ?? '-',
                'status_akun' => $userAktif->status_akun,
                'is_active' => $userAktif->is_active ? 'Aktif' : 'Nonaktif',
            ]
        ]);
    }

    /**
     * Tambah user aktif baru
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat menambah user aktif.'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'divisi_id' => 'required|exists:divisis,id',
            'pembimbing_id' => 'nullable|exists:pembimbings,id',
            'status_akun' => 'required|in:Ada Akun,Tidak Ada Akun',
        ]);

        $userAktif = UserAktif::create([
            'user_id' => $request->user_id,
            'divisi_id' => $request->divisi_id,
            'pembimbing_id' => $request->pembimbing_id,
            'status_akun' => $request->status_akun,
            'is_active' => true
        ]);

        return response()->json([
            'message' => 'User aktif berhasil ditambahkan',
            'data' => $userAktif
        ], 201);
    }

    /**
     * Update data user aktif (edit)
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat memperbarui data.'], 403);
        }

        $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'pembimbing_id' => 'nullable|exists:pembimbings,id',
            'status_akun' => 'required|in:Ada Akun,Tidak Ada Akun',
        ]);

        $userAktif = UserAktif::findOrFail($id);
        $userAktif->update($request->only(['divisi_id', 'pembimbing_id', 'status_akun']));

        return response()->json([
            'message' => 'Data user aktif berhasil diperbarui',
            'data' => $userAktif
        ]);
    }

    /**
     * Nonaktifkan user aktif (soft delete)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat menonaktifkan user.'], 403);
        }

        $userAktif = UserAktif::findOrFail($id);
        $userAktif->update(['is_active' => false]);

        return response()->json([
            'message' => 'User berhasil dinonaktifkan'
        ]);
    }
}

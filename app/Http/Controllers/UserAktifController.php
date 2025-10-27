<?php

namespace App\Http\Controllers;

use App\Models\UserAktif;
use App\Models\Formulir;
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

        // Ambil semua user aktif dengan relasi user, divisi, pembimbing, dan formulir
        $data = UserAktif::with(['user', 'divisi', 'pembimbing'])->get();

        return response()->json([
            'message' => 'Daftar User Aktif',
            'data' => $data
        ]);
    }

    /**
     * Menampilkan detail user aktif berdasarkan ID
     * (data detail diambil dari tabel formulir)
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat melihat detail ini.'], 403);
        }

        // Ambil data user aktif
        $userAktif = UserAktif::with(['user', 'divisi', 'pembimbing'])->findOrFail($id);

        // Ambil data formulir berdasarkan user_id
        $form = Formulir::where('user_id', $userAktif->user_id)->first();

        if (!$form) {
            return response()->json([
                'message' => 'Data formulir belum tersedia untuk user ini.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail User Aktif',
            'data' => [
                'nama' => $userAktif->user->name ?? '-',
                'nik' => $form->nik ?? '-',
                'nim' => $form->nim ?? '-',
                'nohp' => $form->no_hp ?? '-',
                'universitas' => $form->universitas ?? '-',
                'alamatuniv' => $form->alamat_universitas ?? '-',
                'jurusan' => $form->jurusan ?? '-',
                'semester' => $form->semester ?? '-',
                'divisi' => $userAktif->divisi->nama_divisi ?? '-',
                'pembimbing' => $userAktif->pembimbing->nama ?? '-',
                'tanggalmulai' => $form->waktu_mulai ? date('d F Y', strtotime($form->waktu_mulai)) : '-',
                'tanggalselesai' => $form->waktu_selesai ? date('d F Y', strtotime($form->waktu_selesai)) : '-',
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

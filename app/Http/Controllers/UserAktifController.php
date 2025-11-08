<?php

namespace App\Http\Controllers;

use App\Models\UserAktif;
use App\Models\Formulir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAktifController extends Controller
{
    /**
     * Menampilkan daftar user aktif (Admin bisa semua, Pembimbing hanya bimbingannya)
     */
    public function index()
    {
        $user = Auth::user();

        // ✅ Jika admin → lihat semua user aktif
        if ($user->role_id == 1) {
            $data = UserAktif::with(['user', 'divisi', 'pembimbing'])->get();
        }
        // ✅ Jika pembimbing → lihat hanya user bimbingannya
        elseif ($user->role_id == 2) {
            $data = UserAktif::with(['user', 'divisi', 'pembimbing'])
                ->where('pembimbing_id', $user->id)
                ->get();
        }
        // ❌ Selain admin & pembimbing → tolak
        else {
            return response()->json(['message' => 'Akses ditolak, hanya admin atau pembimbing yang dapat melihat data ini.'], 403);
        }

        return response()->json([
            'message' => 'Daftar User Aktif',
            'data' => $data
        ]);
    }

    /**
     * Endpoint khusus pembimbing
     */
    public function indexPembimbing()
    {
        $user = Auth::user();

        if ($user->role_id != 2) {
            return response()->json(['message' => 'Akses ditolak, hanya pembimbing yang dapat melihat data ini.'], 403);
        }

        $data = UserAktif::with(['user', 'divisi', 'pembimbing'])
            ->where('pembimbing_id', $user->id)
            ->get();

        return response()->json([
            'message' => 'Daftar User Aktif Bimbingan Pembimbing',
            'data' => $data
        ]);
    }

    /**
     * Menampilkan detail user aktif berdasarkan ID
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!in_array($user->role_id, [1, 2])) {
            return response()->json(['message' => 'Akses ditolak, hanya admin atau pembimbing yang dapat melihat detail ini.'], 403);
        }

        $userAktif = UserAktif::with(['user', 'divisi', 'pembimbing'])->findOrFail($id);

        if ($user->role_id == 2 && $userAktif->pembimbing_id != $user->id) {
            return response()->json(['message' => 'Akses ditolak, bukan bimbingan Anda.'], 403);
        }

        $form = Formulir::where('user_id', $userAktif->user_id)->first();

        if (!$form) {
            return response()->json(['message' => 'Data formulir belum tersedia untuk user ini.'], 404);
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
     * Tambah user aktif baru (Admin only)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat menambah user aktif.'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'divisi_id' => 'required|exists:divisis,id',
            'pembimbing_id' => 'nullable|exists:users,id',
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
     * Update data user aktif (Admin only)
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat memperbarui data.'], 403);
        }

        $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'pembimbing_id' => 'nullable|exists:users,id',
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
     * Nonaktifkan user aktif (Admin only)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role_id != 1) {
            return response()->json(['message' => 'Akses ditolak, hanya admin yang dapat menonaktifkan user.'], 403);
        }

        $userAktif = UserAktif::findOrFail($id);
        $userAktif->update(['is_active' => false]);

        return response()->json([
            'message' => 'User berhasil dinonaktifkan'
        ]);
    }
}

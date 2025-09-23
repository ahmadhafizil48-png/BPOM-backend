<?php

namespace App\Http\Controllers;

use App\Models\DaftarAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DaftarAkunController extends Controller
{
    // 🔹 Ambil semua akun (dengan filter role opsional)
    public function index(Request $request)
    {
        $query = DaftarAkun::query();

        if ($request->has('role') && $request->role != 'Semua') {
            $query->where('role', $request->role);
        }

        return response()->json($query->get());
    }

    // 🔹 Tambah akun baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|unique:daftar_akun',
            'email' => 'required|email|unique:daftar_akun',
            'nama' => 'required',
            'role' => 'required|in:Admin,Pembimbing,User',
            'status' => 'required|in:Aktif,Nonaktif',
            'password' => 'required|min:6',
        ]);

        // Hash password sebelum simpan
        $data['password'] = Hash::make($data['password']);

        $akun = DaftarAkun::create($data);

        return response()->json([
            'message' => 'Akun berhasil ditambahkan',
            'data' => $akun
        ], 201);
    }

    // 🔹 Lihat detail akun
    public function show($id)
    {
        $akun = DaftarAkun::find($id);

        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        return response()->json($akun);
    }

    // 🔹 Update akun (edit)
    public function update(Request $request, $id)
    {
        $akun = DaftarAkun::find($id);

        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        $data = $request->validate([
            'username' => 'sometimes|required|unique:daftar_akun,username,' . $id,
            'email' => 'sometimes|required|email|unique:daftar_akun,email,' . $id,
            'nama' => 'sometimes|required',
            'role' => 'sometimes|required|in:Admin,Pembimbing,User',
            'status' => 'sometimes|required|in:Aktif,Nonaktif',
        ]);

        $akun->update($data);

        return response()->json([
            'message' => 'Akun berhasil diperbarui',
            'data' => $akun
        ]);
    }

    // 🔹 Reset password
    public function resetPassword(Request $request, $id)
    {
        $akun = DaftarAkun::find($id);

        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        $request->validate([
            'password' => 'required|min:6',
        ]);

        $akun->password = Hash::make($request->password);
        $akun->save();

        return response()->json([
            'message' => 'Password berhasil direset'
        ]);
    }

    // 🔹 Update status (Aktif / Nonaktif)
    public function updateStatus(Request $request, $id)
    {
        $akun = DaftarAkun::find($id);

        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        $request->validate([
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $akun->status = $request->status;
        $akun->save();

        return response()->json([
            'message' => 'Status akun berhasil diperbarui',
            'data' => $akun
        ]);
    }

    // 🔹 Hapus akun
    public function destroy($id)
    {
        $akun = DaftarAkun::find($id);

        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        $akun->delete();

        return response()->json([
            'message' => 'Akun berhasil dihapus'
        ]);
    }
}

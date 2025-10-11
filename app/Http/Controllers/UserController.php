<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Daftar user',
            'data'    => User::with(['role', 'pembimbing'])->orderBy('id', 'desc')->get(),
        ]);
    }

    // ✅ DETAIL USER (untuk admin)
    public function show($id)
    {
        $user = User::with(['role', 'pembimbing'])->find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail user berhasil diambil',
            'data'    => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'role'          => $user->role ? $user->role->name : 'Tidak ada role',
                'pembimbing'    => $user->pembimbing ? $user->pembimbing->nama : 'Tidak ada pembimbing',
                'is_active'     => $user->is_active,
                'created_at'    => $user->created_at,
                'updated_at'    => $user->updated_at,
            ],
        ]);
    }

    // ✅ DETAIL PELAMAR (berdasarkan data di tabel FORMULIR)
    public function detail($id)
    {
        $user = User::with(['formulir', 'role', 'pembimbing'])->find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if (!$user->formulir) {
            return response()->json(['message' => 'Data formulir untuk user ini tidak ditemukan'], 404);
        }

        $f = $user->formulir;

        return response()->json([
            'message' => 'Detail pelamar berhasil diambil',
            'data'    => [
                'user' => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'role'      => $user->role ? $user->role->name : '-',
                    'pembimbing'=> $user->pembimbing ? $user->pembimbing->nama : '-',
                    'is_active' => $user->is_active,
                ],
                'formulir' => [
                    'no_formulir'        => $f->no_formulir,
                    'nik'                => $f->nik,
                    'nim'                => $f->nim,
                    'no_hp'              => $f->no_hp,
                    'universitas'        => $f->universitas,
                    'alamat_universitas' => $f->alamat_universitas,
                    'jurusan'            => $f->jurusan,
                    'semester'           => $f->semester,
                    'divisi_tujuan'      => $f->divisi_tujuan,
                    'waktu_mulai'        => $f->waktu_mulai,
                    'waktu_selesai'      => $f->waktu_selesai,
                    'status_pengajuan'   => $f->status_pengajuan,
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:6',
            'role_id'       => 'nullable|integer',
            'pembimbing_id' => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        $user = User::create($validated);

        return response()->json([
            'message' => 'User berhasil dibuat',
            'data'    => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'sometimes|string|min:6',
            'role_id'  => 'sometimes|integer',
            'is_active'=> 'sometimes|boolean',
        ]);

        if ($request->has('name'))      $user->name = $request->name;
        if ($request->has('email'))     $user->email = $request->email;
        if ($request->has('password'))  $user->password = Hash::make($request->password);
        if ($request->has('role_id'))   $user->role_id = $request->role_id;
        if ($request->has('is_active')) $user->is_active = (bool)$request->is_active;

        $user->save();

        return response()->json([
            'message' => 'User berhasil diupdate',
            'data'    => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // 📝 Register akun baru (default role: user)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            // 'role_id'  => 3, // misalnya 3 = mahasiswa/user biasa
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Register berhasil',
            'user'    => $user,
        ], 201);
    }

    // 🔑 Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // login pakai sanctum
        Auth::login($user);
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'user'    => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'role'   => $user->role ? $user->role->name : null,
                'divisi' => $user->divisi ? $user->divisi->nama : null,
            ],
            'token' => $token,
        ]);
    }

    // 🚪 Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil',
        ]);
    }

    // ✏️ Ubah profil admin (name & email)
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Profil berhasil diperbarui',
            'user'    => $user,
        ]);
    }

    // 🔒 Ganti password admin
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password'      => 'required|string',
            'new_password'      => 'required|string|min:6|confirmed', // harus ada new_password_confirmation
        ]);

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Password lama salah',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Password berhasil diubah',
        ]);
    }
}

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
            'role_id'  => 3, // misalnya 3 = mahasiswa/user biasa
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
}

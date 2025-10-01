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
            'data'    => User::orderBy('id','desc')->get(),
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User tidak ditemukan'], 404);

        return response()->json(['data' => $user]);
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
            'email'    => ['sometimes','email', Rule::unique('users','email')->ignore($user->id)],
            'password' => 'sometimes|string|min:6',
            'role_id'  => 'sometimes|integer',
            'is_active'=> 'sometimes|boolean',
        ]);

        if ($request->has('name'))     $user->name = $request->name;
        if ($request->has('email'))    $user->email = $request->email;
        if ($request->has('password')) $user->password = Hash::make($request->password);
        if ($request->has('role_id'))  $user->role_id = $request->role_id;
        if ($request->has('is_active'))$user->is_active = (bool)$request->is_active;

        $user->save();

        return response()->json([
            'message' => 'User berhasil diupdate',
            'data'    => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User tidak ditemukan'], 404);

        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus']);
    }
}

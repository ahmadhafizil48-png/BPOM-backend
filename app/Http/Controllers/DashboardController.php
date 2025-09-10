<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class DashboardController extends Controller
{
    public function adminDashboard(Request $request)
    {
        $user = $request->user();
        
        // Manual get role
        $userRole = Role::find($user->role_id);
        
        $stats = [
            'total_users' => User::where('role_id', 3)->count(), // role_id 3 = user
            'total_pembimbing' => User::where('role_id', 2)->count(), // role_id 2 = pembimbing
            'total_admin' => User::where('role_id', 1)->count(), // role_id 1 = admin
            'total_magang' => 0
        ];

        return response()->json([
            'status' => true,
            'message' => 'Dashboard admin',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'role_name' => $userRole ? $userRole->name : 'no role'
                ],
                'statistics' => $stats
            ]
        ]);
    }

    public function pembimbingDashboard(Request $request)
    {
        $user = $request->user();
        
        // Manual get role
        $userRole = Role::find($user->role_id);
        
        $stats = [
            'mahasiswa_bimbingan' => 0,
            'magang_aktif' => 0,
            'magang_selesai' => 0,
            'menunggu_approval' => 0
        ];

        return response()->json([
            'status' => true,
            'message' => 'Dashboard pembimbing',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'role_name' => $userRole ? $userRole->name : 'no role'
                ],
                'statistics' => $stats
            ]
        ]);
    }

    public function userDashboard(Request $request)
    {
        $user = $request->user();
        
        // Manual get role
        $userRole = Role::find($user->role_id);
        
        $stats = [
            'status_magang' => 'belum daftar',
            'durasi_magang' => '-',
            'progress' => '0%',
            'divisi' => '-'
        ];

        return response()->json([
            'status' => true,
            'message' => 'Dashboard user',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'role_name' => $userRole ? $userRole->name : 'no role'
                ],
                'statistics' => $stats,
                'my_magang' => []
            ]
        ]);
    }
}
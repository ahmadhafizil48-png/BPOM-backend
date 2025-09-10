<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek user sudah login
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        $user = $request->user();
        
        // Cek apakah user punya role_id
        if (!$user->role_id) {
            return response()->json([
                'status' => false,
                'message' => 'User has no role assigned.',
                'your_role' => 'no role'
            ], 403);
        }

        // Manual cek role tanpa relationship
        $userRole = Role::find($user->role_id);
        
        if (!$userRole) {
            return response()->json([
                'status' => false,
                'message' => 'User role not found in database.',
                'role_id' => $user->role_id
            ], 403);
        }

        // Cek nama role
        if ($userRole->name !== $role) {
            return response()->json([
                'status' => false,
                'message' => "Access denied. Only {$role} can access this resource.",
                'your_role' => $userRole->name,
                'required_role' => $role
            ], 403);
        }

        return $next($request);
    }
}
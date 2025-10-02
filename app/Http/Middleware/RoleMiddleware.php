<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Cek user sudah login
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        // Ambil nama role dari relasi
        $userRole = $user->role?->name;

        // Cek role
        if (!$userRole || !in_array($userRole, $roles)) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only ' . implode(', ', $roles) . ' can access this resource.',
                'your_role' => $userRole,
                'required_role' => $roles
            ], 403);
        }

        return $next($request);
    }
}

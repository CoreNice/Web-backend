<?php

namespace App\Http\Middleware;

use Closure;

class EnsureAdmin
{
    public function handle($request, Closure $next)
    {
        // Get user from request (set by ApiAuth middleware)
        $user = $request->user() ?? $request->request->get('auth_user');

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check role
        if (!isset($user->role) || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}

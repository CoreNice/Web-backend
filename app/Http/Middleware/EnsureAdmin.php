<?php

namespace App\Http\Middleware;

use Closure;

class EnsureAdmin
{
    public function handle($request, Closure $next)
    {
        $user = $request->user() ?? $request->request->get('auth_user');

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!isset($user->role) || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}

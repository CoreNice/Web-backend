<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class ApiAuth
{
    public function handle($request, Closure $next)
    {
        $auth = $request->header('Authorization'); // Bearer <token>

        if (!$auth || !str_starts_with($auth, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = substr($auth, 7);

        $user = User::findByToken($token);

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // inject the user to request
        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}

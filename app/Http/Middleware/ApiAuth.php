<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class ApiAuth
{
    public function handle($request, Closure $next)
    {
        $auth = $request->header('Authorization');

        if (!$auth || !str_starts_with($auth, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = substr($auth, 7);
        $user = User::where('api_tokens', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}

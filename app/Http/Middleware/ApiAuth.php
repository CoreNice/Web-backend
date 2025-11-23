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
        $user = User::findByToken($token);

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $request->request->set('auth_user', $user);
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}

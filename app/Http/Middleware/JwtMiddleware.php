<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        try {
            // Parse & validate token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['message' => 'Token invalid'], 401);
            }

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                try {
                    // Refresh token automatically
                    $newToken = JWTAuth::refresh();
                    return response()->json([
                        'message' => 'Token refreshed',
                        'token' => $newToken,
                    ], 200);
                } catch (Exception $e) {
                    return response()->json(['message' => 'Token expired'], 401);
                }
            }

            return response()->json(['message' => 'Token not found'], 401);
        }

        return $next($request);
    }
}


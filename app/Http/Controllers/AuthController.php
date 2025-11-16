<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:40',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        return response()->json([
            'message' => 'Register success',
            'user' => $user
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', strtolower($request->email))->first();

        if (!$user) {
            return response()->json(['message' => 'Akun belum terdaftar'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'type' => 'bearer',
            'user' => $user
        ]);
    }

    // GET AUTH USER
    public function me(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    // LOGOUT
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logout success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to logout'], 500);
        }
    }
}

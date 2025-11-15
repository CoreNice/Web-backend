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
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name'  => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        return response()->json([
            'message' => 'Register success',
            'user' => $user
        ]);
    }

    // LOGIN
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login'], 401);
        }

        return response()->json([
            'token' => $token,
            'type' => 'bearer'
        ]);
    }

    // GET AUTH USER
    public function me(Request $request)
    {
        return response()->json(JWTAuth::user());
    }

    // LOGOUT
    public function logout()
    {
        JWTAuth::invalidate();

        return response()->json(['message' => 'Logout success']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|min:3|max:40',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'api_tokens' => []
        ]);

        return response()->json([
            'message' => 'Register success',
            'user'    => $user
        ]);
    }

    // REGISTER ADMIN
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name'                  => 'required|min:3|max:40',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'api_tokens' => []
        ]);

        return response()->json([
            'message' => 'Admin register success',
            'user'    => $user
        ]);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', strtolower($request->email))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->generateApiToken();

        return response()->json([
            'message' => 'Login success',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    // ME
    public function me(Request $request)
    {
        return response()->json($request->auth_user);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $user = $request->auth_user;
        $auth = $request->header('Authorization');
        $token = substr($auth, 7);

        $user->revokeToken($token);

        return response()->json(['message' => 'Logout success']);
    }
}

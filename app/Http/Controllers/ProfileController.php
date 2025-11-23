<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($request->has('name') && $request->name) {
            $user->name = $request->name;
        }

        if ($request->has('avatarUrl') && $request->avatarUrl) {
            $user->avatarUrl = $request->avatarUrl;
        }

        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'Profile updated successfully'
        ]);
    }
}

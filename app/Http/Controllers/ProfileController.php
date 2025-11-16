<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->auth_user;

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($request->name) {
            $user->name = $request->name;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');

            $user->avatarUrl = url("storage/" . $path);
        }

        $user->save();

        return response()->json([
            'user' => $user
        ]);
    }
}

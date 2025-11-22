<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function get(Request $request)
    {
        $user = $request->auth_user;

        $favorites = Favorite::where('user_id', $user->_id)
            ->select('product_id', 'product_name')
            ->get();

        return response()->json($favorites);
    }

    public function add(Request $request)
    {
        $user = $request->auth_user;

        $validated = $request->validate([
            'product_id' => 'required|string',
            'product_name' => 'required|string',
        ]);

        // Check if already favorited
        $existing = Favorite::where('user_id', $user->_id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already favorited'], 400);
        }

        $favorite = Favorite::create([
            'user_id' => $user->_id,
            'product_id' => $validated['product_id'],
            'product_name' => $validated['product_name'],
        ]);

        return response()->json([
            'message' => 'Added to favorites',
            'favorite' => $favorite
        ], 201);
    }

    public function remove(Request $request)
    {
        $user = $request->auth_user;

        $validated = $request->validate([
            'product_id' => 'required|string',
        ]);

        $favorite = Favorite::where('user_id', $user->_id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Favorite not found'], 404);
        }

        $favorite->delete();

        return response()->json(['message' => 'Removed from favorites']);
    }
}

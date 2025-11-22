<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function get(Request $request)
    {
        $user = $request->auth_user;

        $cart = Cart::where('user_id', $user->_id)->first();

        if (!$cart) {
            return response()->json(['user_id' => $user->_id, 'items' => []]);
        }

        return response()->json($cart);
    }

    public function update(Request $request)
    {
        $user = $request->auth_user;

        $validated = $request->validate([
            'items' => 'required|array',
        ]);

        $cart = Cart::updateOrCreate(
            ['user_id' => $user->_id],
            ['items' => $validated['items']]
        );

        return response()->json($cart);
    }

    public function clear(Request $request)
    {
        $user = $request->auth_user;

        Cart::where('user_id', $user->_id)->delete();

        return response()->json(['message' => 'Cart cleared']);
    }
}

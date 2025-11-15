<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartController extends Controller
{
    public function get()
    {
        $user = JWTAuth::user();
        return Cart::firstOrCreate(['user_id' => $user->_id]);
    }

    public function update(Request $request)
    {
        $user = JWTAuth::user();

        $cart = Cart::firstOrCreate(['user_id' => $user->_id]);
        $cart->items = $request->items;
        $cart->save();

        return response()->json($cart);
    }

    public function clear()
    {
        $user = JWTAuth::user();

        $cart = Cart::firstOrCreate(['user_id' => $user->_id]);
        $cart->items = [];
        $cart->save();

        return response()->json(['message' => 'Cart cleared']);
    }
}

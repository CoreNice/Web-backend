<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $user = JWTAuth::user();

        $order = Order::create([
            'user_id' => $user->_id,
            'items' => $request->items,
            'subtotal' => $request->subtotal,
            'shipping_cost' => $request->shipping_cost,
            'total' => $request->total,
            'customer' => $request->customer,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Order created',
            'order' => $order
        ]);
    }
}

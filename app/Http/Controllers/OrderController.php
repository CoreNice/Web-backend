<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->auth_user;

        $validated = $request->validate([
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'shipping_cost' => 'required|numeric',
            'total' => 'required|numeric',
            'customer' => 'required|array',
            'customer.name' => 'required|string',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string',
        ]);

        $order = Order::create([
            'user_id' => $user->_id,
            'items' => $validated['items'],
            'subtotal' => $validated['subtotal'],
            'shipping_cost' => $validated['shipping_cost'],
            'total' => $validated['total'],
            'customer' => $validated['customer'],
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }

    public function index(Request $request)
    {
        $user = $request->auth_user;

        $orders = Order::where('user_id', $user->_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $user = $request->auth_user;
        $order = Order::find($id);

        if (!$order || $order->user_id !== $user->_id) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }
}

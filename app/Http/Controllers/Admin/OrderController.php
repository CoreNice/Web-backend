<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Get all orders grouped by status
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

    /**
     * Get orders by status (delivery, pickup, finished)
     */
    public function getByStatus(Request $request, $status)
    {
        $validated = $request->validate([
            'status' => 'in:delivery,pickup,finished'
        ]);

        $orders = Order::where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:delivery,pickup,finished'
        ]);

        $order->status = $validated['status'];
        $order->save();

        return response()->json([
            'message' => 'Order status updated',
            'order' => $order
        ]);
    }

    /**
     * Mark order as finished (move to finished status)
     */
    public function finish($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status = 'finished';
        $order->save();

        return response()->json([
            'message' => 'Order marked as finished',
            'order' => $order
        ]);
    }

    /**
     * Delete/clear an order
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted']);
    }

    /**
     * Get order statistics
     */
    public function statistics()
    {
        $stats = [
            'delivery' => Order::where('status', 'delivery')->count(),
            'pickup' => Order::where('status', 'pickup')->count(),
            'finished' => Order::where('status', 'finished')->count(),
            'total' => Order::count(),
            'total_revenue' => Order::sum('total'),
        ];

        return response()->json($stats);
    }
}

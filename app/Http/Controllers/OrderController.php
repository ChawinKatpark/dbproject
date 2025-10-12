<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display all orders (for admin dashboard).
     */
    public function index()
    {
        // Load user relationship for display
        $orders = Order::with('user')->latest()->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show edit form for an order.
     */
    public function edit(string $id)
    {
        $order = Order::with('user')->findOrFail($id);

        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified order (status or payment status).
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,completed,canceled',
            'payment_status' => 'nullable|string|in:pending,verified,rejected',
        ]);

        $order = Order::with('orderItems.product')->findOrFail($id);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Update order fields
        $order->status = $newStatus;
        $order->payment_status = $request->payment_status ?? $order->payment_status;
        $order->save();

        // ✅ Restore stock if order is canceled (since stock was already deducted at checkout)
        if (
            $oldStatus !== 'canceled' &&
            $newStatus === 'canceled'
        ) {
            foreach ($order->orderItems as $item) {
                $product = $item->product;

                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order updated successfully!');
    }


    /**
     * Delete an order.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }
}
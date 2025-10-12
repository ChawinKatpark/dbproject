<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function show()
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();
        return view('checkout.index', compact('cartItems'));
    }

    /**
     * Handle checkout form submission
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'city'             => 'required|string|max:255',
            'state'            => 'required|string|max:255',
            'postal_code'      => 'required|string|max:20',
            'country'          => 'required|string|max:255',
            'payment_time'     => 'required|date',
            'payment_slip'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->get();

        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout.show')->withErrors(['Your cart is empty!']);
        }

        // Calculate total
        $totalAmount = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Store slip image
        $slipPath = $request->file('payment_slip')->store('slips', 'public');

        // Combine full address
        $fullAddress = implode(', ', [
            $request->shipping_address,
            $request->city,
            $request->state,
            $request->postal_code,
            $request->country,
        ]);

        // Create order
        $order = Order::create([
            'user_id'           => Auth::id(),
            'shipping_address'  => $fullAddress,
            'total_amount'      => $totalAmount,
            'status'            => 'pending',
            'payment_time'      => $request->payment_time,
            'payment_slip_path' => $slipPath,
            'payment_status'    => 'pending',
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity'   => $cartItem->quantity,
                'price'      => $cartItem->product->price,
            ]);

            // Decrease product stock
            $product = $cartItem->product;
            $product->stock = max($product->stock - $cartItem->quantity, 0); // prevent negative stock
            $product->save();
        }

        // Clear cart
        Cart::where('user_id', Auth::id())->delete();

        // Redirect back with session message (for success toast)
        return redirect()->route('checkout.show')
        ->with('order_success', 'Purchase successful!');
    }
}
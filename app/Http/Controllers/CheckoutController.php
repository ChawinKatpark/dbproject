<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout.show')->withErrors(['Your cart is empty!']);
        }

        $totalAmount = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $slipPath = $request->file('payment_slip')->store('slips', 'public');
        $fullAddress = implode(', ', [
            $request->shipping_address,
            $request->city,
            $request->state,
            $request->postal_code,
            $request->country,
        ]);

        DB::transaction(function () use ($cartItems, $totalAmount, $slipPath, $fullAddress, $request) {
            $order = Order::create([
                'user_id'           => Auth::id(),
                'shipping_address'  => $fullAddress,
                'total_amount'      => $totalAmount,
                'status'            => 'pending',
                'payment_time'      => $request->payment_time,
                'payment_slip_path' => $slipPath,
                'payment_status'    => 'pending',
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'price'      => $cartItem->product->price,
                ]);

                $product = $cartItem->product;
                $product->stock = max($product->stock - $cartItem->quantity, 0);
                $product->save();
            }

            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('checkout.show')->with('order_success', 'Purchase successful!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get cart items with related products eager loaded
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        $updated = false; // Flag to know if we updated anything

        foreach ($cartItems as $item) {
            if (!$item->product) {
                // Product might have been deleted, remove this cart item
                $item->delete();
                $updated = true;
                continue;
            }

            // If quantity in cart is greater than stock, update it
            if ($item->quantity > $item->product->stock) {
                if ($item->product->stock > 0) {
                    $item->quantity = $item->product->stock;
                    $item->save();
                } else {
                    // No stock at all, remove the item from cart
                    $item->delete();
                }
                $updated = true;
            }
        }

        // Reload updated cart items after sync
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($updated) {
            session()->flash('warning', 'Some cart items were updated or removed due to stock availability.');
        }

        return view('cart.index', compact('cartItems'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock) {
            return redirect()->route('cart.index')
                ->with('error', "Sorry, only {$product->stock} units available for {$product->name}.");
        }

        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return redirect()->route('home')->with('success', 'Product added to cart successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        if ($request->has('increase')) {
            $cart->quantity += 1;
        } elseif ($request->has('decrease')) {
            $cart->quantity = max(1, $cart->quantity - 1); // prevent 0
        } else {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);
            $cart->quantity = $request->quantity;
        }

        // Check stock again
        if ($cart->quantity > $cart->product->stock) {
            return redirect()->route('cart.index')
                ->with('error', "Only {$cart->product->stock} units available for {$cart->product->name}.");
        }

        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('cart.index')->with('success','Product removed from cart.');
    }
}

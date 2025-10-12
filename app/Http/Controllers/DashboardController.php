<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all orders of the authenticated user, eager load orderItems and product
        $orders = auth()->user()->orders()->with('orderItems.product')->latest()->get();

        return view('dashboard', compact('orders'));
    }
}

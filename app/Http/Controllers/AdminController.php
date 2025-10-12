<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;


class AdminController extends Controller
{
    public function index()
    {
        $recentOrders = Order::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.index', compact('recentOrders', 'recentUsers'));
    }
}

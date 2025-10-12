<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::take(8)->get();
        $categories = Category::take(4)->get();

        return view('welcome', compact('products','categories'));
    }
}

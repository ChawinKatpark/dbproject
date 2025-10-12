<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('products', ProductController::class)->only(['index','show']);
Route::resource('categories', CategoryController::class)->only(['index','show']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(AdminMiddleware::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);

    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart',[CartController::class, 'index'])->name('cart.index');
    Route::post('/cart',[CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cart}',[CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}',[CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout',[CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout',[CheckoutController::class, 'process'])->name('checkout.process');
});

require __DIR__.'/auth.php';

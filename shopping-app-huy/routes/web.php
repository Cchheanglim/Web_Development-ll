<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes are grouped by who is allowed to use them. The "role" middleware
| (App\Http\Middleware\RoleMiddleware) checks the logged-in user's `role`
| column against the allowed roles passed to it, e.g. role:seller,admin
|
| IMPORTANT ORDERING NOTE: Laravel matches routes top-to-bottom, and
| '/products/{product}' would otherwise swallow '/products/create' by
| treating "create" as a product ID. So the fixed "create" route must be
| registered BEFORE the wildcard "{product}" route below it.
*/

// -------------------- Public --------------------
Route::get('/', [ProductController::class, 'home'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// -------------------- Seller only (must come before /products/{product}) --------------------
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// -------------------- Public (wildcard — must stay after /products/create) --------------------
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/sellers/{seller}', [SellerController::class, 'show'])->name('sellers.show');

// -------------------- Guest only --------------------
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// -------------------- Any authenticated user --------------------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/chat/{product}/{seller}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{product}/{seller}', [ChatController::class, 'store'])->name('chat.store');
    // Lightweight polling endpoint the chat page hits every few seconds
    Route::get('/chat/{product}/{seller}/messages', [ChatController::class, 'messages'])->name('chat.messages');

    Route::get('/products/{product}/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/products/{product}/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/review', [ReviewController::class, 'store'])->name('reviews.store');

    Route::post('/products/{product}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/checkout', [CartController::class, 'placeOrder'])->name('cart.placeOrder');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
});

// -------------------- Admin only --------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
});

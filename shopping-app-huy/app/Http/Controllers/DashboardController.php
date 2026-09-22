<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Routes the logged-in user to the right dashboard view based on
     * their role. Admins never land here — they're routed to /admin
     * straight from login, but this is a safe fallback too.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isSeller()) {
            $products = $user->products()->with('images')->latest()->get();

            // One row per distinct (product, buyer) conversation the seller is part of.
            $threads = Chat::with(['product', 'buyer'])
                ->where('seller_id', $user->id)
                ->latest()
                ->get()
                ->unique(fn (Chat $chat) => $chat->product_id . '-' . $chat->buyer_id);

            $orders = Order::with(['product', 'buyer'])
                ->where('seller_id', $user->id)
                ->latest()
                ->get();

            return view('dashboard.seller', compact('products', 'threads', 'orders'));
        }

        // Buyer dashboard
        $threads = Chat::with(['product', 'seller'])
            ->where('buyer_id', $user->id)
            ->latest()
            ->get()
            ->unique(fn (Chat $chat) => $chat->product_id . '-' . $chat->seller_id);

        $orders = Order::with(['product', 'seller'])
            ->where('buyer_id', $user->id)
            ->latest()
            ->get();

        $favorites = $user->favorites()->with(['images', 'seller', 'reviews'])->latest('favorites.created_at')->get();
        $favoriteIds = $favorites->pluck('id');

        return view('dashboard.buyer', compact('threads', 'orders', 'favorites', 'favoriteIds'));
    }
}

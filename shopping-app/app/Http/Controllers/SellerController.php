<?php

namespace App\Http\Controllers;

use App\Models\User;

class SellerController extends Controller
{
    /** Public storefront page — a seller's profile plus their full catalog. */
    public function show(\Illuminate\Http\Request $request, User $seller)
    {
        abort_unless($seller->isSeller(), 404);

        $products = $seller->products()
            ->with(['images', 'reviews'])
            ->latest()
            ->paginate(12);

        $allReviews = \App\Models\Review::whereIn('product_id', $seller->products()->pluck('id'))->get();
        $averageRating = $allReviews->isEmpty() ? null : round($allReviews->avg('rating'), 1);

        $favoriteIds = $request->user()
            ? $request->user()->favorites()->pluck('products.id')
            : collect();

        return view('sellers.show', [
            'seller' => $seller,
            'products' => $products,
            'averageRating' => $averageRating,
            'reviewCount' => $allReviews->count(),
            'favoriteIds' => $favoriteIds,
        ]);
    }
}

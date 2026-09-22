<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Leave a review for a completed order, optionally with photos.
     * Only that order's buyer may review it, only once it's completed,
     * and only once — the unique constraint on reviews.order_id backs
     * this up at the database level.
     */
    public function store(Request $request, Order $order)
    {
        abort_if($request->user()->id !== $order->buyer_id, 403, 'Only the buyer can review this order.');
        abort_if($order->status !== 'completed', 403, 'You can only review a completed order.');
        abort_if($order->review()->exists(), 403, 'You already reviewed this order.');

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'max:4096'],
        ]);

        $review = Review::create([
            'order_id' => $order->id,
            'product_id' => $order->product_id,
            'buyer_id' => $order->buyer_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        foreach ($request->file('images', []) as $file) {
            $path = $file->store('reviews', 'public');

            ReviewImage::create([
                'review_id' => $review->id,
                'image_path' => $path,
            ]);
        }

        return back()->with('status', 'Thanks for the review!');
    }
}

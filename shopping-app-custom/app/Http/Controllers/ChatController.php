<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Show (or start) a chat thread about one product, between a buyer
     * and the given seller. A thread is uniquely identified by the
     * (product_id, buyer_id, seller_id) combination.
     *
     * - If the logged-in user IS the seller, they must say which buyer's
     *   thread they're opening via ?buyer={id} (the seller dashboard's
     *   chat list already links here with that in place).
     * - Otherwise the logged-in user themself is treated as the buyer.
     */
    public function show(Request $request, Product $product, User $seller)
    {
        $buyer = $this->resolveBuyer($request, $seller);

        $messages = $this->threadQuery($product, $buyer, $seller)->get();

        return view('chat.show', compact('product', 'seller', 'buyer', 'messages'));
    }

    /** Post a new chat message into the thread. */
    public function store(Request $request, Product $product, User $seller)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $buyer = $this->resolveBuyer($request, $seller);

        Chat::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'product_id' => $product->id,
            'sender_id' => $request->user()->id,
            'message' => $validated['message'],
        ]);

        return redirect()->route('chat.show', array_filter([
            'product' => $product->id,
            'seller' => $seller->id,
            'buyer' => $request->user()->id === $seller->id ? $buyer->id : null,
        ]));
    }

    /**
     * JSON endpoint the chat page polls every few seconds so new messages
     * show up without a full page reload — a simple, dependency-free
     * stand-in for a true websocket-based real-time connection (see the
     * README for how to upgrade this to Laravel Reverb/Pusher + Echo).
     */
    public function messages(Request $request, Product $product, User $seller)
    {
        $buyer = $this->resolveBuyer($request, $seller);
        $currentUserId = $request->user()->id;

        $payload = $this->threadQuery($product, $buyer, $seller)
            ->get()
            ->map(fn (Chat $chat) => [
                'id' => $chat->id,
                'message' => $chat->message,
                'sender_name' => optional($chat->sender)->name ?? $chat->buyer->name,
                'is_mine' => $chat->sender_id === $currentUserId,
                'created_at' => $chat->created_at->format('M j, g:i A'),
            ]);

        return response()->json($payload);
    }

    /** Every message that belongs to this exact (product, buyer, seller) thread, oldest first. */
    private function threadQuery(Product $product, User $buyer, User $seller)
    {
        return Chat::with(['buyer', 'seller', 'sender'])
            ->where('product_id', $product->id)
            ->where('buyer_id', $buyer->id)
            ->where('seller_id', $seller->id)
            ->orderBy('created_at');
    }

    /**
     * Work out which user is the "buyer" side of this thread.
     * - Seller viewing their inbox: pass ?buyer=ID to pick the thread.
     * - Buyer viewing/starting a chat: they are the buyer themselves.
     */
    private function resolveBuyer(Request $request, User $seller): User
    {
        $user = $request->user();

        if ($user->id === $seller->id) {
            $buyerId = $request->query('buyer');

            abort_if(! $buyerId, 400, 'A buyer must be specified when a seller opens a chat thread.');

            return User::findOrFail($buyerId);
        }

        return $user;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SavedCard;
use App\Support\CardHelper;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Checkout page: order summary + quantity + shipping details +
     * payment method. This is the real "Buy Now" destination — the
     * button on the product page links here rather than firing an
     * order immediately.
     */
    public function checkout(Request $request, Product $product)
    {
        abort_if($request->user()->id === $product->user_id, 403, "You can't buy your own product.");

        $product->load(['images', 'seller']);

        return view('orders.checkout', [
            'product' => $product,
            'paymentMethods' => Order::PAYMENT_METHODS,
            'savedCards' => $request->user()->savedCards,
        ]);
    }

    /**
     * Submits the checkout form and creates a pending order. Card number
     * and CVV are validated but never persisted — only a display-safe
     * brand + last 4 digits are derived and stored (see CardHelper).
     */
    public function store(Request $request, Product $product)
    {
        abort_if($request->user()->id === $product->user_id, 403, "You can't buy your own product.");

        $needsNewCard = $request->input('payment_method') === 'card' && ! $request->filled('saved_card_id');

        $validated = $request->validate(array_merge([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:' . implode(',', array_keys(Order::PAYMENT_METHODS))],
            'saved_card_id' => ['nullable', 'exists:saved_cards,id'],
            'save_card' => ['nullable', 'boolean'],
        ], $needsNewCard ? [
            'card_number' => ['required', 'string'],
            'card_expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvv' => ['required', 'digits_between:3,4'],
        ] : [
            'card_number' => ['nullable'],
            'card_expiry' => ['nullable'],
            'card_cvv' => ['nullable'],
        ]), [
            'shipping_phone.regex' => 'Please enter a valid phone number (digits, spaces, +, -, and () only).',
        ]);

        $cardInfo = $this->resolveCardInfo($request, $validated);

        $order = Order::create([
            'product_id' => $product->id,
            'buyer_id' => $request->user()->id,
            'seller_id' => $product->user_id,
            'quantity' => $validated['quantity'],
            'total_price' => $product->price * $validated['quantity'],
            'status' => Order::STATUS_PENDING,
            'shipping_name' => $validated['shipping_name'],
            'shipping_phone' => $validated['shipping_phone'],
            'shipping_address' => $validated['shipping_address'],
            'payment_method' => $validated['payment_method'],
            'card_brand' => $cardInfo['brand'],
            'card_last4' => $cardInfo['last4'],
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('status', 'Order placed! The seller will confirm it shortly.');
    }

    /** A single order's detail/receipt page — visible to its buyer or seller. */
    public function show(Order $order)
    {
        $this->authorizeParty($order);

        $order->load(['product.images', 'buyer', 'seller']);

        return view('orders.show', compact('order'));
    }

    /**
     * Seller updates an order's status (confirm / complete / cancel).
     * Buyers may only cancel their own still-pending orders.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::STATUSES)],
        ]);

        $user = $request->user();

        if ($user->id === $order->seller_id) {
            // Sellers can move an order through any valid status.
        } elseif ($user->id === $order->buyer_id && $validated['status'] === Order::STATUS_CANCELLED) {
            abort_if($order->status !== Order::STATUS_PENDING, 403, 'This order can no longer be cancelled.');
        } else {
            abort(403, 'You cannot update this order.');
        }

        $order->update(['status' => $validated['status']]);

        return back()->with('status', 'Order updated.');
    }

    /** Only the buyer or seller involved in this order may view/act on it. */
    private function authorizeParty(Order $order): void
    {
        $userId = request()->user()->id;

        abort_if($userId !== $order->buyer_id && $userId !== $order->seller_id, 403);
    }

    /** Resolves card display info from a saved card OR a freshly typed number — never stores the raw number. */
    private function resolveCardInfo(Request $request, array $validated): array
    {
        if ($validated['payment_method'] !== Order::PAYMENT_CARD) {
            return ['brand' => null, 'last4' => null];
        }

        if (! empty($validated['saved_card_id'])) {
            $card = SavedCard::where('id', $validated['saved_card_id'])->where('user_id', $request->user()->id)->firstOrFail();

            return ['brand' => $card->brand, 'last4' => $card->last4];
        }

        $info = CardHelper::deriveDisplayInfo($validated['card_number']);

        if ($request->boolean('save_card')) {
            [$month, $year] = explode('/', $validated['card_expiry']);

            SavedCard::create([
                'user_id' => $request->user()->id,
                'brand' => $info['brand'],
                'last4' => $info['last4'],
                'expiry_month' => (int) $month,
                'expiry_year' => 2000 + (int) $year,
            ]);
        }

        return $info;
    }
}

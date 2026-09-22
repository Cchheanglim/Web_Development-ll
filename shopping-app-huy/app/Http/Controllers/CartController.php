<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\SavedCard;
use App\Support\CardHelper;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /** View the cart, items grouped by seller (each seller becomes its own order at checkout). */
    public function index(Request $request)
    {
        $items = $request->user()->cartItems()->with(['product.images', 'product.seller'])->get();
        $groupedBySeller = $items->groupBy(fn (CartItem $item) => $item->product->seller->name);

        return view('cart.index', [
            'groupedBySeller' => $groupedBySeller,
            'total' => $items->sum(fn (CartItem $item) => $item->subtotal),
        ]);
    }

    /** Add a product to the cart (or bump its quantity if already in there). */
    public function add(Request $request, Product $product)
    {
        abort_if($request->user()->id === $product->user_id, 403, "You can't buy your own product.");

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);
        $quantity = $validated['quantity'] ?? 1;

        $existing = CartItem::where('user_id', $request->user()->id)->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->update(['quantity' => min(99, $existing->quantity + $quantity)]);
        } else {
            CartItem::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('status', 'Added to cart.');
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== $request->user()->id, 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back();
    }

    public function remove(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== $request->user()->id, 403);

        $cartItem->delete();

        return back()->with('status', 'Removed from cart.');
    }

    /** Checkout page for the whole cart: one shipping/payment form, split into one order per seller's items. */
    public function checkout(Request $request)
    {
        $items = $request->user()->cartItems()->with(['product.images', 'product.seller'])->get();

        abort_if($items->isEmpty(), 400, 'Your cart is empty.');

        return view('cart.checkout', [
            'items' => $items,
            'total' => $items->sum(fn (CartItem $item) => $item->subtotal),
            'paymentMethods' => Order::PAYMENT_METHODS,
            'savedCards' => $request->user()->savedCards,
        ]);
    }

    /**
     * Places the cart as one Order per cart item (so each seller gets
     * their own order to confirm), then empties the cart. Card number
     * and CVV are validated but never persisted — see CardHelper.
     */
    public function placeOrder(Request $request)
    {
        $user = $request->user();
        $items = $user->cartItems()->with('product')->get();
        abort_if($items->isEmpty(), 400, 'Your cart is empty.');

        $needsNewCard = $request->input('payment_method') === 'card' && ! $request->filled('saved_card_id');

        $validated = $request->validate(array_merge([
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

        foreach ($items as $item) {
            Order::create([
                'product_id' => $item->product_id,
                'buyer_id' => $user->id,
                'seller_id' => $item->product->user_id,
                'quantity' => $item->quantity,
                'total_price' => $item->product->price * $item->quantity,
                'status' => Order::STATUS_PENDING,
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'card_brand' => $cardInfo['brand'],
                'card_last4' => $cardInfo['last4'],
            ]);
        }

        $user->cartItems()->delete();

        return redirect()->route('dashboard')->with('status', 'Order placed! Each seller will confirm their part separately.');
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

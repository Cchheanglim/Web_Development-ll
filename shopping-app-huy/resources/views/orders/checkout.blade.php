@extends('layouts.app')
@section('title', 'Checkout — ' . $product->title)

@section('content')
    @php
        $image = $product->images->first();
    @endphp

    <div class="flex items-center gap-2 text-xs text-gray-400 mt-6 mb-4">
        <a href="{{ route('products.show', $product) }}" class="hover:text-orange-600">&larr; Back to listing</a>
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

    <form action="{{ route('orders.store', $product) }}" method="POST" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start">
            {{-- ===================== Left: item, shipping, payment ===================== --}}
            <div class="space-y-6">
                {{-- Item --}}
                <div class="bg-white border rounded-lg p-5">
                    <h2 class="font-bold text-gray-900 mb-4">Item</h2>
                    <div class="flex gap-4">
                        @if($image)
                            <img src="{{ $image->url }}" class="w-20 h-20 object-cover rounded-md border">
                        @else
                            <div class="w-20 h-20 bg-gray-100 rounded-md border flex items-center justify-center text-gray-300">
                                @include('partials.icon', ['name' => 'photo', 'class' => 'w-8 h-8'])
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $product->title }}</p>
                            <p class="text-sm text-gray-500">${{ number_format($product->price, 2) }} each</p>
                            <p class="text-xs text-gray-400">Sold by {{ $product->seller->name }}</p>
                        </div>
                        <div class="flex items-center gap-2 h-fit">
                            <button type="button" id="qty-minus" class="w-8 h-8 border rounded-md flex items-center justify-center hover:bg-gray-50">
                                @include('partials.icon', ['name' => 'minus', 'class' => 'w-4 h-4'])
                            </button>
                            <input type="number" name="quantity" id="qty-input" value="1" min="1" max="99"
                                   data-price="{{ $product->price }}"
                                   class="w-14 border rounded-md text-center py-1.5 text-sm">
                            <button type="button" id="qty-plus" class="w-8 h-8 border rounded-md flex items-center justify-center hover:bg-gray-50">
                                @include('partials.icon', ['name' => 'plus', 'class' => 'w-4 h-4'])
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Shipping details --}}
                <div class="bg-white border rounded-lg p-5">
                    <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        @include('partials.icon', ['name' => 'truck', 'class' => 'w-5 h-5 text-orange-600'])
                        Shipping details
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Full name</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required
                                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Phone number</label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}" required placeholder="e.g. 012 345 678"
                                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Delivery / pickup address</label>
                            <textarea name="shipping_address" rows="3" required
                                      class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('shipping_address') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment method --}}
                @include('partials.payment-fields')
            </div>

            {{-- ===================== Right: order summary ===================== --}}
            <div class="bg-white border rounded-lg p-5 lg:sticky lg:top-24">
                <h2 class="font-bold text-gray-900 mb-4">Order summary</h2>
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Unit price</span>
                    <span>${{ number_format($product->price, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Quantity</span>
                    <span id="summary-qty">1</span>
                </div>
                <div class="flex justify-between text-lg font-bold text-gray-900 border-t pt-3 mt-3">
                    <span>Total</span>
                    <span class="text-orange-600" id="summary-total">${{ number_format($product->price, 2) }}</span>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-md font-semibold hover:bg-red-700 mt-5 flex items-center justify-center gap-2">
                    @include('partials.icon', ['name' => 'cart', 'class' => 'w-5 h-5'])
                    Place order
                </button>
                <p class="text-[11px] text-gray-400 text-center mt-2">By placing this order you're sending a request the seller must confirm.</p>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const qtyInput = document.getElementById('qty-input');
    const summaryQty = document.getElementById('summary-qty');
    const summaryTotal = document.getElementById('summary-total');
    const price = parseFloat(qtyInput.dataset.price);

    function refreshTotals() {
        let qty = parseInt(qtyInput.value, 10);
        if (isNaN(qty) || qty < 1) qty = 1;
        if (qty > 99) qty = 99;
        qtyInput.value = qty;
        summaryQty.textContent = qty;
        summaryTotal.textContent = '$' + (price * qty).toFixed(2);
    }

    document.getElementById('qty-minus').addEventListener('click', () => {
        qtyInput.value = Math.max(1, parseInt(qtyInput.value || 1, 10) - 1);
        refreshTotals();
    });
    document.getElementById('qty-plus').addEventListener('click', () => {
        qtyInput.value = Math.min(99, parseInt(qtyInput.value || 1, 10) + 1);
        refreshTotals();
    });
    qtyInput.addEventListener('input', refreshTotals);
</script>
@endpush

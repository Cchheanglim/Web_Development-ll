@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
    <div class="flex items-center gap-2 text-xs text-gray-400 mt-6 mb-4">
        <a href="{{ route('cart.index') }}" class="hover:text-orange-600">&larr; Back to cart</a>
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

    <form action="{{ route('cart.placeOrder') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start">
            <div class="space-y-6">
                {{-- Items --}}
                <div class="bg-white border rounded-lg p-5">
                    <h2 class="font-bold text-gray-900 mb-4">Items ({{ $items->count() }})</h2>
                    <div class="divide-y">
                        @foreach($items as $item)
                            @php $image = $item->product->images->first(); @endphp
                            <div class="flex items-center gap-4 py-3">
                                @if($image)
                                    <img src="{{ $image->url }}" class="w-14 h-14 object-cover rounded-md border">
                                @else
                                    <div class="w-14 h-14 bg-gray-100 rounded-md border flex items-center justify-center text-gray-300">
                                        @include('partials.icon', ['name' => 'photo', 'class' => 'w-5 h-5'])
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $item->product->title }}</p>
                                    <p class="text-xs text-gray-400">Qty {{ $item->quantity }} &middot; sold by {{ $item->product->seller->name }}</p>
                                </div>
                                <p class="text-sm font-semibold text-orange-600">${{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        @endforeach
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

                {{-- Payment method (shared partial) --}}
                @include('partials.payment-fields')
            </div>

            {{-- Summary --}}
            <div class="bg-white border rounded-lg p-5 lg:sticky lg:top-24">
                <h2 class="font-bold text-gray-900 mb-4">Order summary</h2>
                <div class="flex justify-between text-lg font-bold text-gray-900 border-t pt-3">
                    <span>Total</span>
                    <span class="text-orange-600">${{ number_format($total, 2) }}</span>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-md font-semibold hover:bg-red-700 mt-5 flex items-center justify-center gap-2">
                    @include('partials.icon', ['name' => 'cart', 'class' => 'w-5 h-5'])
                    Place order
                </button>
                <p class="text-[11px] text-gray-400 text-center mt-2">This splits into one order per seller, which they'll confirm separately.</p>
            </div>
        </div>
    </form>
@endsection

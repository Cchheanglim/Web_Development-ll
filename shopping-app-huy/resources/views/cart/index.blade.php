@extends('layouts.app')
@section('title', 'My cart')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 mb-6 mt-6 flex items-center gap-2">
        @include('partials.icon', ['name' => 'cart', 'class' => 'w-6 h-6'])
        My cart
    </h1>

    @if($groupedBySeller->isEmpty())
        <div class="bg-white border rounded-lg p-10 text-center">
            <p class="text-gray-500 mb-4">Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-orange-600 text-white px-5 py-2.5 rounded-md hover:bg-orange-700 font-semibold">
                Browse products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8 items-start">
            {{-- Items, grouped by seller since each seller becomes its own order --}}
            <div class="space-y-6">
                @foreach($groupedBySeller as $sellerName => $items)
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 text-sm font-semibold text-gray-700 border-b">
                            Sold by {{ $sellerName }}
                        </div>
                        <div class="divide-y">
                            @foreach($items as $item)
                                @php $image = $item->product->images->first(); @endphp
                                <div class="flex items-center gap-4 p-4">
                                    @if($image)
                                        <img src="{{ $image->url }}" class="w-16 h-16 object-cover rounded-md border">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-md border flex items-center justify-center text-gray-300">
                                            @include('partials.icon', ['name' => 'photo', 'class' => 'w-6 h-6'])
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('products.show', $item->product) }}" class="font-medium text-sm text-gray-800 hover:text-orange-600 truncate block">
                                            {{ $item->product->title }}
                                        </a>
                                        <p class="text-xs text-gray-400">${{ number_format($item->product->price, 2) }} each</p>
                                    </div>

                                    <form action="{{ route('cart.updateQuantity', $item) }}" method="POST" class="flex items-center gap-1">
                                        @csrf @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99"
                                               onchange="this.form.submit()"
                                               class="w-14 border rounded-md text-center py-1 text-sm">
                                    </form>

                                    <p class="w-20 text-right font-semibold text-orange-600 text-sm">${{ number_format($item->subtotal, 2) }}</p>

                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500">
                                            @include('partials.icon', ['name' => 'x-mark', 'class' => 'w-4 h-4'])
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="bg-white border rounded-lg p-5 lg:sticky lg:top-24">
                <h2 class="font-bold text-gray-900 mb-4">Order summary</h2>
                <div class="flex justify-between text-lg font-bold text-gray-900 border-b pb-3 mb-3">
                    <span>Total</span>
                    <span class="text-orange-600">${{ number_format($total, 2) }}</span>
                </div>
                <p class="text-xs text-gray-400 mb-4">Items from different sellers are placed as separate orders, so each seller can confirm their own part.</p>
                <a href="{{ route('cart.checkout') }}"
                   class="flex items-center justify-center gap-2 w-full bg-red-600 text-white py-3 rounded-md font-semibold hover:bg-red-700">
                    @include('partials.icon', ['name' => 'cart', 'class' => 'w-5 h-5'])
                    Proceed to checkout
                </a>
            </div>
        </div>
    @endif
@endsection

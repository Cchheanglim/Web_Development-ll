@extends('layouts.app')
@section('title', 'My dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6 mt-6">My dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- My orders --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">My orders ({{ $orders->count() }})</h2>
            <div class="bg-white border rounded-lg divide-y">
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-100 text-amber-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                @forelse($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="block p-4 hover:bg-orange-50">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-sm truncate max-w-[60%]">{{ $order->product->title }}</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full capitalize {{ $statusColors[$order->status] }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Sold by {{ $order->seller->name }} &middot; Qty {{ $order->quantity }} &middot; ${{ number_format($order->total_price, 2) }}</p>
                    </a>
                @empty
                    <p class="p-4 text-gray-500 text-sm">
                        No orders yet. <a href="{{ route('products.index') }}" class="text-orange-600 hover:underline font-medium">Browse products</a> to place one.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- My conversations --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">My conversations</h2>
            <div class="bg-white border rounded-lg divide-y">
                @forelse($threads as $thread)
                    <a href="{{ route('chat.show', [$thread->product, $thread->seller]) }}"
                       class="block p-4 hover:bg-orange-50">
                        <p class="font-medium text-sm">{{ $thread->seller->name }}</p>
                        <p class="text-xs text-gray-500 truncate">about "{{ $thread->product->title }}"</p>
                    </a>
                @empty
                    <p class="p-4 text-gray-500 text-sm">
                        No conversations yet. <a href="{{ route('products.index') }}" class="text-orange-600 hover:underline font-medium">Browse products</a> to start one.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- My favorites --}}
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">My favorites ({{ $favorites->count() }})</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            @forelse($favorites as $product)
                @include('products._card', ['product' => $product])
            @empty
                <p class="text-gray-500 text-sm col-span-full">
                    No saved items yet. Tap the heart icon on any product to save it here.
                </p>
            @endforelse
        </div>
    </div>
@endsection

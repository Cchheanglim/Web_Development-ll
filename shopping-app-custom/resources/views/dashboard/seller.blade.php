@extends('layouts.app')
@section('title', 'Seller dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6 mt-6">Seller dashboard</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- My products --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">My products ({{ $products->count() }})</h2>
                <a href="{{ route('products.create') }}" class="bg-orange-600 text-white px-3 py-1.5 rounded-md text-sm hover:bg-orange-700 font-semibold">
                    + Post
                </a>
            </div>

            <div class="bg-white border rounded-lg divide-y">
                @forelse($products as $product)
                    <div class="flex items-center justify-between p-4">
                        <div>
                            <a href="{{ route('products.show', $product) }}" class="font-medium hover:text-orange-600 text-sm">
                                {{ $product->title }}
                            </a>
                            <p class="text-xs text-gray-500">${{ number_format($product->price, 2) }} &middot; {{ ucfirst($product->condition) }}</p>
                        </div>
                        <div class="flex gap-3 text-xs">
                            <a href="{{ route('products.edit', $product) }}" class="text-orange-600 hover:underline font-medium">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete this product?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="p-4 text-gray-500 text-sm">You haven't posted any products yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Orders received --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Orders received ({{ $orders->count() }})</h2>
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
                        <p class="text-xs text-gray-500 mt-1">{{ $order->buyer->name }} &middot; Qty {{ $order->quantity }} &middot; ${{ number_format($order->total_price, 2) }}</p>
                    </a>
                @empty
                    <p class="p-4 text-gray-500 text-sm">No orders yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Chat threads --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Buyer messages</h2>
            <div class="bg-white border rounded-lg divide-y">
                @forelse($threads as $thread)
                    <a href="{{ route('chat.show', [$thread->product, auth()->user()]) }}?buyer={{ $thread->buyer_id }}"
                       class="block p-4 hover:bg-orange-50">
                        <p class="font-medium text-sm">{{ $thread->buyer->name }}</p>
                        <p class="text-xs text-gray-500 truncate">about "{{ $thread->product->title }}"</p>
                    </a>
                @empty
                    <p class="p-4 text-gray-500 text-sm">No messages yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

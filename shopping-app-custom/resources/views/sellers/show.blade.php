@extends('layouts.app')
@section('title', $seller->name . ' — MarketHub')

@section('content')
    <div class="bg-white border rounded-lg p-6 mt-6 mb-8 flex flex-col sm:flex-row items-start sm:items-center gap-5">
        <div class="w-16 h-16 rounded-full bg-orange-100 text-orange-700 font-bold text-2xl flex items-center justify-center shrink-0">
            {{ strtoupper(substr($seller->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <h1 class="text-xl font-bold text-gray-900">{{ $seller->name }}</h1>
            <p class="text-xs text-gray-400 mb-1">Seller on MarketHub since {{ $seller->created_at->format('M Y') }}</p>
            @include('partials.stars', ['rating' => $averageRating, 'count' => $reviewCount])
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold text-orange-600">{{ $products->total() }}</p>
            <p class="text-xs text-gray-400">Products listed</p>
        </div>
    </div>

    <h2 class="text-lg font-bold text-gray-900 mb-4">{{ $seller->name }}'s products</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
        @forelse($products as $product)
            @include('products._card', ['product' => $product])
        @empty
            <p class="text-gray-500 col-span-full">This seller hasn't posted any products yet.</p>
        @endforelse
    </div>

    {{ $products->links() }}
@endsection

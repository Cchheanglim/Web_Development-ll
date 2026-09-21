@extends('layouts.app')
@section('title', 'MarketHub — Buy & sell locally')

@section('content')
    {{-- ===================== Hero banner ===================== --}}
    <div class="mt-6 bg-gradient-to-r from-orange-600 to-red-500 rounded-xl px-8 py-12 text-white relative overflow-hidden">
        <div class="relative z-10 max-w-xl">
            <h1 class="text-3xl md:text-4xl font-extrabold leading-tight">Everything you need, from people near you.</h1>
            <p class="mt-3 text-orange-50">Browse thousands of listings and message the seller directly — no middleman, no fees.</p>
            <form action="{{ route('products.index') }}" method="GET" class="mt-6 flex max-w-md bg-white rounded-md overflow-hidden">
                <input type="text" name="q" placeholder="Try 'bike', 'desk', 'headphones'..."
                       class="flex-1 px-4 py-3 text-sm text-gray-800 focus:outline-none">
                <button type="submit" class="bg-gray-900 hover:bg-black text-white px-5 text-sm font-semibold">Search</button>
            </form>
        </div>
        <div class="hidden md:block absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full"></div>
        <div class="hidden md:block absolute right-20 -top-10 w-32 h-32 bg-white/10 rounded-full"></div>
    </div>

    {{-- ===================== Category tiles ===================== --}}
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-7 gap-3 mt-8">
        @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat]) }}"
               class="bg-white border rounded-lg py-4 px-2 text-center hover:border-orange-400 hover:shadow-md transition">
                <p class="text-xs font-medium text-gray-700">{{ $cat }}</p>
            </a>
        @endforeach
    </div>

    {{-- ===================== Recent listings ===================== --}}
    <div class="flex items-center justify-between mt-10 mb-4">
        <h2 class="text-lg font-bold text-gray-900">Recently listed</h2>
        <a href="{{ route('products.index') }}" class="text-sm text-orange-600 hover:underline font-medium">See all &rarr;</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        @forelse($products as $product)
            @include('products._card', ['product' => $product])
        @empty
            <p class="text-gray-500 col-span-full">No products yet — be the first seller to post one!</p>
        @endforelse
    </div>
@endsection

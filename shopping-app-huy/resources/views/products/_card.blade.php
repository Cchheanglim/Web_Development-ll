{{--
    Reusable product card used on the home page, listing/search page,
    seller storefronts, and "related products" rows.
    Optional $favoriteIds (collection of product IDs the current user has
    saved) enables the heart button's filled/outline state.
--}}
@php
    $firstImage = $product->images->first();
    $isFavorited = isset($favoriteIds) && $favoriteIds->contains($product->id);
@endphp

<div class="group bg-white rounded-lg border border-gray-200 hover:border-orange-400 hover:shadow-lg transition overflow-hidden relative">
    @auth
        <form action="{{ route('favorites.toggle', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
            @csrf
            <button type="submit" class="bg-white/90 hover:bg-white rounded-full p-1.5 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                     fill="{{ $isFavorited ? 'currentColor' : 'none' }}"
                     class="w-4 h-4 {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </button>
        </form>
    @endauth

    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="relative">
            @if($firstImage)
                <img src="{{ $firstImage->url }}" alt="{{ $product->title }}" class="w-full aspect-square object-cover">
            @else
                <div class="w-full aspect-square bg-gray-100 flex items-center justify-center text-gray-300">
                    @include('partials.icon', ['name' => 'photo', 'class' => 'w-10 h-10'])
                </div>
            @endif
            <span class="absolute top-2 left-2 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase {{ $product->condition === 'new' ? 'bg-green-600 text-white' : 'bg-amber-500 text-white' }}">
                {{ $product->condition }}
            </span>
        </div>
        <div class="px-3 pt-3">
            <h3 class="text-sm text-gray-700 leading-snug h-10 overflow-hidden group-hover:text-orange-600">
                {{ $product->title }}
            </h3>
            @include('partials.stars', ['rating' => $product->average_rating, 'count' => $product->review_count, 'size' => 'w-3 h-3'])
            <p class="text-orange-600 font-extrabold text-lg mt-1">${{ number_format($product->price, 2) }}</p>
        </div>
    </a>

    {{-- Outside the product link so the seller link is its own clickable target (no nested <a> tags) --}}
    <div class="flex items-center justify-between px-3 pb-3 pt-1 text-[11px] text-gray-400">
        <span>{{ $product->category }}</span>
        <a href="{{ route('sellers.show', $product->seller) }}" class="truncate max-w-[45%] hover:text-orange-600 hover:underline">
            {{ $product->seller->name }}
        </a>
    </div>
</div>

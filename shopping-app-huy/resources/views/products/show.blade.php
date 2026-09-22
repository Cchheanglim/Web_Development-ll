@extends('layouts.app')
@section('title', $product->title . ' — PsaOnline')

@section('content')
    <div class="flex items-center gap-2 text-xs text-gray-400 mt-6 mb-4">
        <a href="{{ route('home') }}" class="hover:text-orange-600">Home</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category' => $product->category]) }}" class="hover:text-orange-600">{{ $product->category }}</a>
        <span>/</span>
        <span class="text-gray-600 truncate">{{ $product->title }}</span>
    </div>

    @php
        $imageUrls = $product->images->map(fn ($img) => $img->url)->values();
        if ($imageUrls->isEmpty()) {
            $imageUrls = collect([null]); // signals "no real photo" to the template below
        }
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-8">
        {{-- ===================== Gallery ===================== --}}
        <div class="bg-white border rounded-lg p-4">
            <div class="relative group" id="gallery-main-wrap">
                @if($imageUrls[0])
                    <img src="{{ $imageUrls[0] }}" id="main-image" class="w-full aspect-square object-cover rounded-md cursor-zoom-in" alt="{{ $product->title }}">
                @else
                    <div id="main-image-empty" class="w-full aspect-square bg-gray-100 rounded-md flex flex-col items-center justify-center text-gray-300 gap-2">
                        @include('partials.icon', ['name' => 'photo', 'class' => 'w-14 h-14'])
                        <span class="text-xs text-gray-400">No photo available</span>
                    </div>
                @endif

                @if($imageUrls[0])
                    <button type="button" id="zoom-btn"
                            class="absolute bottom-3 right-3 bg-white/90 hover:bg-white text-gray-700 rounded-full p-2 shadow">
                        @include('partials.icon', ['name' => 'zoom', 'class' => 'w-5 h-5'])
                    </button>
                @endif

                @if($imageUrls->count() > 1)
                    <button type="button" id="gallery-prev"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-700 rounded-full p-2 shadow opacity-0 group-hover:opacity-100 transition">
                        @include('partials.icon', ['name' => 'chevron-left', 'class' => 'w-5 h-5'])
                    </button>
                    <button type="button" id="gallery-next"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-700 rounded-full p-2 shadow opacity-0 group-hover:opacity-100 transition">
                        @include('partials.icon', ['name' => 'chevron-right', 'class' => 'w-5 h-5'])
                    </button>
                @endif
            </div>

            @if($imageUrls->count() > 1)
                <div class="grid grid-cols-6 gap-2 mt-3" id="thumb-row">
                    @foreach($imageUrls as $i => $url)
                        <button type="button" data-index="{{ $i }}"
                                class="thumb-btn border-2 rounded-md overflow-hidden {{ $i === 0 ? 'border-orange-500' : 'border-transparent hover:border-orange-300' }}">
                            <img src="{{ $url }}" class="w-full aspect-square object-cover">
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="mt-6 border-t pt-4">
                <h2 class="font-bold text-gray-900 mb-2">Description</h2>
                <p class="text-gray-600 text-sm whitespace-pre-line">{{ $product->description }}</p>
            </div>

            @if($product->specs->isNotEmpty())
                <div class="mt-6 border-t pt-4">
                    <h2 class="font-bold text-gray-900 mb-3">Specifications</h2>
                    <div class="divide-y border rounded-md overflow-hidden">
                        @foreach($product->specs as $spec)
                            <div class="flex text-sm">
                                <div class="w-1/3 bg-gray-50 px-4 py-2 text-gray-500">{{ $spec->label }}</div>
                                <div class="flex-1 px-4 py-2 text-gray-800">{{ $spec->value }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ===================== Buy box ===================== --}}
        <div>
            <div class="bg-white border rounded-lg p-5 sticky top-24">
                <div class="flex items-start justify-between">
                    <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full uppercase mb-2 {{ $product->condition === 'new' ? 'bg-green-600 text-white' : 'bg-amber-500 text-white' }}">
                        {{ $product->condition }}
                    </span>
                    @auth
                        <form action="{{ route('favorites.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 text-xs {{ $isFavorited ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                     fill="{{ $isFavorited ? 'currentColor' : 'none' }}" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                                {{ $isFavorited ? 'Saved' : 'Save' }}
                            </button>
                        </form>
                    @endauth
                </div>
                <h1 class="text-xl font-bold text-gray-900 leading-snug">{{ $product->title }}</h1>
                <div class="mt-1">
                    @include('partials.stars', ['rating' => $product->average_rating, 'count' => $product->review_count])
                </div>
                <p class="text-3xl text-orange-600 font-extrabold mt-3">${{ number_format($product->price, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">Category: {{ $product->category }}</p>

                <a href="{{ route('sellers.show', $product->seller) }}" class="mt-5 flex items-center gap-3 border-t pt-4 hover:bg-gray-50 -mx-5 px-5 py-2">
                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-700 font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($product->seller->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $product->seller->name }}</p>
                        <p class="text-xs text-gray-400">View seller's storefront &rarr;</p>
                    </div>
                </a>

                <div class="mt-5 space-y-2">
                    @auth
                        @if(auth()->id() !== $product->user_id)
                            <a href="{{ route('orders.checkout', $product) }}"
                               class="flex items-center justify-center gap-2 bg-red-600 text-white py-3 rounded-md font-semibold hover:bg-red-700">
                                @include('partials.icon', ['name' => 'cart', 'class' => 'w-5 h-5'])
                                Buy now
                            </a>

                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 border border-red-600 text-red-600 py-3 rounded-md font-semibold hover:bg-red-50">
                                    @include('partials.icon', ['name' => 'cart', 'class' => 'w-5 h-5'])
                                    Add to cart
                                </button>
                            </form>

                            <a href="{{ route('chat.show', [$product, $product->seller]) }}"
                               class="flex items-center justify-center gap-2 border border-orange-600 text-orange-600 py-3 rounded-md font-semibold hover:bg-orange-50">
                                @include('partials.icon', ['name' => 'chat', 'class' => 'w-5 h-5'])
                                Chat with seller
                            </a>
                        @endif

                        @if(auth()->id() === $product->user_id)
                            <a href="{{ route('products.edit', $product) }}"
                               class="block text-center border border-orange-600 text-orange-600 py-3 rounded-md font-semibold hover:bg-orange-50">
                                Edit listing
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete this product? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full border border-red-500 text-red-600 py-3 rounded-md font-semibold hover:bg-red-50">
                                    Delete listing
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block text-center bg-orange-600 text-white py-3 rounded-md font-semibold hover:bg-orange-700">
                            Log in to buy or chat with seller
                        </a>
                    @endauth
                </div>

                <div class="mt-5 border-t pt-4 text-xs text-gray-400 space-y-2">
                    <p class="flex items-center gap-2">
                        @include('partials.icon', ['name' => 'cart', 'class' => 'w-4 h-4'])
                        "Buy now" sends a request the seller confirms — no payment is collected here
                    </p>
                    <p class="flex items-center gap-2">
                        @include('partials.icon', ['name' => 'lock', 'class' => 'w-4 h-4'])
                        Chat stays inside the app
                    </p>
                    <p class="flex items-center gap-2">
                        @include('partials.icon', ['name' => 'map-pin', 'class' => 'w-4 h-4'])
                        Meet in a safe, public place
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== Reviews ===================== --}}
    <div class="mt-12 bg-white border rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Reviews</h2>
            @include('partials.stars', ['rating' => $product->average_rating, 'count' => $product->review_count])
        </div>

        @forelse($product->reviews as $review)
            <div class="border-t py-4 first:border-t-0 first:pt-0">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-800">{{ $review->buyer->name }}</p>
                    <span class="text-xs text-gray-400">{{ $review->created_at->format('M j, Y') }}</span>
                </div>
                @include('partials.stars', ['rating' => $review->rating, 'count' => 1, 'size' => 'w-3 h-3'])
                @if($review->comment)
                    <p class="text-sm text-gray-600 mt-2">{{ $review->comment }}</p>
                @endif
                @if($review->images->isNotEmpty())
                    <div class="flex gap-2 mt-2">
                        @foreach($review->images as $img)
                            <a href="{{ $img->url }}" target="_blank" rel="noopener">
                                <img src="{{ $img->url }}" class="w-16 h-16 object-cover rounded-md border hover:opacity-80">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-400">No reviews yet — reviews appear here once buyers complete an order and rate it.</p>
        @endforelse
    </div>

    {{-- ===================== Related products ===================== --}}
    @if($related->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-lg font-bold text-gray-900 mb-4">You might also like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($related as $item)
                    @include('products._card', ['product' => $item])
                @endforeach
            </div>
        </div>
    @endif

    {{-- ===================== Zoom lightbox ===================== --}}
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-6">
        <button type="button" id="lightbox-close" class="absolute top-5 right-5 text-white/80 hover:text-white">
            @include('partials.icon', ['name' => 'x-mark', 'class' => 'w-8 h-8'])
        </button>

        @if($imageUrls->count() > 1)
            <button type="button" id="lightbox-prev" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white">
                @include('partials.icon', ['name' => 'chevron-left', 'class' => 'w-10 h-10'])
            </button>
            <button type="button" id="lightbox-next" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white">
                @include('partials.icon', ['name' => 'chevron-right', 'class' => 'w-10 h-10'])
            </button>
        @endif

        <img id="lightbox-image" src="" class="max-h-[85vh] max-w-[85vw] object-contain rounded-md">
    </div>
@endsection

@push('scripts')
<script>
    const images = @json($imageUrls);
    let currentIndex = 0;

    const mainImage = document.getElementById('main-image');
    const thumbButtons = document.querySelectorAll('.thumb-btn');
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');

    function showImage(index) {
        currentIndex = (index + images.length) % images.length;
        if (mainImage) mainImage.src = images[currentIndex];
        thumbButtons.forEach((btn, i) => {
            btn.classList.toggle('border-orange-500', i === currentIndex);
            btn.classList.toggle('border-transparent', i !== currentIndex);
        });
    }

    thumbButtons.forEach((btn) => {
        btn.addEventListener('click', () => showImage(parseInt(btn.dataset.index, 10)));
    });

    document.getElementById('gallery-prev')?.addEventListener('click', () => showImage(currentIndex - 1));
    document.getElementById('gallery-next')?.addEventListener('click', () => showImage(currentIndex + 1));

    function openLightbox() {
        lightboxImage.src = images[currentIndex];
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
    }
    function closeLightbox() {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
    }

    document.getElementById('zoom-btn')?.addEventListener('click', openLightbox);
    mainImage?.addEventListener('click', openLightbox);
    document.getElementById('lightbox-close').addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });

    document.getElementById('lightbox-prev')?.addEventListener('click', () => { showImage(currentIndex - 1); lightboxImage.src = images[currentIndex]; });
    document.getElementById('lightbox-next')?.addEventListener('click', () => { showImage(currentIndex + 1); lightboxImage.src = images[currentIndex]; });

    document.addEventListener('keydown', (e) => {
        if (lightbox.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') { showImage(currentIndex - 1); lightboxImage.src = images[currentIndex]; }
        if (e.key === 'ArrowRight') { showImage(currentIndex + 1); lightboxImage.src = images[currentIndex]; }
    });
</script>
@endpush

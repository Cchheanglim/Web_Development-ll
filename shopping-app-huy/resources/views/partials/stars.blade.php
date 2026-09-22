{{--
    Star rating display. Usage:
        @include('partials.stars', ['rating' => $product->average_rating, 'count' => $product->review_count])
    $rating is a 0-5 float or null (no reviews yet); $count is the review total.
--}}
@php
    $rating = $rating ?? 0;
    $size = $size ?? 'w-4 h-4';
@endphp

<div class="flex items-center gap-1">
    <div class="flex">
        @for($i = 1; $i <= 5; $i++)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                 class="{{ $size }} {{ $i <= round($rating) ? 'text-amber-400' : 'text-gray-200' }}">
                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.454 1.405 1.02L10 15.591l4.069 2.485c.713.435 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
            </svg>
        @endfor
    </div>
    @if(($count ?? 0) > 0)
        <span class="text-xs text-gray-500">{{ $rating }} ({{ $count }})</span>
    @else
        <span class="text-xs text-gray-400">No reviews yet</span>
    @endif
</div>

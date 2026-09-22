@extends('layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')
    @php
        $statusColors = [
            'pending' => 'bg-amber-100 text-amber-700',
            'confirmed' => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
        ];
        $image = $order->product->images->first();

        // Step index for the tracker below: pending=0, confirmed=1, completed=2. Cancelled is its own state.
        $steps = ['pending' => 0, 'confirmed' => 1, 'completed' => 2];
        $currentStep = $steps[$order->status] ?? -1;
    @endphp

    <div class="max-w-2xl mx-auto mt-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-orange-600 hover:underline">&larr; Back to dashboard</a>

        <div class="bg-white border rounded-lg p-6 mt-4">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                    <p class="text-xs text-gray-400">Placed {{ $order->created_at->format('M j, Y g:i A') }}</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full capitalize {{ $statusColors[$order->status] }}">
                    {{ $order->status }}
                </span>
            </div>

            {{-- ===================== Status tracker ===================== --}}
            @if($order->status !== 'cancelled')
                <div class="flex items-center mb-6">
                    @foreach(['Pending' => 0, 'Confirmed' => 1, 'Completed' => 2] as $label => $index)
                        <div class="flex items-center {{ $index < 2 ? 'flex-1' : '' }}">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $index <= $currentStep ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                                    @include('partials.icon', ['name' => 'check-circle', 'class' => 'w-5 h-5'])
                                </div>
                                <p class="text-[10px] mt-1 {{ $index <= $currentStep ? 'text-orange-600 font-semibold' : 'text-gray-400' }}">{{ $label }}</p>
                            </div>
                            @if($index < 2)
                                <div class="flex-1 h-0.5 mx-2 {{ $index < $currentStep ? 'bg-orange-600' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md px-4 py-3">
                    This order was cancelled.
                </div>
            @endif

            <div class="flex gap-4 border-t border-b py-4">
                @if($image)
                    <img src="{{ $image->url }}" class="w-20 h-20 object-cover rounded-md border">
                @else
                    <div class="w-20 h-20 bg-gray-100 rounded-md border flex items-center justify-center text-gray-300">
                        @include('partials.icon', ['name' => 'photo', 'class' => 'w-8 h-8'])
                    </div>
                @endif
                <div class="flex-1">
                    <a href="{{ route('products.show', $order->product) }}" class="font-semibold text-gray-900 hover:text-orange-600">
                        {{ $order->product->title }}
                    </a>
                    <p class="text-sm text-gray-500 mt-1">Qty: {{ $order->quantity }} &times; ${{ number_format($order->product->price, 2) }}</p>
                </div>
                <p class="text-lg font-bold text-orange-600">${{ number_format($order->total_price, 2) }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Buyer</p>
                    <p class="font-medium">{{ $order->buyer->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Seller</p>
                    <p class="font-medium">{{ $order->seller->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Payment method</p>
                    <p class="font-medium">
                        {{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? $order->payment_method }}
                        @if($order->card_last4)
                            <span class="text-gray-400 font-normal">({{ $order->card_brand }} &bull;&bull;&bull;&bull; {{ $order->card_last4 }})</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Contact phone</p>
                    <p class="font-medium">{{ $order->shipping_phone }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs">Shipping to</p>
                    <p class="font-medium">{{ $order->shipping_name }} &mdash; {{ $order->shipping_address }}</p>
                </div>
            </div>

            {{-- ===================== Status actions ===================== --}}
            <div class="mt-6 border-t pt-4 flex flex-wrap gap-2">
                @if(auth()->id() === $order->seller_id)
                    @if($order->status === 'pending')
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button class="bg-blue-600 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-700">Confirm order</button>
                        </form>
                    @endif
                    @if($order->status === 'confirmed')
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button class="bg-green-600 text-white text-sm px-4 py-2 rounded-md hover:bg-green-700">Mark completed</button>
                        </form>
                    @endif
                    @if(in_array($order->status, ['pending', 'confirmed']))
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button class="border border-red-500 text-red-600 text-sm px-4 py-2 rounded-md hover:bg-red-50">Cancel order</button>
                        </form>
                    @endif
                @elseif(auth()->id() === $order->buyer_id && $order->status === 'pending')
                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button class="border border-red-500 text-red-600 text-sm px-4 py-2 rounded-md hover:bg-red-50">Cancel order</button>
                    </form>
                @endif

                <a href="{{ route('chat.show', [$order->product, $order->seller]) }}?buyer={{ $order->buyer_id }}"
                   class="flex items-center gap-2 border border-orange-600 text-orange-600 text-sm px-4 py-2 rounded-md hover:bg-orange-50">
                    @include('partials.icon', ['name' => 'chat', 'class' => 'w-4 h-4'])
                    Message about this order
                </a>
            </div>

            {{-- ===================== Leave a review ===================== --}}
            @if(auth()->id() === $order->buyer_id && $order->status === 'completed')
                @if($order->review)
                    <div class="mt-6 border-t pt-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Your review</h3>
                        @include('partials.stars', ['rating' => $order->review->rating, 'count' => 1])
                        @if($order->review->comment)
                            <p class="text-sm text-gray-600 mt-2">{{ $order->review->comment }}</p>
                        @endif
                        @if($order->review->images->isNotEmpty())
                            <div class="flex gap-2 mt-3">
                                @foreach($order->review->images as $img)
                                    <a href="{{ $img->url }}" target="_blank" rel="noopener">
                                        <img src="{{ $img->url }}" class="w-16 h-16 object-cover rounded-md border">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-6 border-t pt-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Leave a review</h3>
                        <form action="{{ route('reviews.store', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex items-center gap-1 mb-3" id="star-picker">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star-btn text-2xl text-gray-300 hover:text-amber-400" data-value="{{ $i }}">★</button>
                                @endfor
                                <input type="hidden" name="rating" id="rating-input" value="0" required>
                            </div>
                            <textarea name="comment" rows="3" placeholder="Optional: how was the item and the seller?"
                                      class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                            <div class="mt-3">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Add photos of what you received (optional, up to 5)</label>
                                <input type="file" name="images[]" multiple accept="image/*"
                                       class="w-full border rounded-md px-3 py-2 text-sm bg-white">
                            </div>
                            <button type="submit" class="mt-3 bg-orange-600 text-white text-sm px-4 py-2 rounded-md hover:bg-orange-700">
                                Submit review
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const starButtons = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');

    starButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const value = parseInt(btn.dataset.value, 10);
            ratingInput.value = value;
            starButtons.forEach((b) => {
                b.classList.toggle('text-amber-400', parseInt(b.dataset.value, 10) <= value);
                b.classList.toggle('text-gray-300', parseInt(b.dataset.value, 10) > value);
            });
        });
    });
</script>
@endpush

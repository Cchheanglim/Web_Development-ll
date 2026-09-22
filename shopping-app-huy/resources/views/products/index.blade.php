@extends('layouts.app')
@section('title', (request('category') ? request('category') . ' — ' : '') . 'Products — PsaOnline')

@section('content')
    <div class="flex items-center gap-2 text-xs text-gray-400 mt-6 mb-4">
        <a href="{{ route('home') }}" class="hover:text-orange-600">Home</a>
        <span>/</span>
        <span class="text-gray-600">{{ request('category') ?: 'All products' }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-6">
        {{-- ===================== Sidebar filters ===================== --}}
        <aside class="bg-white border rounded-lg p-4 h-fit lg:sticky lg:top-24">
            <form method="GET" action="{{ route('products.index') }}" class="space-y-5">
                <input type="hidden" name="q" value="{{ request('q') }}">

                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-2">Category</h3>
                    <div class="space-y-1 text-sm">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="category" value="" onchange="this.form.submit()" @checked(!request('category'))>
                            All categories
                        </label>
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-2">
                                <input type="radio" name="category" value="{{ $cat }}" onchange="this.form.submit()" @checked(request('category') === $cat)>
                                {{ $cat }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="text-sm font-bold text-gray-900 mb-2">Condition</h3>
                    <div class="space-y-1 text-sm">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="condition" value="" onchange="this.form.submit()" @checked(!request('condition'))>
                            Any
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="condition" value="new" onchange="this.form.submit()" @checked(request('condition') === 'new')>
                            New
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="condition" value="used" onchange="this.form.submit()" @checked(request('condition') === 'used')>
                            Used
                        </label>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="text-sm font-bold text-gray-900 mb-2">Price range</h3>
                    <div class="flex items-center gap-2">
                        <input type="number" step="0.01" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                               class="w-full border rounded-md px-2 py-1.5 text-sm">
                        <span class="text-gray-400">–</span>
                        <input type="number" step="0.01" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                               class="w-full border rounded-md px-2 py-1.5 text-sm">
                    </div>
                    <button type="submit" class="mt-3 w-full bg-orange-600 text-white text-sm font-semibold py-2 rounded-md hover:bg-orange-700">
                        Apply
                    </button>
                </div>

                <a href="{{ route('products.index') }}" class="block text-center text-xs text-gray-400 hover:text-gray-600">
                    Clear all filters
                </a>
            </form>
        </aside>

        {{-- ===================== Results ===================== --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500">{{ $products->total() }} result{{ $products->total() === 1 ? '' : 's' }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
                @forelse($products as $product)
                    @include('products._card', ['product' => $product])
                @empty
                    <p class="text-gray-500 col-span-full">No products match your filters.</p>
                @endforelse
            </div>

            {{ $products->links() }}
        </div>
    </div>
@endsection

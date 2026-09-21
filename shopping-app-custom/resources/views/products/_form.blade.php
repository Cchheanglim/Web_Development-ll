{{-- Shared fields for products/create and products/edit --}}

<div>
    <label class="block text-sm font-medium mb-1">Title</label>
    <input type="text" name="title" value="{{ old('title', $product->title ?? '') }}" required
           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Description</label>
    <textarea name="description" rows="4" required
              class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Price ($)</label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price ?? '') }}" required
               class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Condition</label>
        <select name="condition" required class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option value="new" @selected(old('condition', $product->condition ?? '') === 'new')>New</option>
            <option value="used" @selected(old('condition', $product->condition ?? '') === 'used')>Used</option>
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Category</label>
    <select name="category" required class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
        @foreach($categories as $cat)
            <option value="{{ $cat }}" @selected(old('category', $product->category ?? '') === $cat)>{{ $cat }}</option>
        @endforeach
    </select>
</div>

@isset($product)
    @if($product->images->isNotEmpty())
        <div>
            <label class="block text-sm font-medium mb-2">Current images (check to remove)</label>
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->images as $image)
                    <label class="relative block cursor-pointer">
                        <img src="{{ $image->url }}" class="rounded-md border h-20 w-full object-cover">
                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}"
                               class="absolute top-1 right-1 h-4 w-4">
                    </label>
                @endforeach
            </div>
        </div>
    @endif
@endisset

<div>
    <label class="block text-sm font-medium mb-1">
        {{ isset($product) ? 'Add more images' : 'Product images' }}
    </label>
    <input type="file" name="images[]" multiple accept="image/*"
           class="w-full border rounded-md px-3 py-2 bg-white">
    <p class="text-xs text-gray-400 mt-1">You can select multiple images. Max 4MB each.</p>
</div>

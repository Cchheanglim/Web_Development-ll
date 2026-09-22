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

{{-- ===================== Specifications (optional) ===================== --}}
<div>
    <label class="block text-sm font-medium mb-1">Specifications (optional)</label>
    <p class="text-xs text-gray-400 mb-2">Add details like "Material: Cotton" or "Weight: 250g" — shown as a table on the product page.</p>

    @php
        $existingSpecs = old('spec_label')
            ? collect(old('spec_label'))->map(fn($l, $i) => ['label' => $l, 'value' => old('spec_value')[$i] ?? ''])
            : (isset($product) ? $product->specs : collect());
    @endphp

    <div id="specs-rows" class="space-y-2">
        @forelse($existingSpecs as $spec)
            <div class="flex gap-2 spec-row">
                <input type="text" name="spec_label[]" value="{{ is_array($spec) ? $spec['label'] : $spec->label }}" placeholder="Label (e.g. Material)"
                       class="w-1/3 border rounded-md px-3 py-2 text-sm">
                <input type="text" name="spec_value[]" value="{{ is_array($spec) ? $spec['value'] : $spec->value }}" placeholder="Value (e.g. Cotton)"
                       class="flex-1 border rounded-md px-3 py-2 text-sm">
                <button type="button" class="remove-spec-row text-red-500 text-sm px-2">✕</button>
            </div>
        @empty
            <div class="flex gap-2 spec-row">
                <input type="text" name="spec_label[]" placeholder="Label (e.g. Material)" class="w-1/3 border rounded-md px-3 py-2 text-sm">
                <input type="text" name="spec_value[]" placeholder="Value (e.g. Cotton)" class="flex-1 border rounded-md px-3 py-2 text-sm">
                <button type="button" class="remove-spec-row text-red-500 text-sm px-2">✕</button>
            </div>
        @endforelse
    </div>

    <button type="button" id="add-spec-row" class="mt-2 text-sm text-orange-600 hover:underline">+ Add another spec</button>
</div>

<script>
    (function () {
        const rows = document.getElementById('specs-rows');
        const addBtn = document.getElementById('add-spec-row');

        function wireRemove(row) {
            row.querySelector('.remove-spec-row').addEventListener('click', () => {
                if (rows.children.length > 1) row.remove();
            });
        }
        rows.querySelectorAll('.spec-row').forEach(wireRemove);

        addBtn.addEventListener('click', () => {
            const row = document.createElement('div');
            row.className = 'flex gap-2 spec-row';
            row.innerHTML = `
                <input type="text" name="spec_label[]" placeholder="Label (e.g. Material)" class="w-1/3 border rounded-md px-3 py-2 text-sm">
                <input type="text" name="spec_value[]" placeholder="Value (e.g. Cotton)" class="flex-1 border rounded-md px-3 py-2 text-sm">
                <button type="button" class="remove-spec-row text-red-500 text-sm px-2">✕</button>
            `;
            rows.appendChild(row);
            wireRemove(row);
        });
    })();
</script>

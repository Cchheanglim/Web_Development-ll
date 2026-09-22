<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /** Home page: category strip + a handful of the most recent listings. */
    public function home(Request $request)
    {
        $products = Product::with(['images', 'seller', 'reviews'])
            ->latest()
            ->take(10)
            ->get();

        return view('home', [
            'products' => $products,
            'categories' => Product::CATEGORIES,
            'favoriteIds' => $this->currentUserFavoriteIds($request),
        ]);
    }

    /** Product listing page with search, category, condition & price filters. */
    public function index(Request $request)
    {
        $products = Product::with(['images', 'seller', 'reviews'])
            ->search($request->query('q'))
            ->condition($request->query('condition'))
            ->category($request->query('category'))
            ->priceBetween(
                $request->query('min_price') !== null && $request->query('min_price') !== ''
                    ? (float) $request->query('min_price') : null,
                $request->query('max_price') !== null && $request->query('max_price') !== ''
                    ? (float) $request->query('max_price') : null,
            )
            ->latest()
            ->paginate(16)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Product::CATEGORIES,
            'favoriteIds' => $this->currentUserFavoriteIds($request),
        ]);
    }

    public function show(Request $request, Product $product)
    {
        $product->load(['images', 'seller', 'reviews.buyer', 'reviews.images', 'specs']);

        // "You might also like" — same category, excluding this product.
        $related = Product::with(['images', 'seller', 'reviews'])
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'related' => $related,
            'favoriteIds' => $this->currentUserFavoriteIds($request),
            'isFavorited' => $this->currentUserFavoriteIds($request)->contains($product->id),
        ]);
    }

    public function create()
    {
        $categories = Product::CATEGORIES;

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', 'in:new,used'],
            'category' => ['required', 'in:' . implode(',', Product::CATEGORIES)],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'max:4096'], // 4MB per image
            'spec_label' => ['nullable', 'array'],
            'spec_label.*' => ['nullable', 'string', 'max:100'],
            'spec_value' => ['nullable', 'array'],
            'spec_value.*' => ['nullable', 'string', 'max:255'],
        ]);

        $product = $request->user()->products()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'category' => $validated['category'],
        ]);

        $this->storeImages($product, $request->file('images', []));
        $this->syncSpecs($product, $validated['spec_label'] ?? [], $validated['spec_value'] ?? []);

        return redirect()
            ->route('products.show', $product)
            ->with('status', 'Product posted!');
    }

    public function edit(Product $product)
    {
        $this->authorizeOwner($product);

        $categories = Product::CATEGORIES;
        $product->load('specs');

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeOwner($product);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', 'in:new,used'],
            'category' => ['required', 'in:' . implode(',', Product::CATEGORIES)],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer', 'exists:product_images,id'],
            'spec_label' => ['nullable', 'array'],
            'spec_label.*' => ['nullable', 'string', 'max:100'],
            'spec_value' => ['nullable', 'array'],
            'spec_value.*' => ['nullable', 'string', 'max:255'],
        ]);

        $product->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'category' => $validated['category'],
        ]);

        // Remove any images the seller checked off in the edit form.
        if (! empty($validated['remove_images'])) {
            $images = $product->images()->whereIn('id', $validated['remove_images'])->get();
            foreach ($images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Add any newly uploaded images.
        $this->storeImages($product, $request->file('images', []));
        $this->syncSpecs($product, $validated['spec_label'] ?? [], $validated['spec_value'] ?? []);

        return redirect()
            ->route('products.show', $product)
            ->with('status', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        $this->authorizeOwner($product);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Product deleted.');
    }

    /** Only the seller who owns the product may edit/delete it. */
    private function authorizeOwner(Product $product): void
    {
        if ($product->user_id !== request()->user()->id) {
            abort(403, 'You can only manage your own products.');
        }
    }

    /** Save uploaded image files to the "public" disk and attach them to the product. */
    private function storeImages(Product $product, array $files): void
    {
        foreach ($files as $file) {
            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
            ]);
        }
    }

    /**
     * Replace a product's spec rows with the given label/value pairs
     * (parallel arrays from the form). Blank rows are skipped so an
     * empty "add another spec" row submitted by mistake doesn't save.
     */
    private function syncSpecs(Product $product, array $labels, array $values): void
    {
        $product->specs()->delete();

        foreach ($labels as $i => $label) {
            $label = trim($label ?? '');
            $value = trim($values[$i] ?? '');

            if ($label === '' || $value === '') {
                continue;
            }

            $product->specs()->create([
                'label' => $label,
                'value' => $value,
                'sort_order' => $i,
            ]);
        }
    }

    /** IDs of products the current user has favorited — empty collection when logged out. */
    private function currentUserFavoriteIds(Request $request)
    {
        return $request->user()
            ? $request->user()->favorites()->pluck('products.id')
            : collect();
    }
}

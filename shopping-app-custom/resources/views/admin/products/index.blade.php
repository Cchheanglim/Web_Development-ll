@extends('layouts.app')
@section('title', 'Manage products')

@section('content')
    <h1 class="text-2xl font-bold mb-6 mt-6">Manage products</h1>

    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="p-3">Title</th>
                    <th class="p-3">Seller</th>
                    <th class="p-3">Price</th>
                    <th class="p-3">Condition</th>
                    <th class="p-3">Category</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($products as $product)
                    <tr>
                        <td class="p-3">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-orange-600">{{ $product->title }}</a>
                        </td>
                        <td class="p-3">{{ $product->seller->name }}</td>
                        <td class="p-3">${{ number_format($product->price, 2) }}</td>
                        <td class="p-3 capitalize">{{ $product->condition }}</td>
                        <td class="p-3">{{ $product->category }}</td>
                        <td class="p-3 text-right">
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Remove this product?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection

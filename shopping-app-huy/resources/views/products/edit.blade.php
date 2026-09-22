@extends('layouts.app')
@section('title', 'Edit ' . $product->title)

@section('content')
    <h1 class="text-2xl font-bold mb-6 mt-6">Edit product</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
          class="bg-white border rounded-lg p-6 max-w-2xl space-y-5">
        @csrf
        @method('PUT')
        @include('products._form', ['product' => $product])

        <button type="submit" class="bg-orange-600 text-white px-5 py-2.5 rounded-md hover:bg-orange-700 font-semibold">
            Save changes
        </button>
    </form>
@endsection

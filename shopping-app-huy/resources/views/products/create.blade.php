@extends('layouts.app')
@section('title', 'Post a product')

@section('content')
    <h1 class="text-2xl font-bold mb-6 mt-6">Post a product for sale</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white border rounded-lg p-6 max-w-2xl space-y-5">
        @csrf
        @include('products._form')

        <button type="submit" class="bg-orange-600 text-white px-5 py-2.5 rounded-md hover:bg-orange-700 font-semibold">
            Post product
        </button>
    </form>
@endsection

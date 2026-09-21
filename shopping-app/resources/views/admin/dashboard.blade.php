@extends('layouts.app')
@section('title', 'Admin dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6 mt-6">Admin dashboard</h1>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
        <div class="bg-white border rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $stats['users'] }}</p>
            <p class="text-sm text-gray-500">Total users</p>
        </div>
        <div class="bg-white border rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $stats['sellers'] }}</p>
            <p class="text-sm text-gray-500">Sellers</p>
        </div>
        <div class="bg-white border rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $stats['buyers'] }}</p>
            <p class="text-sm text-gray-500">Buyers</p>
        </div>
        <div class="bg-white border rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $stats['products'] }}</p>
            <p class="text-sm text-gray-500">Products listed</p>
        </div>
        <div class="bg-white border rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $stats['orders'] }}</p>
            <p class="text-sm text-gray-500">Total orders</p>
        </div>
        <div class="bg-white border rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-amber-500">{{ $stats['pending_orders'] }}</p>
            <p class="text-sm text-gray-500">Pending orders</p>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('admin.users.index') }}" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
            Manage users
        </a>
        <a href="{{ route('admin.products.index') }}" class="border border-orange-600 text-orange-600 px-4 py-2 rounded-md hover:bg-orange-50">
            Manage products
        </a>
    </div>
@endsection

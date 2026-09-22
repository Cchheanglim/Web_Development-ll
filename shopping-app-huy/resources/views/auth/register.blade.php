@extends('layouts.app')
@section('title', 'Sign up')

@section('content')
    <div class="max-w-md mx-auto mt-10 bg-white border rounded-lg p-8 shadow-sm">
        <div class="text-center mb-6">
            <span class="bg-orange-600 text-white font-black text-2xl w-12 h-12 rounded-md inline-flex items-center justify-center">P</span>
            <h1 class="text-2xl font-bold mt-3">Create your account</h1>
            <p class="text-sm text-gray-400">Join PsaOnline to buy or sell in minutes</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirm password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">I want to</label>
                <select name="role" required class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="buyer" @selected(old('role') === 'buyer')>Buy things (Buyer)</option>
                    <option value="seller" @selected(old('role') === 'seller')>Sell things (Seller)</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-orange-600 text-white py-2.5 rounded-md hover:bg-orange-700 font-semibold">
                Sign up
            </button>
        </form>

        <p class="text-sm text-gray-500 text-center mt-5">
            Already have an account? <a href="{{ route('login') }}" class="text-orange-600 font-medium hover:underline">Log in</a>
        </p>
    </div>
@endsection

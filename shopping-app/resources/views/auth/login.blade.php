@extends('layouts.app')
@section('title', 'Log in')

@section('content')
    <div class="max-w-md mx-auto mt-10 bg-white border rounded-lg p-8 shadow-sm">
        <div class="text-center mb-6">
            <span class="bg-orange-600 text-white font-black text-2xl w-12 h-12 rounded-md inline-flex items-center justify-center">M</span>
            <h1 class="text-2xl font-bold mt-3">Welcome back</h1>
            <p class="text-sm text-gray-400">Log in to your MarketHub account</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember"> Remember me
            </label>

            <button type="submit" class="w-full bg-orange-600 text-white py-2.5 rounded-md hover:bg-orange-700 font-semibold">
                Log in
            </button>
        </form>

        <p class="text-sm text-gray-500 text-center mt-5">
            No account yet? <a href="{{ route('register') }}" class="text-orange-600 font-medium hover:underline">Sign up</a>
        </p>

        <div class="text-xs text-gray-400 text-center mt-6 border-t pt-4">
            Admin login (seeded): admin@example.com / password<br>
            No buyer or seller account yet? <a href="{{ route('register') }}" class="text-orange-600 hover:underline">Sign up</a> to create one.
        </div>
    </div>
@endsection

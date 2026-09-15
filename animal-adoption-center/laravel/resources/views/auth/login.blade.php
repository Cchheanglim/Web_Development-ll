@extends('layouts.app')

@section('title', 'Owner Login - Haven Paws')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <!-- Logo and Heading -->
        <div class="text-center">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-stone-900 text-white text-2xl shadow-lg">
                🐾
            </span>
            <h2 class="mt-4 text-2xl font-bold tracking-tight text-stone-900">
                Owner & Staff Sign In
            </h2>
            <p class="mt-1 text-xs text-stone-500">
                Sign in to manage pets, update shelter details, and review adoption statuses.
            </p>
        </div>

        <!-- Seeder Notice Helper Box -->
        <div class="rounded-2xl bg-amber-50 border border-amber-200/80 p-4 text-xs text-amber-900">
            <p class="font-bold flex items-center gap-1.5">
                <span>🔑</span> Default Seeded Owner Credentials
            </p>
            <div class="mt-2 space-y-1 font-mono text-[11px] bg-white/70 p-2.5 rounded-xl border border-amber-100">
                <div>Email: <span class="font-bold text-stone-900">admin@adoptioncenter.com</span></div>
                <div>Password: <span class="font-bold text-stone-900">password123</span></div>
            </div>
            <p class="mt-2 text-[11px] text-amber-700 italic">
                * Please update your password upon initial login in production.
            </p>
        </div>

        <!-- Login Form -->
        <div class="rounded-3xl bg-white p-8 shadow-xl shadow-stone-200/50 border border-stone-200/80">
            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-stone-700">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'admin@adoptioncenter.com') }}" required autofocus
                           class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-stone-700">Password</label>
                    <input type="password" id="password" name="password" value="password123" required
                           class="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-stone-600">
                        <input type="checkbox" name="remember" class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                        <span>Remember my session</span>
                    </label>
                </div>

                <button type="submit" class="w-full rounded-xl bg-stone-900 hover:bg-stone-800 py-3 text-sm font-bold text-white shadow-md transition-all">
                    Sign In to Dashboard &rarr;
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xs text-stone-500 hover:text-stone-800">
                &larr; Back to Public Showcase
            </a>
        </div>
    </div>
</div>
@endsection

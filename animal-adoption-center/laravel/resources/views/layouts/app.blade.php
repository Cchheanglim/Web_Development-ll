<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-50 text-stone-800 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Haven Paws Animal Adoption Center')</title>
    <!-- Tailwind CSS CDN or Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="flex min-h-full flex-col bg-[#FDFBF7]">
    <!-- Top Alert / Flash Messages -->
    @if(session('success'))
        <div id="flash-banner" class="bg-emerald-600 text-white px-4 py-3 text-sm font-medium flex items-center justify-between shadow-sm">
            <div class="max-w-7xl mx-auto flex items-center gap-2 w-full">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="document.getElementById('flash-banner').remove()" class="text-white/80 hover:text-white text-lg leading-none">&times;</button>
        </div>
    @endif

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 border-b border-amber-900/10 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <!-- Brand / Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20 group-hover:scale-105 transition-transform">
                    🐾
                </span>
                <div>
                    <span class="block text-lg font-bold text-stone-900 tracking-tight leading-tight">Haven Paws</span>
                    <span class="block text-xs font-medium text-amber-700 tracking-wide uppercase">Adoption Center</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="transition-colors hover:text-amber-700 {{ request()->routeIs('home') ? 'text-amber-700 font-semibold' : 'text-stone-600' }}">
                    Browse Pets
                </a>
                <a href="{{ route('about') }}" class="transition-colors hover:text-amber-700 {{ request()->routeIs('about') ? 'text-amber-700 font-semibold' : 'text-stone-600' }}">
                    About & Visiting
                </a>

                @auth
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3.5 py-1.5 text-xs font-semibold text-amber-800 hover:bg-amber-200 transition-colors">
                        ⚙️ Owner Dashboard
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer with discrete Owner Login link -->
    <footer class="mt-auto border-t border-stone-200 bg-stone-900 text-stone-300">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-500 text-white text-base">🐾</span>
                    <div>
                        <p class="font-bold text-white text-base">Haven Paws Animal Adoption Center</p>
                        <p class="text-xs text-stone-400">Non-profit shelter committed to humane animal rescue & adoption.</p>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="flex flex-wrap items-center gap-6 text-sm text-stone-400">
                    <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Browse Animals</a>
                    <a href="{{ route('about') }}" class="hover:text-amber-400 transition-colors">Shelter Story & Hours</a>
                    
                    <!-- Discrete Owner Login Link -->
                    @guest
                        <a href="{{ route('login') }}" class="text-xs text-stone-500 hover:text-stone-300 transition-colors inline-flex items-center gap-1 border-l border-stone-700 pl-4">
                            🔒 Staff & Owner Portal
                        </a>
                    @else
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-stone-400 hover:text-red-400 transition-colors inline-flex items-center gap-1 border-l border-stone-700 pl-4">
                                Sign Out ({{ Auth::user()->name }})
                            </button>
                        </form>
                    @endguest
                </div>
            </div>

            <div class="mt-8 border-t border-stone-800 pt-6 text-center text-xs text-stone-500">
                &copy; {{ date('Y') }} Haven Paws Animal Adoption Center. Powered by Laravel 11.
            </div>
        </div>
    </footer>
</body>
</html>

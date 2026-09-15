<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-100 text-stone-800 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Owner Dashboard') - Haven Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="flex min-h-full flex-col bg-stone-100">
    <!-- Top Admin Bar -->
    <header class="sticky top-0 z-30 border-b border-stone-200 bg-white shadow-xs">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 font-bold text-stone-900">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-stone-900 text-white text-base">🐾</span>
                    <span>Haven Paws <span class="rounded bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-900">Owner Admin</span></span>
                </a>

                <nav class="hidden md:flex items-center gap-1 text-sm font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.pets.index') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('admin.pets.*') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900' }}">
                        Manage Pets
                    </a>
                    <a href="{{ route('admin.shelter-profile.edit') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('admin.shelter-profile.*') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900' }}">
                        Shelter Profile
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="text-xs font-medium text-amber-700 hover:text-amber-800 flex items-center gap-1">
                    <span>View Public Site &rarr;</span>
                </a>

                <div class="h-4 w-px bg-stone-200"></div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-stone-500 hidden sm:inline">{{ Auth::user()->email }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-lg border border-stone-300 px-2.5 py-1 text-xs font-medium text-stone-700 hover:bg-stone-50 hover:text-red-600 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-800">
                <p class="font-semibold">Please fix the following validation errors:</p>
                <ul class="mt-1 list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Content -->
    <main class="flex-grow py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PsaOnline')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    {{-- ===================== Thin utility bar ===================== --}}
    <div class="bg-gray-900 text-gray-300 text-xs">
        <div class="max-w-7xl mx-auto px-4 h-8 flex items-center justify-between">
            <span>Buy and sell locally &mdash; chat directly with sellers, no middleman.</span>
            <div class="hidden sm:flex items-center gap-4">
                @auth
                    <span>Hi, {{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="hover:text-white">Log in</a>
                    <a href="{{ route('register') }}" class="hover:text-white">Sign up</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- ===================== Main navbar: logo + big search ===================== --}}
    <header class="bg-white border-b sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                <span class="bg-orange-600 text-white font-black text-xl w-9 h-9 rounded-md flex items-center justify-center">P</span>
                <span class="text-xl font-extrabold text-gray-900 hidden sm:inline">Psa<span class="text-orange-600">Online</span></span>
            </a>

            {{-- Search bar (submits to the product listing with ?q=) --}}
            <form action="{{ route('products.index') }}" method="GET" class="flex-1 flex max-w-2xl">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for anything..."
                       class="flex-1 border border-gray-300 rounded-l-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-5 rounded-r-md text-sm font-semibold">
                    Search
                </button>
            </form>

            <div class="hidden md:flex items-center gap-5 text-sm font-medium ml-auto shrink-0">
                @auth
                    @php
                        $cartCount = auth()->user()->cartItems()->sum('quantity');
                    @endphp
                    <a href="{{ route('cart.index') }}" class="hover:text-orange-600 relative flex items-center">
                        @include('partials.icon', ['name' => 'cart', 'class' => 'w-5 h-5'])
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('dashboard') }}" class="hover:text-orange-600 relative">
                        Dashboard
                        @if(auth()->user()->isSeller())
                            @php
                                $pendingCount = \App\Models\Order::where('seller_id', auth()->id())->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="absolute -top-2 -right-3 bg-red-600 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                                    {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                                </span>
                            @endif
                        @endif
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-orange-600">Admin</a>
                    @endif

                    @if(auth()->user()->isSeller())
                        <a href="{{ route('products.create') }}"
                           class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 font-semibold">
                            + Post product
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-orange-600">Login</a>
                    <a href="{{ route('register') }}"
                       class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 font-semibold">
                        Sign up free
                    </a>
                @endauth
            </div>
        </div>

        {{-- Category strip --}}
        <nav class="border-t bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 flex items-center gap-5 h-10 text-xs font-medium text-gray-600 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('products.index') }}" class="hover:text-orange-600 flex items-center gap-1 {{ request()->routeIs('products.index') && !request('category') ? 'text-orange-600' : '' }}">
                    <span>@include('partials.icon', ['name' => 'bars', 'class' => 'w-4 h-4'])</span> All products
                </a>
                @foreach(\App\Models\Product::CATEGORIES as $cat)
                    <a href="{{ route('products.index', ['category' => $cat]) }}"
                       class="hover:text-orange-600 {{ request('category') === $cat ? 'text-orange-600 font-semibold' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </nav>
    </header>

    {{-- ===================== Flash / validation messages ===================== --}}
    <div class="max-w-7xl mx-auto w-full px-4 mt-4">
        @if(session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-md mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-md mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- ===================== Page content ===================== --}}
    <main class="max-w-7xl mx-auto w-full px-4 pb-16 flex-1">
        @yield('content')
    </main>

    {{-- ===================== Trust strip ===================== --}}
    <div class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-xs text-gray-500">
            <div>
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center">
                    @include('partials.icon', ['name' => 'lock', 'class' => 'w-5 h-5'])
                </div>
                <p class="font-semibold text-gray-800">Secure in-app chat</p>
                <p>Every conversation stays on PsaOnline</p>
            </div>
            <div>
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center">
                    @include('partials.icon', ['name' => 'cart', 'class' => 'w-5 h-5'])
                </div>
                <p class="font-semibold text-gray-800">Buy Now requests</p>
                <p>Sellers confirm before anything is final</p>
            </div>
            <div>
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center">
                    @include('partials.icon', ['name' => 'badge-check', 'class' => 'w-5 h-5'])
                </div>
                <p class="font-semibold text-gray-800">Verified accounts</p>
                <p>Every buyer and seller signs up with an account</p>
            </div>
            <div>
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center">
                    @include('partials.icon', ['name' => 'map-pin', 'class' => 'w-5 h-5'])
                </div>
                <p class="font-semibold text-gray-800">Meet safely</p>
                <p>We recommend public places for handoffs</p>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-gray-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-2 md:grid-cols-5 gap-8">
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="bg-orange-600 text-white font-black w-8 h-8 rounded-md flex items-center justify-center">P</span>
                    <span class="text-white font-bold">PsaOnline</span>
                </div>
                <p class="text-xs leading-relaxed">A buyer/seller marketplace with in-app chat and simple order requests. Built with Laravel.</p>
            </div>
            <div>
                <p class="text-white font-semibold mb-3 text-xs uppercase tracking-wide">Categories</p>
                <ul class="space-y-2 text-xs">
                    @foreach(array_slice(\App\Models\Product::CATEGORIES, 0, 6) as $cat)
                        <li><a href="{{ route('products.index', ['category' => $cat]) }}" class="hover:text-white">{{ $cat }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="text-white font-semibold mb-3 text-xs uppercase tracking-wide">Shop</p>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('products.index') }}" class="hover:text-white">All products</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white">Become a seller</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white">My dashboard</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <p class="text-white font-semibold mb-3 text-xs uppercase tracking-wide">Account</p>
                <ul class="space-y-2 text-xs">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-white">Log in</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white">Sign up</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <p class="text-white font-semibold mb-3 text-xs uppercase tracking-wide">Safety tips</p>
                <ul class="space-y-2 text-xs">
                    <li>Meet in public places</li>
                    <li>Keep chats inside the app</li>
                    <li>Inspect items before paying</li>
                    <li>Only confirm orders you intend to fulfill</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 text-center text-xs py-4">
            &copy; {{ date('Y') }} PsaOnline &mdash; Laravel demo project
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

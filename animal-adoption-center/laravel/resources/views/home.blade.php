@extends('layouts.app')

@section('title', $shelter->shelter_name ?? 'Haven Paws Animal Adoption Center')

@section('content')
<!-- Hero / Shelter Bio Summary Banner -->
<section class="relative overflow-hidden bg-gradient-to-b from-amber-100/60 to-transparent py-14 md:py-20 border-b border-amber-900/5">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7 space-y-5">
                <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3.5 py-1 text-xs font-semibold text-amber-800">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $shelter->tagline ?? 'Connecting Loving Families with Pets in Need' }}
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-stone-900 leading-tight">
                    Find your new <span class="text-amber-700 underline decoration-amber-300">best friend</span> today.
                </h1>
                <p class="text-base sm:text-lg text-stone-600 max-w-2xl leading-relaxed">
                    {{ $shelter->bio ?? 'Welcome to Haven Paws! We are dedicated to rescuing, rehabilitating, and rehoming abandoned and neglected companion animals.' }}
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="#browse-pets" class="rounded-xl bg-stone-900 px-6 py-3.5 text-sm font-semibold text-white shadow-md hover:bg-stone-800 transition-all">
                        Browse Animals
                    </a>
                    <a href="{{ route('about') }}" class="rounded-xl border border-stone-300 bg-white px-6 py-3.5 text-sm font-semibold text-stone-700 hover:bg-stone-50 transition-all">
                        Visiting Hours & Story
                    </a>
                </div>
            </div>

            <!-- Shelter Quick Stats Card -->
            <div class="lg:col-span-5">
                <div class="rounded-3xl bg-white p-6 shadow-xl shadow-stone-200/50 border border-stone-100 space-y-4">
                    <div class="flex items-center gap-4 border-b border-stone-100 pb-4">
                        <div class="h-12 w-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-700 text-2xl">
                            🏡
                        </div>
                        <div>
                            <h3 class="font-bold text-stone-900 text-base">{{ $shelter->shelter_name ?? 'Haven Paws Center' }}</h3>
                            <p class="text-xs text-stone-500">Open for meet-and-greets & walk-ins</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="rounded-xl bg-stone-50 p-3">
                            <span class="block text-stone-400 font-medium">Hours</span>
                            <span class="block font-semibold text-stone-800 mt-1">{{ $shelter->opening_hours ?? '10am - 5:30pm' }}</span>
                        </div>
                        <div class="rounded-xl bg-stone-50 p-3">
                            <span class="block text-stone-400 font-medium">Phone Inquiries</span>
                            <span class="block font-semibold text-stone-800 mt-1">{{ $shelter->phone ?? '(555) 234-5678' }}</span>
                        </div>
                    </div>

                    <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-3 text-xs text-emerald-800 flex items-center gap-2">
                        <span class="text-base">❤️</span>
                        <span>All animals are fully vaccinated, microchipped & spayed/neutered.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pets Showcase & Filter Section -->
<section id="browse-pets" class="py-12 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Controls Header: Search, Species Tabs, Status Filter -->
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">Meet Our Pets</h2>
                    <p class="text-sm text-stone-500 mt-1">Select a filter or search to find dogs, cats, rabbits, and companion friends.</p>
                </div>

                <!-- Search Input Form -->
                <form action="{{ route('home') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="type" value="{{ request('type', 'All') }}">
                    <input type="hidden" name="status" value="{{ request('status', 'All') }}">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or breed..." class="w-full rounded-xl border border-stone-300 bg-white py-2 pl-3 pr-8 text-sm placeholder:text-stone-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        @if(request('search'))
                            <a href="{{ route('home', ['type' => request('type', 'All'), 'status' => request('status', 'All')]) }}" class="absolute right-2.5 top-2.5 text-xs text-stone-400 hover:text-stone-600">&times;</a>
                        @endif
                    </div>
                    <button type="submit" class="rounded-xl bg-stone-900 px-4 py-2 text-xs font-semibold text-white hover:bg-stone-800">Search</button>
                </form>
            </div>

            <!-- Species Type Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2 border-b border-stone-200 pb-3">
                @foreach($petTypes as $t)
                    <a href="{{ route('home', ['type' => $t, 'status' => request('status', 'All'), 'search' => request('search')]) }}"
                       class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ request('type', 'All') === $t ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                        @if($t === 'All') 🐾 All Species
                        @elseif($t === 'Dog') 🐕 Dogs
                        @elseif($t === 'Cat') 🐈 Cats
                        @elseif($t === 'Bird') 🦜 Birds
                        @elseif($t === 'Rabbit') 🐇 Rabbits
                        @else 🐾 Others
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Status Filter Badges -->
            <div class="flex items-center gap-3 text-xs font-medium text-stone-500">
                <span>Filter Status:</span>
                @foreach($statuses as $s)
                    <a href="{{ route('home', ['status' => $s, 'type' => request('type', 'All'), 'search' => request('search')]) }}"
                       class="rounded-lg px-2.5 py-1 transition-colors {{ request('status', 'All') === $s ? 'bg-stone-900 text-white' : 'hover:bg-stone-200 text-stone-600' }}">
                        {{ $s }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Pet Grid -->
        @if($pets->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($pets as $pet)
                    <div class="group flex flex-col overflow-hidden rounded-2xl bg-white border border-stone-200/80 shadow-sm hover:shadow-md transition-all">
                        <!-- Pet Image Container -->
                        <div class="relative aspect-4/3 w-full overflow-hidden bg-stone-100">
                            <img src="{{ $pet->image_url }}" alt="{{ $pet->name }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Color-coded Status Badge -->
                            <div class="absolute top-3 right-3">
                                @if($pet->status === 'Available')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 backdrop-blur-xs px-3 py-1 text-xs font-bold text-white shadow-xs">
                                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span> Available
                                    </span>
                                @elseif($pet->status === 'Pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/90 backdrop-blur-xs px-3 py-1 text-xs font-bold text-white shadow-xs">
                                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-stone-500/90 backdrop-blur-xs px-3 py-1 text-xs font-bold text-white shadow-xs">
                                        Adopted
                                    </span>
                                @endif
                            </div>

                            <div class="absolute bottom-3 left-3">
                                <span class="rounded-lg bg-black/60 backdrop-blur-xs px-2.5 py-1 text-[11px] font-semibold text-white">
                                    {{ $pet->type }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="flex flex-1 flex-col justify-between p-5 space-y-4">
                            <div>
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-stone-900 group-hover:text-amber-700 transition-colors">
                                        {{ $pet->name }}
                                    </h3>
                                    <span class="text-xs font-medium text-stone-500">{{ $pet->age }}</span>
                                </div>
                                <p class="text-xs font-semibold text-amber-800 mt-0.5">
                                    {{ $pet->breed ?? 'Mixed Breed' }} &bull; {{ $pet->gender }}
                                </p>
                                <p class="text-xs text-stone-600 line-clamp-2 mt-2 leading-relaxed">
                                    {{ $pet->description }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 pt-1">
                                <a href="{{ route('pets.show', $pet) }}" class="flex-1 rounded-xl bg-stone-100 hover:bg-stone-200 py-2 text-center text-xs font-semibold text-stone-800 transition-colors">
                                    View Story
                                </a>
                                @if($pet->status === 'Available')
                                    <a href="{{ route('pets.show', $pet) }}#inquire" class="flex-1 rounded-xl bg-amber-600 hover:bg-amber-700 py-2 text-center text-xs font-semibold text-white shadow-sm transition-colors">
                                        Adopt Milo &rarr;
                                    </a>
                                @else
                                    <button disabled class="flex-1 rounded-xl bg-stone-100 py-2 text-center text-xs font-medium text-stone-400 cursor-not-allowed">
                                        {{ $pet->status }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $pets->links() }}
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-stone-300 bg-white p-12 text-center">
                <span class="text-4xl">🐾</span>
                <h3 class="mt-3 text-lg font-bold text-stone-900">No pets match the current filter</h3>
                <p class="mt-1 text-sm text-stone-500">Try changing species or status, or clear your search term.</p>
                <div class="mt-4">
                    <a href="{{ route('home') }}" class="inline-flex rounded-xl bg-stone-900 px-4 py-2 text-xs font-semibold text-white hover:bg-stone-800">
                        Reset Filters
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

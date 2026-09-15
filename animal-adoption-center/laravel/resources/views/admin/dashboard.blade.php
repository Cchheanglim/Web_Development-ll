@extends('layouts.admin')

@section('title', 'Owner Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">
                Welcome, {{ Auth::user()->name }} 👋
            </h1>
            <p class="text-sm text-stone-500 mt-1">
                Overview of adoption metrics, available animals, and center management.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pets.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-600 hover:bg-amber-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all">
                <span>+ Add New Pet</span>
            </a>
            <a href="{{ route('admin.shelter-profile.edit') }}" class="inline-flex items-center gap-2 rounded-xl bg-white border border-stone-300 hover:bg-stone-50 px-4 py-2.5 text-xs font-semibold text-stone-700 transition-all">
                <span>Edit Shelter Info</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Pets -->
        <div class="rounded-2xl bg-white p-6 border border-stone-200/80 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Pets In System</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-stone-100 text-stone-800 text-lg">🐾</span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-stone-900">{{ $totalPets }}</p>
            <span class="text-xs text-stone-500 mt-1 block">All registered animals</span>
        </div>

        <!-- Available for Adoption (Green) -->
        <div class="rounded-2xl bg-white p-6 border border-emerald-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Available For Adoption</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 text-lg">🟢</span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-emerald-700">{{ $availablePets }}</p>
            <span class="text-xs text-emerald-600/80 mt-1 block">Ready for meet-and-greets</span>
        </div>

        <!-- Applications Pending (Amber) -->
        <div class="rounded-2xl bg-white p-6 border border-amber-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Pending Inquiries</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700 text-lg">⏳</span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-amber-700">{{ $pendingPets }}</p>
            <span class="text-xs text-amber-700/80 mt-1 block">In review by shelter staff</span>
        </div>

        <!-- Happily Adopted (Stone/Gray) -->
        <div class="rounded-2xl bg-white p-6 border border-stone-200/80 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Happily Adopted</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-stone-100 text-stone-600 text-lg">🎉</span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-stone-700">{{ $adoptedPets }}</p>
            <span class="text-xs text-stone-400 mt-1 block">Living in forever homes</span>
        </div>
    </div>

    <!-- Recent Pets Table & Shelter Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Pets List -->
        <div class="lg:col-span-8 rounded-3xl bg-white p-6 border border-stone-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-stone-900">Recent Animals</h2>
                    <p class="text-xs text-stone-500">Latest entries into shelter database</p>
                </div>
                <a href="{{ route('admin.pets.index') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-800">
                    View All Pets &rarr;
                </a>
            </div>

            <div class="divide-y divide-stone-100">
                @forelse($recentPets as $pet)
                    <div class="flex items-center justify-between py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="{{ $pet->image_url }}" alt="{{ $pet->name }}" class="h-12 w-12 rounded-xl object-cover">
                            <div>
                                <h4 class="font-bold text-stone-900 text-sm">{{ $pet->name }}</h4>
                                <span class="text-xs text-stone-500">{{ $pet->type }} &bull; {{ $pet->breed }} &bull; {{ $pet->age }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            @if($pet->status === 'Available')
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-800">Available</span>
                            @elseif($pet->status === 'Pending')
                                <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">Pending</span>
                            @else
                                <span class="rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-bold text-stone-600">Adopted</span>
                            @endif

                            <a href="{{ route('admin.pets.edit', $pet) }}" class="rounded-lg border border-stone-200 px-2.5 py-1 text-xs font-semibold text-stone-700 hover:bg-stone-50">
                                Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-xs text-stone-400">No pets have been added yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Shelter Info Card -->
        <div class="lg:col-span-4 rounded-3xl bg-white p-6 border border-stone-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                <h3 class="font-bold text-stone-900 text-base">Shelter Information</h3>
                <a href="{{ route('admin.shelter-profile.edit') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-800">
                    Edit &rarr;
                </a>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-stone-400 font-medium block">Public Name</span>
                    <span class="text-stone-900 font-bold block mt-0.5">{{ $shelter->shelter_name ?? 'Haven Paws Center' }}</span>
                </div>
                <div>
                    <span class="text-stone-400 font-medium block">Tagline</span>
                    <span class="text-stone-700 block mt-0.5 italic">{{ $shelter->tagline ?? 'Connecting Loving Families with Pets' }}</span>
                </div>
                <div>
                    <span class="text-stone-400 font-medium block">Contact Phone & Email</span>
                    <span class="text-stone-800 block mt-0.5">{{ $shelter->phone ?? '(555) 234-5678' }} &bull; {{ $shelter->email ?? 'contact@havenpaws.org' }}</span>
                </div>
                <div>
                    <span class="text-stone-400 font-medium block">Visiting Address</span>
                    <span class="text-stone-800 block mt-0.5">{{ $shelter->address ?? '742 Evergreen Terrace, Springfield, OR' }}</span>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('home') }}" target="_blank" class="block w-full rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-800 text-center py-2.5 text-xs font-bold transition-colors">
                    Preview Public Website
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

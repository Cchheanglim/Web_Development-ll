@extends('layouts.app')

@section('title', 'About Our Shelter - ' . ($shelter->shelter_name ?? 'Haven Paws'))

@section('content')
<div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8 space-y-12">
    <!-- Header -->
    <div class="text-center space-y-4">
        <span class="inline-flex rounded-full bg-amber-100 px-3.5 py-1 text-xs font-semibold text-amber-800">
            About Our Mission
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-stone-900 tracking-tight">
            {{ $shelter->shelter_name ?? 'Haven Paws Animal Adoption Center' }}
        </h1>
        <p class="text-base sm:text-lg text-stone-600 max-w-2xl mx-auto">
            {{ $shelter->tagline ?? 'Connecting Loving Families with Pets in Need' }}
        </p>
    </div>

    <!-- Banner Image -->
    @if($shelter && $shelter->banner_image_path)
        <div class="relative overflow-hidden rounded-3xl aspect-21/9 shadow-lg border border-stone-200">
            <img src="{{ $shelter->banner_image_path }}" alt="{{ $shelter->shelter_name }}" class="h-full w-full object-cover">
        </div>
    @endif

    <!-- Story and Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Story -->
        <div class="md:col-span-2 rounded-3xl bg-white p-8 border border-stone-200/80 shadow-xs space-y-6">
            <h2 class="text-2xl font-bold text-stone-900">Our Rescue Story & Values</h2>
            <div class="prose text-stone-600 text-sm leading-relaxed whitespace-pre-line">
                {{ $shelter->bio ?? 'Haven Paws is dedicated to rescuing dogs, cats, rabbits, and companion animals.' }}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-stone-100">
                <div class="rounded-2xl bg-amber-50/60 p-4 border border-amber-100">
                    <h4 class="font-bold text-stone-900 text-sm">Cage-Free Living</h4>
                    <p class="text-xs text-stone-600 mt-1">Our animals live in spacious, enriched communal rooms designed to keep them calm and happy.</p>
                </div>
                <div class="rounded-2xl bg-amber-50/60 p-4 border border-amber-100">
                    <h4 class="font-bold text-stone-900 text-sm">Comprehensive Vet Care</h4>
                    <p class="text-xs text-stone-600 mt-1">Every pet receives a thorough medical exam, microchip, dental check, and mandatory sterilization.</p>
                </div>
            </div>
        </div>

        <!-- Shelter Quick Contacts & Hours -->
        <div class="space-y-6">
            <div class="rounded-3xl bg-white p-6 border border-stone-200 shadow-xs space-y-5">
                <h3 class="font-bold text-stone-900 text-base">Visiting & Contact Info</h3>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="text-stone-400 font-medium block">Visiting & Adoption Hours</span>
                        <span class="text-stone-800 font-semibold block mt-1">{{ $shelter->opening_hours ?? 'Tue - Sun: 10:00 AM - 5:30 PM' }}</span>
                    </div>

                    <div>
                        <span class="text-stone-400 font-medium block">Shelter Location</span>
                        <span class="text-stone-800 font-semibold block mt-1">{{ $shelter->address ?? '742 Evergreen Terrace, Springfield, OR' }}</span>
                    </div>

                    <div>
                        <span class="text-stone-400 font-medium block">Adoption Inquiries Email</span>
                        <a href="mailto:{{ $shelter->email ?? 'adoptions@havenpaws.org' }}" class="text-amber-700 font-semibold block mt-1 hover:underline">
                            {{ $shelter->email ?? 'adoptions@havenpaws.org' }}
                        </a>
                    </div>

                    <div>
                        <span class="text-stone-400 font-medium block">Direct Phone Line</span>
                        <a href="tel:{{ $shelter->phone ?? '(555) 234-5678' }}" class="text-amber-700 font-semibold block mt-1 hover:underline">
                            {{ $shelter->phone ?? '(555) 234-5678' }}
                        </a>
                    </div>
                </div>

                <div class="pt-4 border-t border-stone-100">
                    <a href="{{ route('home') }}" class="block w-full rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-center py-2.5 text-xs font-bold transition-colors">
                        Browse Available Animals &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

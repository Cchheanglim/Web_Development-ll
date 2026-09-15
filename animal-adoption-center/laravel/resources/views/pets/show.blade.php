@extends('layouts.app')

@section('title', $pet->name . ' - ' . ($shelter->shelter_name ?? 'Haven Paws'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-xs font-medium text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-stone-900">Pets</a>
        <span>/</span>
        <a href="{{ route('home', ['type' => $pet->type]) }}" class="hover:text-stone-900">{{ $pet->type }}s</a>
        <span>/</span>
        <span class="text-stone-900 font-semibold">{{ $pet->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Pet Image and Highlights -->
        <div class="lg:col-span-7 space-y-6">
            <div class="relative overflow-hidden rounded-3xl bg-stone-100 shadow-md border border-stone-200 aspect-4/3">
                <img src="{{ $pet->image_url }}" alt="{{ $pet->name }}" class="h-full w-full object-cover">
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @if($pet->status === 'Available')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-4 py-1.5 text-xs font-bold text-white shadow-md">
                            <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span> Available for Adoption
                        </span>
                    @elseif($pet->status === 'Pending')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-600 px-4 py-1.5 text-xs font-bold text-white shadow-md">
                            Application Pending
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-stone-600 px-4 py-1.5 text-xs font-bold text-white shadow-md">
                            Happily Adopted!
                        </span>
                    @endif
                </div>
            </div>

            <!-- Essential Attributes -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs">
                    <span class="text-xs text-stone-400 font-medium">Species</span>
                    <p class="text-base font-bold text-stone-900 mt-0.5">{{ $pet->type }}</p>
                </div>
                <div class="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs">
                    <span class="text-xs text-stone-400 font-medium">Age</span>
                    <p class="text-base font-bold text-stone-900 mt-0.5">{{ $pet->age }}</p>
                </div>
                <div class="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs">
                    <span class="text-xs text-stone-400 font-medium">Gender</span>
                    <p class="text-base font-bold text-stone-900 mt-0.5">{{ $pet->gender }}</p>
                </div>
                <div class="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs">
                    <span class="text-xs text-stone-400 font-medium">Breed</span>
                    <p class="text-base font-bold text-stone-900 mt-0.5 truncate">{{ $pet->breed ?? 'Domestic' }}</p>
                </div>
            </div>

            <!-- About This Pet -->
            <div class="rounded-3xl bg-white p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-4">
                <h2 class="text-xl font-bold text-stone-900">About {{ $pet->name }}</h2>
                <div class="prose text-stone-600 text-sm leading-relaxed whitespace-pre-line">
                    {{ $pet->description }}
                </div>
                
                <div class="pt-4 border-t border-stone-100 flex flex-wrap gap-2 text-xs">
                    <span class="rounded-lg bg-amber-50 px-3 py-1.5 text-amber-800 font-medium">✓ Spayed / Neutered</span>
                    <span class="rounded-lg bg-amber-50 px-3 py-1.5 text-amber-800 font-medium">✓ Vaccinations Up-to-date</span>
                    <span class="rounded-lg bg-amber-50 px-3 py-1.5 text-amber-800 font-medium">✓ Microchipped</span>
                    <span class="rounded-lg bg-amber-50 px-3 py-1.5 text-amber-800 font-medium">✓ Veterinary Health Exam</span>
                </div>
            </div>
        </div>

        <!-- Inquire / Adoption Sidebar -->
        <div class="lg:col-span-5 space-y-6">
            <div id="inquire" class="rounded-3xl bg-white p-6 sm:p-8 border border-stone-200 shadow-md space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-stone-900">Ready to Adopt {{ $pet->name }}?</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Send an inquiry to schedule a meet-and-greet.</p>
                    </div>
                    <span class="text-3xl">🐾</span>
                </div>

                @if($pet->status === 'Available')
                    <!-- Inquiry Form -->
                    <form onsubmit="alert('Inquiry sent! The shelter team will contact you shortly.'); return false;" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-700">Your Full Name</label>
                            <input type="text" required placeholder="e.g. Jane Doe" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-stone-700">Email Address</label>
                                <input type="email" required placeholder="jane@example.com" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-stone-700">Phone Number</label>
                                <input type="tel" required placeholder="(555) 000-0000" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700">Message / Tell Us About Your Home</label>
                            <textarea rows="3" class="mt-1 w-full rounded-xl border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none">Hi! I would love to learn more about {{ $pet->name }} and schedule a visit to meet them in person.</textarea>
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-amber-600 hover:bg-amber-700 py-3 text-sm font-semibold text-white shadow-md transition-colors">
                            Submit Adoption Inquiry
                        </button>
                    </form>

                    <!-- Direct Email / Call Option -->
                    <div class="pt-4 border-t border-stone-100 text-center space-y-2">
                        <p class="text-xs text-stone-500">Or reach the shelter directly:</p>
                        <div class="flex items-center justify-center gap-4 text-xs font-semibold text-amber-800">
                            <a href="mailto:{{ $shelter->email ?? 'adoptions@havenpaws.org' }}?subject=Inquiry about {{ $pet->name }}" class="hover:underline">
                                ✉️ Email Shelter
                            </a>
                            <span>&bull;</span>
                            <a href="tel:{{ $shelter->phone ?? '(555) 234-5678' }}" class="hover:underline">
                                📞 Call {{ $shelter->phone ?? '(555) 234-5678' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl bg-stone-50 p-6 text-center border border-stone-200">
                        <p class="text-sm font-semibold text-stone-700">{{ $pet->name }} is currently {{ $pet->status }}.</p>
                        <p class="text-xs text-stone-500 mt-1">Check back soon or browse our other wonderful animals waiting for homes!</p>
                        <a href="{{ route('home') }}" class="mt-4 inline-block rounded-xl bg-stone-900 px-4 py-2 text-xs font-semibold text-white">
                            Browse Available Pets
                        </a>
                    </div>
                @endif
            </div>

            <!-- Shelter Hours & Location -->
            <div class="rounded-3xl bg-stone-50 p-6 border border-stone-200 space-y-3 text-xs text-stone-600">
                <h4 class="font-bold text-stone-900 text-sm">Shelter Visiting Address</h4>
                <p>{{ $shelter->address ?? '742 Evergreen Terrace, Springfield, OR' }}</p>
                <p><strong class="text-stone-800">Visiting Hours:</strong> {{ $shelter->opening_hours ?? 'Tue-Sun 10am-5:30pm' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Shelter Settings - Haven Paws')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">Shelter Profile & Settings</h1>
            <p class="text-xs text-stone-500 mt-0.5">Customize your shelter name, public bio, visiting hours, and contact details.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">
            &larr; Back to Dashboard
        </a>
    </div>

    <div class="rounded-3xl bg-white p-8 border border-stone-200/80 shadow-xs">
        <form action="{{ route('admin.shelter-profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Shelter Name and Tagline -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-stone-900 border-b border-stone-100 pb-2">Center Identity</h3>
                
                <div>
                    <label for="shelter_name" class="block text-xs font-bold text-stone-700">Shelter / Organization Name *</label>
                    <input type="text" id="shelter_name" name="shelter_name" value="{{ old('shelter_name', $shelter->shelter_name) }}" required
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('shelter_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tagline" class="block text-xs font-bold text-stone-700">Public Tagline / Slogan</label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $shelter->tagline) }}"
                           placeholder="e.g. Connecting Loving Families with Pets in Need"
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('tagline') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="bio" class="block text-xs font-bold text-stone-700">About Story & Welcome Message</label>
                    <textarea id="bio" name="bio" rows="4"
                              class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none leading-relaxed">{{ old('bio', $shelter->bio) }}</textarea>
                    @error('bio') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Contact & Hours -->
            <div class="space-y-4 pt-4 border-t border-stone-100">
                <h3 class="text-sm font-bold text-stone-900 border-b border-stone-100 pb-2">Public Contact & Visiting Hours</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-xs font-bold text-stone-700">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $shelter->phone) }}" placeholder="(555) 234-5678"
                               class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                        @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-stone-700">Inquiry Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $shelter->email) }}" placeholder="adoptions@havenpaws.org"
                               class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                        @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold text-stone-700">Physical Address / Facility Location</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $shelter->address) }}" placeholder="742 Evergreen Terrace, Springfield, OR"
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="opening_hours" class="block text-xs font-bold text-stone-700">Opening & Visiting Hours</label>
                    <input type="text" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $shelter->opening_hours) }}" placeholder="Tue - Sun: 10:00 AM - 5:30 PM (Closed Mon)"
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('opening_hours') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Banner Image -->
            <div class="space-y-3 pt-4 border-t border-stone-100">
                <h3 class="text-sm font-bold text-stone-900 border-b border-stone-100 pb-2">Cover Banner</h3>
                @if($shelter->banner_image_path)
                    <div class="h-28 w-full rounded-2xl overflow-hidden border border-stone-200">
                        <img src="{{ $shelter->banner_image_path }}" alt="Banner" class="h-full w-full object-cover">
                    </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-stone-700">Upload New Banner Image (Max 3MB)</label>
                    <input type="file" name="banner_image" accept="image/*"
                           class="mt-1 block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                </div>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-stone-300 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-50">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-amber-600 hover:bg-amber-700 px-6 py-2.5 text-xs font-bold text-white shadow-md transition-colors">
                    Save Shelter Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

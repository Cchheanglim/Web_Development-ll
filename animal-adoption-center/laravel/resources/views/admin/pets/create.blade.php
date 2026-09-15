@extends('layouts.admin')

@section('title', 'Add New Pet - Haven Paws')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">Add New Animal</h1>
            <p class="text-xs text-stone-500 mt-0.5">Register a companion animal for public adoption showcase.</p>
        </div>
        <a href="{{ route('admin.pets.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">
            &larr; Back to Pet List
        </a>
    </div>

    <div class="rounded-3xl bg-white p-8 border border-stone-200/80 shadow-xs">
        <form action="{{ route('admin.pets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Name and Type -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-stone-700">Pet Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Bella"
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type" class="block text-xs font-bold text-stone-700">Species / Type *</label>
                    <select id="type" name="type" required class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Breed, Age, Gender -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="breed" class="block text-xs font-bold text-stone-700">Breed (or Mix)</label>
                    <input type="text" id="breed" name="breed" value="{{ old('breed') }}" placeholder="e.g. Golden Retriever"
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('breed') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="age" class="block text-xs font-bold text-stone-700">Age *</label>
                    <input type="text" id="age" name="age" value="{{ old('age') }}" required placeholder="e.g. 2 years, 6 months"
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('age') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="gender" class="block text-xs font-bold text-stone-700">Gender *</label>
                    <select id="gender" name="gender" required class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                        @foreach($genders as $g)
                            <option value="{{ $g }}" {{ old('gender') === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                    @error('gender') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Adoption Status -->
            <div>
                <label for="status" class="block text-xs font-bold text-stone-700">Adoption Status *</label>
                <select id="status" name="status" required class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ old('status', 'Available') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Description / Bio -->
            <div>
                <label for="description" class="block text-xs font-bold text-stone-700">Pet Bio & Story *</label>
                <textarea id="description" name="description" rows="4" required placeholder="Describe personality, energy level, ideal family, and medical history..."
                          class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none leading-relaxed">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-xs font-bold text-stone-700">Pet Photo (JPEG, PNG, WEBP &bull; Max 2MB)</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                       class="mt-1 block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                @error('image') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Form Buttons -->
            <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.pets.index') }}" class="rounded-xl border border-stone-300 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-50">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-amber-600 hover:bg-amber-700 px-6 py-2.5 text-xs font-bold text-white shadow-md transition-colors">
                    Save & Publish Pet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Edit ' . $pet->name . ' - Haven Paws')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">Edit Animal Profile</h1>
            <p class="text-xs text-stone-500 mt-0.5">Updating information for <strong>{{ $pet->name }}</strong> (#{{ $pet->id }})</p>
        </div>
        <a href="{{ route('admin.pets.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">
            &larr; Back to Pet List
        </a>
    </div>

    <div class="rounded-3xl bg-white p-8 border border-stone-200/80 shadow-xs">
        <form action="{{ route('admin.pets.update', $pet) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Image Preview -->
            <div class="flex items-center gap-4 rounded-2xl bg-stone-50 p-4 border border-stone-200">
                <img src="{{ $pet->image_url }}" alt="{{ $pet->name }}" class="h-20 w-20 rounded-2xl object-cover border border-stone-300">
                <div class="text-xs space-y-1">
                    <span class="font-bold text-stone-900 block">Current Photo</span>
                    <p class="text-stone-500">To replace this photo, upload a new image below. Otherwise, the current photo will remain active.</p>
                </div>
            </div>

            <!-- Name and Type -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-stone-700">Pet Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $pet->name) }}" required
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type" class="block text-xs font-bold text-stone-700">Species / Type *</label>
                    <select id="type" name="type" required class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ old('type', $pet->type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Breed, Age, Gender -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="breed" class="block text-xs font-bold text-stone-700">Breed (or Mix)</label>
                    <input type="text" id="breed" name="breed" value="{{ old('breed', $pet->breed) }}"
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('breed') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="age" class="block text-xs font-bold text-stone-700">Age *</label>
                    <input type="text" id="age" name="age" value="{{ old('age', $pet->age) }}" required
                           class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                    @error('age') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="gender" class="block text-xs font-bold text-stone-700">Gender *</label>
                    <select id="gender" name="gender" required class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none">
                        @foreach($genders as $g)
                            <option value="{{ $g }}" {{ old('gender', $pet->gender) === $g ? 'selected' : '' }}>{{ $g }}</option>
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
                        <option value="{{ $s }}" {{ old('status', $pet->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Description / Bio -->
            <div>
                <label for="description" class="block text-xs font-bold text-stone-700">Pet Bio & Story *</label>
                <textarea id="description" name="description" rows="4" required
                          class="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-500 focus:outline-none leading-relaxed">{{ old('description', $pet->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- New Image Upload -->
            <div>
                <label class="block text-xs font-bold text-stone-700">Replace Pet Photo (Optional &bull; Max 2MB)</label>
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
                    Update Pet Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

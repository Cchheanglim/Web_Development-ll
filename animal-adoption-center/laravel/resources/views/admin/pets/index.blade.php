@extends('layouts.admin')

@section('title', 'Manage Pets - Haven Paws')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">Manage Pets & Adoption Statuses</h1>
            <p class="text-xs text-stone-500 mt-1">Create, update, or remove pets and change their availability.</p>
        </div>
        <div>
            <a href="{{ route('admin.pets.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-600 hover:bg-amber-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all">
                <span>+ Add New Pet</span>
            </a>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.pets.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or breed..."
                   class="rounded-xl border border-stone-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none">
            
            <select name="type" class="rounded-xl border border-stone-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none">
                <option value="All">All Species</option>
                <option value="Dog" {{ request('type') === 'Dog' ? 'selected' : '' }}>Dogs</option>
                <option value="Cat" {{ request('type') === 'Cat' ? 'selected' : '' }}>Cats</option>
                <option value="Bird" {{ request('type') === 'Bird' ? 'selected' : '' }}>Birds</option>
                <option value="Rabbit" {{ request('type') === 'Rabbit' ? 'selected' : '' }}>Rabbits</option>
                <option value="Other" {{ request('type') === 'Other' ? 'selected' : '' }}>Other</option>
            </select>

            <select name="status" class="rounded-xl border border-stone-300 px-3 py-1.5 text-xs focus:border-amber-500 focus:outline-none">
                <option value="All">All Statuses</option>
                <option value="Available" {{ request('status') === 'Available' ? 'selected' : '' }}>Available</option>
                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Adopted" {{ request('status') === 'Adopted' ? 'selected' : '' }}>Adopted</option>
            </select>

            <button type="submit" class="rounded-xl bg-stone-900 px-3 py-1.5 text-xs font-semibold text-white">Filter</button>
            @if(request('search') || request('type') || request('status'))
                <a href="{{ route('admin.pets.index') }}" class="text-xs text-stone-500 hover:text-stone-800">Clear</a>
            @endif
        </form>

        <span class="text-xs text-stone-500 font-medium">
            Showing {{ $pets->count() }} records
        </span>
    </div>

    <!-- Pets Table -->
    <div class="overflow-hidden rounded-3xl bg-white border border-stone-200/80 shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-600 uppercase font-semibold border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4">Pet / Photo</th>
                        <th class="px-6 py-4">Species & Breed</th>
                        <th class="px-6 py-4">Age & Gender</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($pets as $pet)
                        <tr class="hover:bg-stone-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $pet->image_url }}" alt="{{ $pet->name }}" class="h-12 w-12 rounded-xl object-cover border border-stone-200">
                                    <div>
                                        <a href="{{ route('pets.show', $pet) }}" target="_blank" class="font-bold text-stone-900 hover:text-amber-700 text-sm">
                                            {{ $pet->name }}
                                        </a>
                                        <span class="block text-[11px] text-stone-400">ID: #{{ $pet->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-stone-900 block">{{ $pet->type }}</span>
                                <span class="text-stone-500">{{ $pet->breed ?? 'Unknown Breed' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="block font-medium text-stone-900">{{ $pet->age }}</span>
                                <span class="text-stone-500">{{ $pet->gender }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($pet->status === 'Available')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span> Available
                                    </span>
                                @elseif($pet->status === 'Pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-600"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-stone-100 px-3 py-1 text-xs font-bold text-stone-600">
                                        Adopted
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.pets.edit', $pet) }}" class="rounded-lg bg-stone-100 px-3 py-1.5 font-semibold text-stone-700 hover:bg-stone-200 transition-colors">
                                        Edit
                                    </a>

                                    <!-- Trigger Deletion Modal -->
                                    <button onclick="openDeleteModal({{ $pet->id }}, '{{ addslashes($pet->name) }}')"
                                            class="rounded-lg bg-rose-50 px-3 py-1.5 font-semibold text-rose-700 hover:bg-rose-100 transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-stone-400">
                                No pets found. Click "+ Add New Pet" to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-stone-100">
            {{ $pets->links() }}
        </div>
    </div>
</div>

<!-- Deletion Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl border border-stone-200 space-y-4">
        <div class="flex items-center gap-3 text-rose-600">
            <span class="text-2xl">⚠️</span>
            <h3 class="text-lg font-bold text-stone-900">Confirm Pet Deletion</h3>
        </div>
        <p class="text-xs text-stone-600 leading-relaxed">
            Are you sure you want to permanently delete <strong id="delete-pet-name" class="text-stone-900"></strong> from the adoption center database? This will also remove any uploaded photos associated with this pet.
        </p>

        <form id="delete-form" action="" method="POST" class="pt-2 flex items-center justify-end gap-3">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="rounded-xl border border-stone-300 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50">
                Cancel
            </button>
            <button type="submit" class="rounded-xl bg-rose-600 hover:bg-rose-700 px-4 py-2 text-xs font-bold text-white shadow-sm">
                Yes, Delete Record
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(petId, petName) {
        document.getElementById('delete-pet-name').textContent = petName;
        document.getElementById('delete-form').action = '/admin/pets/' + petId;
        const modal = document.getElementById('delete-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection

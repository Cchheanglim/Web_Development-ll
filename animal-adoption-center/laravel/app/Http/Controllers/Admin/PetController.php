<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Display a listing of pets in the Owner management view.
     */
    public function index(Request $request)
    {
        $query = Pet::query();

        if ($request->filled('type') && $request->type !== 'All') {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%");
            });
        }

        $pets = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new pet.
     */
    public function create()
    {
        $types = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
        $genders = ['Male', 'Female', 'Unknown'];
        $statuses = ['Available', 'Pending', 'Adopted'];

        return view('admin.pets.create', compact('types', 'genders', 'statuses'));
    }

    /**
     * Store a newly created pet in storage.
     */
    public function store(StorePetRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pets', 'public');
            $validated['image_path'] = $imagePath;
        }

        Pet::create($validated);

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet profile created successfully!');
    }

    /**
     * Display the specified pet.
     */
    public function show(Pet $pet)
    {
        return redirect()->route('pets.show', $pet);
    }

    /**
     * Show the form for editing the specified pet.
     */
    public function edit(Pet $pet)
    {
        $types = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
        $genders = ['Male', 'Female', 'Unknown'];
        $statuses = ['Available', 'Pending', 'Adopted'];

        return view('admin.pets.edit', compact('pet', 'types', 'genders', 'statuses'));
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // Remove obsolete previous image if stored locally
            if ($pet->image_path && Storage::disk('public')->exists($pet->image_path)) {
                Storage::disk('public')->delete($pet->image_path);
            }

            $imagePath = $request->file('image')->store('pets', 'public');
            $validated['image_path'] = $imagePath;
        }

        $pet->update($validated);

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet profile updated successfully!');
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(Pet $pet)
    {
        // Delete associated image from storage
        if ($pet->image_path && Storage::disk('public')->exists($pet->image_path)) {
            Storage::disk('public')->delete($pet->image_path);
        }

        $pet->delete();

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet profile deleted successfully!');
    }
}

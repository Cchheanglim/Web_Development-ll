<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\ShelterProfile;

class PetController extends Controller
{
    /**
     * Display a single pet's detail page with adoption inquiry prompt.
     */
    public function show(Pet $pet)
    {
        $shelter = ShelterProfile::first();
        $similarPets = Pet::where('id', '!=', $pet->id)
            ->where('type', $pet->type)
            ->where('status', 'Available')
            ->take(3)
            ->get();

        return view('pets.show', compact('pet', 'shelter', 'similarPets'));
    }
}

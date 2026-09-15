<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\ShelterProfile;

class DashboardController extends Controller
{
    /**
     * Display the Owner management dashboard with key metrics.
     */
    public function index()
    {
        $totalPets = Pet::count();
        $availablePets = Pet::where('status', 'Available')->count();
        $pendingPets = Pet::where('status', 'Pending')->count();
        $adoptedPets = Pet::where('status', 'Adopted')->count();

        $recentPets = Pet::latest()->take(5)->get();
        $shelter = ShelterProfile::first();

        return view('admin.dashboard', compact(
            'totalPets',
            'availablePets',
            'pendingPets',
            'adoptedPets',
            'recentPets',
            'shelter'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\ShelterProfile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display public pet showcase with filters and shelter bio summary.
     */
    public function index(Request $request)
    {
        $shelter = ShelterProfile::first();

        $query = Pet::query();

        // Filter by pet type
        if ($request->filled('type') && $request->type !== 'All') {
            $query->where('type', $request->type);
        }

        // Filter by adoption status
        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $pets = $query->orderBy('status', 'asc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(12)
                      ->withQueryString();

        $petTypes = ['All', 'Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
        $statuses = ['All', 'Available', 'Pending', 'Adopted'];

        return view('home', compact('shelter', 'pets', 'petTypes', 'statuses'));
    }

    /**
     * Display full shelter about story and contact information.
     */
    public function about()
    {
        $shelter = ShelterProfile::first();
        return view('about', compact('shelter'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShelterProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShelterProfileController extends Controller
{
    /**
     * Show the form for editing the shelter profile.
     */
    public function edit()
    {
        $shelter = ShelterProfile::firstOrCreate(
            ['id' => 1],
            [
                'shelter_name' => 'Haven Paws Animal Adoption Center',
                'tagline' => 'Connecting Loving Families with Pets in Need',
                'bio' => 'Welcome to Haven Paws! We are dedicated to rescuing, rehabilitating, and rehoming abandoned and neglected companion animals.',
                'phone' => '(555) 234-5678',
                'email' => 'contact@havenpaws.org',
                'address' => '1244 Orchard Valley Rd, Maplewood, CA 90210',
                'opening_hours' => 'Mon - Sat: 10:00 AM - 6:00 PM | Sun: 12:00 PM - 5:00 PM',
            ]
        );

        return view('admin.shelter-profile.edit', compact('shelter'));
    }

    /**
     * Update the shelter profile.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'shelter_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'banner_image' => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:3072'],
        ]);

        $shelter = ShelterProfile::firstOrCreate(['id' => 1]);

        if ($request->hasFile('banner_image')) {
            if ($shelter->banner_image_path && Storage::disk('public')->exists($shelter->banner_image_path)) {
                Storage::disk('public')->delete($shelter->banner_image_path);
            }
            $validated['banner_image_path'] = $request->file('banner_image')->store('shelter', 'public');
        }

        $shelter->update($validated);

        return redirect()->route('admin.shelter-profile.edit')
            ->with('success', 'Shelter profile updated successfully!');
    }
}

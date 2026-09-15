<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\ShelterProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Single Default Owner Account
        User::updateOrCreate(
            ['email' => 'admin@adoptioncenter.com'],
            [
                'name' => 'Shelter Director',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Initial Shelter Profile
        ShelterProfile::updateOrCreate(
            ['id' => 1],
            [
                'shelter_name' => 'Haven Paws Animal Adoption Center',
                'tagline' => 'Where Every Pet Finds A Forever Family',
                'bio' => 'Founded in 2018, Haven Paws is a non-profit, cage-free rescue sanctuary committed to giving every companion animal a second chance at life. We provide comprehensive veterinary care, behavioral rehabilitation, and gentle socialization before connecting our rescues with loving families.',
                'phone' => '(555) 234-5678',
                'email' => 'adoptions@havenpaws.org',
                'address' => '742 Evergreen Terrace, Springfield, OR 97477',
                'opening_hours' => 'Tuesday – Sunday: 10:00 AM – 5:30 PM (Closed Mondays for shelter sanitization)',
                'banner_image_path' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1400&q=80',
            ]
        );

        // 3. Seed 6–8 Sample Pets Across Various Statuses and Types
        $samplePets = [
            [
                'name' => 'Milo',
                'type' => 'Dog',
                'breed' => 'Golden Retriever Mix',
                'age' => '2 years',
                'gender' => 'Male',
                'description' => 'Milo is an affectionate, energetic young pup who loves morning fetch, belly rubs, and learning new agility tricks. Great with children and fellow dogs.',
                'status' => 'Available',
                'image_path' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Luna',
                'type' => 'Cat',
                'breed' => 'Domestic Short Hair',
                'age' => '1 year',
                'gender' => 'Female',
                'description' => 'Luna is a quiet, purring cuddle enthusiast. She adores sunny windowsills, laser pointers, and curling up beside you while you read.',
                'status' => 'Available',
                'image_path' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Barnaby',
                'type' => 'Rabbit',
                'breed' => 'Holland Lop',
                'age' => '8 months',
                'gender' => 'Male',
                'description' => 'Barnaby is a gentle, velvet-soft bunny who does cute binkies when excited. He is litter-box trained and thrives on fresh parsley and timothy hay.',
                'status' => 'Pending',
                'image_path' => 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Pip & Squeak',
                'type' => 'Bird',
                'breed' => 'Cockatiel Pair',
                'age' => '3 years',
                'gender' => 'Unknown',
                'description' => 'A bonded pair of cheerful whistling cockatiels that must be adopted together. They love head scratches and sing sweet tunes in the morning.',
                'status' => 'Available',
                'image_path' => 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Copper',
                'type' => 'Dog',
                'breed' => 'Beagle Hound',
                'age' => '4 years',
                'gender' => 'Male',
                'description' => 'Copper has big soulful hazel eyes and an inquisitive nose. He enjoys leisurely scent walks, chew toys, and sleeping near warm blankets.',
                'status' => 'Available',
                'image_path' => 'https://images.unsplash.com/photo-1537151625747-768eb6cf92b2?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Willow',
                'type' => 'Cat',
                'breed' => 'Calico Longhair',
                'age' => '3 years',
                'gender' => 'Female',
                'description' => 'Willow is majestic, calm, and regal. She was adopted last week and is now living happily in her forever home in Portland!',
                'status' => 'Adopted',
                'image_path' => 'https://images.unsplash.com/photo-1573865526739-10659fec78a5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Rocky',
                'type' => 'Dog',
                'breed' => 'Australian Shepherd',
                'age' => '1.5 years',
                'gender' => 'Male',
                'description' => 'High-spirited working breed looking for an active hiking partner. Extremely smart, attentive, and loyal.',
                'status' => 'Pending',
                'image_path' => 'https://images.unsplash.com/photo-1517849845537-4d257902454a?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Ziggy',
                'type' => 'Other',
                'breed' => 'Guinea Pig Duo',
                'age' => '1 year',
                'gender' => 'Male',
                'description' => 'Ziggy and his brother make happy chirping sounds when opening the fridge! Very friendly, gentle with children, and easy to care for.',
                'status' => 'Available',
                'image_path' => 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($samplePets as $petData) {
            Pet::updateOrCreate(
                ['name' => $petData['name']],
                $petData
            );
        }
    }
}

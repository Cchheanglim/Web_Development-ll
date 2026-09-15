<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShelterProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'shelter_name',
        'tagline',
        'bio',
        'phone',
        'email',
        'address',
        'opening_hours',
        'banner_image_path',
    ];
}

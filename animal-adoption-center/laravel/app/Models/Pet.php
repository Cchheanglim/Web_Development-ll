<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'breed',
        'age',
        'gender',
        'description',
        'status',
        'image_path',
    ];

    /**
     * Get the public URL for the pet image.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path);
        }

        if ($this->image_path && (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://'))) {
            return $this->image_path;
        }

        return 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?auto=format&fit=crop&w=600&q=80';
    }

    /**
     * Scope query to filter by type and status.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['type'] ?? null, function ($query, $type) {
            if ($type !== 'All') {
                $query->where('type', $type);
            }
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            if ($status !== 'All') {
                $query->where('status', $status);
            }
        });

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('breed', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%');
            });
        });
    }
}

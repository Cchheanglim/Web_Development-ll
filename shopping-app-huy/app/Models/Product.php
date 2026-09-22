<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const CONDITION_NEW = 'new';
    public const CONDITION_USED = 'used';

    /** Fixed category list used for the browse-by-category UI. */
    public const CATEGORIES = [
        'Electronics',
        'Fashion',
        'Home & Living',
        'Sports & Outdoors',
        'Books & Study',
        'Vehicles',
        'Others',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'condition',
        'category',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // ---------- Relationships ----------

    /** The seller who owns this product. */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** All uploaded images for this product. */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /** Every chat message tied to this product. */
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    /** Reviews left by buyers who completed an order for this product. */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /** Seller-entered spec rows (Material -> Cotton, etc), shown as a details table. */
    public function specs()
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort_order');
    }

    /** Users who have favorited/saved this product. */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function getAverageRatingAttribute(): ?float
    {
        return $this->reviews->isEmpty() ? null : round($this->reviews->avg('rating'), 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews->count();
    }

    // ---------- Query scopes (used by the search/filter form) ----------

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopeCondition($query, ?string $condition)
    {
        if (! $condition) {
            return $query;
        }

        return $query->where('condition', $condition);
    }

    public function scopeCategory($query, ?string $category)
    {
        if (! $category) {
            return $query;
        }

        return $query->where('category', $category);
    }

    public function scopePriceBetween($query, ?float $min, ?float $max)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }

        if ($max !== null) {
            $query->where('price', '<=', $max);
        }

        return $query;
    }
}

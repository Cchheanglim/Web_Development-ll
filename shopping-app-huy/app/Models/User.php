<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Role constants keep role strings consistent across the app
     * instead of typing "buyer" / "seller" / "admin" by hand everywhere.
     */
    public const ROLE_BUYER = 'buyer';
    public const ROLE_SELLER = 'seller';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ---------- Relationships ----------

    /** Products this user is selling (only meaningful when role = seller). */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /** Chat messages this user sent as the buyer. */
    public function chatsAsBuyer()
    {
        return $this->hasMany(Chat::class, 'buyer_id');
    }

    /** Chat messages this user received as the seller. */
    public function chatsAsSeller()
    {
        return $this->hasMany(Chat::class, 'seller_id');
    }

    /** Products this user has favorited/saved (buyers use this). */
    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimestamps();
    }

    /** Items currently sitting in this user's cart. */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /** Card display-info (last 4 + brand) this user has chosen to save. */
    public function savedCards()
    {
        return $this->hasMany(SavedCard::class);
    }

    // ---------- Role helpers ----------

    public function isBuyer(): bool
    {
        return $this->role === self::ROLE_BUYER;
    }

    public function isSeller(): bool
    {
        return $this->role === self::ROLE_SELLER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
}

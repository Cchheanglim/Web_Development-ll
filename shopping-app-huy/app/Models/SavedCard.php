<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Deliberately holds only display-safe fields (brand, last 4 digits,
 * expiry). Never a full card number or CVV — see the migration comment
 * and CartController/OrderController for why.
 */
class SavedCard extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'brand', 'last4', 'expiry_month', 'expiry_year'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLabelAttribute(): string
    {
        return "{$this->brand} •••• {$this->last4} (exp " . str_pad($this->expiry_month, 2, '0', STR_PAD_LEFT) . "/{$this->expiry_year})";
    }
}

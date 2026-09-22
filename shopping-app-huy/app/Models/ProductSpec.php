<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'label', 'value', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

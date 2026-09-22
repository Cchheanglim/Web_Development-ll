<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    public const PAYMENT_COD = 'cod';
    public const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    public const PAYMENT_CARD = 'card';

    public const PAYMENT_METHODS = [
        self::PAYMENT_COD => 'Cash on delivery / pickup',
        self::PAYMENT_BANK_TRANSFER => 'Bank transfer',
        self::PAYMENT_CARD => 'Credit / debit card',
    ];

    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
        'quantity',
        'total_price',
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'payment_method',
        'card_brand',
        'card_last4',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /** The single review left for this order, if any. */
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}

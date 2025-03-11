<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrderProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_product';

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price',
        'is_refunded',
    ];

    /**
     * Get the order that owns the OrderProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Get the product that owns the OrderProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get all of the refunds for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'order_id', 'id');
    }

    public function sold(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->qty * $this->price,
        );
    }
}

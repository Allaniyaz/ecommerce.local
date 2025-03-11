<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batch_product';

    protected $fillable = [
        'batch_id',
        'product_id',
        'qty',
        'price',
        'remain_qty',
        'storage_id',
    ];

    /**
     * Get the batch that owns the BatchProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    /**
     * Get the product that owns the BatchProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the storage that owns the BatchProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id', 'id');
    }

    /**
     * Get all of the refunds for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'batch_id', 'id');
    }


    protected function payed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->qty * $this->price,
        );
    }

    public function sold_qty(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->qty - $this->remain_qty,
        );
    }
}

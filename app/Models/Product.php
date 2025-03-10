<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $with = ['subcategory', 'subcategory.category'];

    protected $fillable = [
        'name',
        'subcategory_id',
    ];

    /**
     * Get the subcategory that owns the BelongTo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id');
    }

    /**
     * The batches that belong to the Batch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_product', 'product_id', 'batch_id')
            ->withPivot([
                'qty',
                'price',
                'remain_qty',
                'storage_id',
            ]);
    }

    /**
     * The orders that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product', 'product_id', 'order_id')
            ->withPivot([
                'qty',
                'price',
                'is_refunded',
            ]);
    }
}

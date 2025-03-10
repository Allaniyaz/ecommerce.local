<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;



class Batch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'provider_id',
        'storage_id',
        // 'product_id',
        // 'price',
        // 'qty'
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id', 'id');
    }


    /**
     * The products that belong to the Batch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'batch_product', 'batch_id', 'product_id')
            ->withPivot([
                'qty',
                'price',
                'remain_qty',
                'storage_id',
            ]);
    }

    /**
     * Get all of the refunds for the Batch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function refunds(): HasManyThrough
    {
        return $this->hasManyThrough(Refund::class, BatchProduct::class);
    }
}

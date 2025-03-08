<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BatchProduct extends Model
{
    use HasFactory;

    protected $table = 'bacth_product';

    protected $fillable = [
        'batch_id',
        'product_id',
        'qty',
        'price',
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
}

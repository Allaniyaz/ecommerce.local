<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the subcategories for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class, 'category_id', 'id');
    }

    /**
     * Get all of the products for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, Subcategory::class);
    }
}

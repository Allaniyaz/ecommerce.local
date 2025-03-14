<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCategory extends Model
{
    use HasFactory;

    protected $table = 'provider_category';
    public $timestamps  = false;

    protected $fillable = [
        'provider_id',
        'category_id',
    ];
}

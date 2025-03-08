<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;


class Client extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    // protected $with = ['user'];

    protected $fillable = [
        'name',
        'info',
    ];

    // /**
    //  * Get the user associated with the Client
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  */
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }



}

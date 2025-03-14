<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Role users
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}

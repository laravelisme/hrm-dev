<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MRefreshToken extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TSp extends Model
{
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(\App\Models\TSpDetail::class, 't_sp_id');
    }
}

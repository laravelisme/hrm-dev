<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TSpDetail extends Model
{
    protected $guarded = ['id'];

    public function sp()
    {
        return $this->belongsTo(\App\Models\TSp::class, 't_sp_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MSaldoCuti extends Model
{
    protected $guarded = ['id'];

    public function jabatan()
    {
        return $this->belongsTo(\App\Models\MJabatan::class, 'm_jabatan_id');
    }
}

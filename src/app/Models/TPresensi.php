<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPresensi extends Model
{
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo(\App\Models\MKaryawan::class, 'm_karyawan_id');
    }
}

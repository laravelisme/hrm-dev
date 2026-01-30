<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TLembur extends Model
{
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo('App\Models\MKaryawan', 'm_karyawan_id', 'id');
    }
}

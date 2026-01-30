<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TIjin extends Model
{
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo('App\Models\MKaryawan', 'm_karyawan_id', 'id');
    }

    public function jenisIzin()
    {
        return $this->belongsTo('App\Models\MJenisIzin', 'm_jenis_izin_id', 'id');
    }
}

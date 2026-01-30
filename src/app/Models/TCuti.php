<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TCuti extends Model
{
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo('App\Models\MKaryawan', 'm_karyawan_id', 'id');
    }

    public function jenisCuti()
    {
        return $this->belongsTo('App\Models\MJenisCuti', 'm_jenis_cuti_id', 'id');
    }
}

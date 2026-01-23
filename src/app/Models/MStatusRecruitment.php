<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MStatusRecruitment extends Model
{
    protected $guarded = ['id'];

    public function calonKaryawan()
    {
        return $this->belongsTo(MCalonKaryawan::class, 'm_calon_karyawan_id');
    }
}

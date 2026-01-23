<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCalonKaryawan extends Model
{
    protected  $guarded = ['id'];

    public function statusRecruitments()
    {
        return $this->hasMany(MStatusRecruitment::class, 'm_calon_karyawan_id');
    }
    public function latestStatusRecruitment()
    {
        return $this->hasOne(MStatusRecruitment::class, 'm_calon_karyawan_id')->latestOfMany();
    }
}

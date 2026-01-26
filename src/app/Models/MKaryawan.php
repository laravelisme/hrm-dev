<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MKaryawan extends Model
{
    protected $guarded = ['id'];

    public function pendidikans() { return $this->hasMany(\App\Models\MKaryawanPendidikan::class, 'm_karyawan_id'); }
    public function pengalamanKerjas() { return $this->hasMany(\App\Models\MKaryawanPengalamanKerja::class, 'm_karyawan_id'); }
    public function organisasis() { return $this->hasMany(\App\Models\MKaryawanOrganisasi::class, 'm_karyawan_id'); }
    public function jabatans() { return $this->hasMany(\App\Models\MKaryawanJabatan::class, 'm_karyawan_id'); }
    public function bahasas() { return $this->hasMany(\App\Models\MKaryawanBahasa::class, 'm_karyawan_id'); }
    public function anaks() { return $this->hasMany(\App\Models\MKaryawanAnak::class, 'm_karyawan_id'); }
    public function saudaras() { return $this->hasMany(\App\Models\MKaryawanSaudara::class, 'm_karyawan_id'); }

}

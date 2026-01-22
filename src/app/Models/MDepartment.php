<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MDepartment extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_hr' => 'boolean',
    ];


    public function company()
    {
        return $this->belongsTo(MCompany::class, 'company_id');
    }
}

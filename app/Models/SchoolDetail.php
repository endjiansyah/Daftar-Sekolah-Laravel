<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolDetail extends Model
{
    //
    protected $fillable = [
        'user_id',
        'school_name',
        'school_address',
        'city_id',
        'graduation_year'
    ];

    // Tambahkan di dalam class SchoolDetail
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_name',
        'school_address',
        'city_id',
        'graduation_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
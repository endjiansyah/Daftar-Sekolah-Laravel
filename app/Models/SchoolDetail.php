<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolDetail extends Model
{
    //
    protected $fillable = ['user_id', 'school_name', 'school_address', 'city', 'graduation_year'];
}

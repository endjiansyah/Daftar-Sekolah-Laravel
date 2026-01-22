<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['id', 'name'];
    public $incrementing = false; // Karena kita pakai ID resmi wilayah (4 digit)
}
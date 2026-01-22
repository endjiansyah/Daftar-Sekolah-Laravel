<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentDetail extends Model
{
    //
    protected $fillable = ['user_id', 'parent_name', 'relationship', 'parent_phone', 'parent_email'];
}

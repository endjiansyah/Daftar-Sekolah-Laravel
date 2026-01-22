<?php

namespace App\Models;

use App\Enums\RegistrationStatus; //
use App\Enums\UserRole; //
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'username', 'email', 'password', 'full_name', 'nisn', 
        'gender', 'phone_number', 'pob', 'dob', 'address', 'role', 'status'
    ];

    protected $casts = [
        // Pastikan mengarah ke RegistrationStatus
        'status' => RegistrationStatus::class, 
        'role' => UserRole::class,
        'dob' => 'date',
    ];

    public function parentDetail() {
        return $this->hasOne(ParentDetail::class);
    }

    public function schoolDetail() {
        return $this->hasOne(SchoolDetail::class);
    }
}
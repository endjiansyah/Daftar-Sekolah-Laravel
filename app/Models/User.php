<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\RegistrationStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'full_name', 
        'nisn', 'gender', 'pob', 'dob', 'address', 'role', 'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'role' => UserRole::class,
        'status' => RegistrationStatus::class,
    ];

    // Relasi agar tidak error saat memanggil di Dashboard
    public function parentDetail() { return $this->hasOne(ParentDetail::class); }
    public function schoolDetail() { return $this->hasOne(SchoolDetail::class); }
}
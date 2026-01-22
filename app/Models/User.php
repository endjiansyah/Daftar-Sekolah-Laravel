<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\RegistrationStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'full_name', 'gender',
        'place_of_birth', 'date_of_birth', 'phone_number', 'address',
        'role', 'status',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class, // Supaya otomatis jadi Enum
            'status' => RegistrationStatus::class, // Supaya otomatis jadi Enum
        ];
    }

    // Relasi ke Data Orang Tua
    public function parentDetail(): HasOne
    {
        return $this->hasOne(ParentDetail::class);
    }

    // Relasi ke Data Sekolah
    public function schoolDetail(): HasOne
    {
        return $this->hasOne(SchoolDetail::class);
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\RegistrationStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'username' => 'admin',
            'email' => 'admin@sma.sch.id',
            'password' => Hash::make('semuasama123'),
            'full_name' => 'Administrator PPDB',
            'role' => UserRole::ADMIN,
            'status' => RegistrationStatus::TERVERIFIKASI,
        ]);

        // 2. Buat Akun Siswa Contoh (Opsional)
        User::create([
            'username' => 'siswasample',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('semuasama123'),
            'full_name' => 'Budi Santoso',
            'role' => UserRole::STUDENT,
            'status' => RegistrationStatus::DAFTAR,
        ]);
    }
}
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

        $this->call([
            CitySeeder::class, // Panggil seeder kota di sini
            // UserSeeder::class, (Jika ada)
        ]);
    }
}
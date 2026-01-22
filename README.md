<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Web Pendaftaran Sekolah (PPDB Online) - Technical Assessment

Project ini dikembangkan sebagai sistem manajemen pendaftaran siswa baru menggunakan **Laravel 12**. Sistem ini mengintegrasikan alur pendaftaran mandiri oleh siswa dan sistem verifikasi satu pintu oleh admin.

## 🚀 Fitur Utama & Arsitektur
- **Dual-Mode Registration**: Mendukung pendaftaran parsial (akun) maupun lengkap (biodata terintegrasi).
- **State Management (Enums)**: Menggunakan PHP Enums untuk integritas status pendaftaran (DAFTAR, TERVERIFIKASI, DITOLAK).
- **Database Transaction**: Memastikan atomisitas data saat pendaftaran siswa yang melibatkan 3 tabel (Users, Parent, School).
- **Admin Management**: Dashboard admin dengan fitur *real-time status update* (Verifikasi, Tolak, dan Reset Status).

## 📂 Pemetaan Struktur Data & Logika
Berikut adalah rincian file utama berdasarkan arsitektur **MVC (Model-View-Controller)**:

### 1. Database (Migrations & Seeders)
Menangani struktur tabel dan data awal untuk keperluan pengujian.
- `database/migrations/`: 
    - `users`: Tabel utama (Auth, NISN, Data Pribadi).
    - `parent_details`: Relasi 1:1 data orang tua/wali.
    - `school_details`: Relasi 1:1 data sekolah asal.
    - `cities`: Referensi data wilayah untuk tempat lahir dan lokasi sekolah.
- `database/seeders/DatabaseSeeder.php`: Menginisialisasi user Admin, Siswa contoh, dan data wilayah (Cities).

### 2. Models (Data Definitions)
Mengatur relasi Eloquent antar entitas.
- `app/Models/User.php`: Memiliki relasi `hasOne` ke `ParentDetail` dan `SchoolDetail`.
- `app/Enums/RegistrationStatus.php`: Definisi status pendaftaran berbasis String.

### 3. Controllers (Business Logic)
Pusat kendali alur data aplikasi.
- `app/Http/Controllers/Auth/RegisteredUserController.php`: Logika pendaftaran siswa menggunakan `DB::transaction`.
- `app/Http/Controllers/Admin/VerificationController.php`: Logika khusus admin untuk mengubah status pendaftaran (`updateStatus`).

### 4. Views (User Interface)
Menggunakan **Blade Templating** dan **Bootstrap 5**.
- `resources/views/admin/dashboard.blade.php`: Interface pengelolaan siswa dengan sistem Modal detail dan Quick Action.
- `resources/views/dashboard.blade.php`: Interface dashboard siswa (dinamis berdasarkan kelengkapan profil).



## 🛠️ Langkah Instalasi
1. Clone repositori ini.
2. Jalankan `composer install`.
3. Salin `.env.example` menjadi `.env` dan konfigurasi database.
4. Jalankan perintah:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
5. Jalankan server: php artisan serve.

## 🔑 Akun Akses (Seeded Data)
Role	Email	Password
Admin	admin@sma.sch.id	semuasama123

## 🛠 Panduan Perbaikan/Reset Data
Jika Anda melakukan testing dan ingin mengosongkan data atau mengulang status:

Reset Status: Gunakan tombol "Reset" pada Dashboard Admin untuk mengubah status TERVERIFIKASI atau DITOLAK kembali menjadi DAFTAR.

Refresh Database: Jalankan php artisan migrate:fresh --seed untuk mengembalikan database ke kondisi awal.
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Web Pendaftaran Sekolah (PPDB Online) - Technical Assessment

Project ini dikembangkan sebagai bagian dari penilaian teknis untuk sistem pendaftaran siswa baru menggunakan **Laravel 12**.

## 🚀 Fitur Utama & Arsitektur
- **Dual-Mode Registration**: 
  - *Partial*: Siswa dapat mendaftar hanya dengan data akun dasar.
  - *Complete*: Pendaftaran satu pintu yang mencakup data pribadi, orang tua, dan sekolah asal.
- **Dynamic Dashboard**: Dashboard yang mendeteksi kelengkapan profil. Jika data belum lengkap (akibat pendaftaran parsial), sistem akan menyediakan form integrasi untuk melengkapi data.
- **State Management dengan PHP Enums**: Menggunakan Enum (PHP 8.2+) untuk mengelola `RegistrationStatus` (DAFTAR, TERVERIFIKASI, DITOLAK) dan `UserRole` guna menjamin tipe data yang kuat (Type-safety).
- **Database Integrity**: Menggunakan `DB::transaction` pada proses pendaftaran guna memastikan data di 3 tabel berbeda tetap sinkron dan mencegah data sampah (partial data) jika terjadi error.
- **Admin Verification System**: Dashboard khusus admin untuk memproses pendaftaran, memantau kelengkapan data sekolah/orang tua, dan merubah status pendaftaran secara real-time.

## 🛠️ Langkah Instalasi
1. Clone repositori ini.
2. Jalankan `composer install`.
3. Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
4. Jalankan `php artisan key:generate`.
5. Jalankan migrasi beserta data awal (seeder):
   ```bash
   php artisan migrate --seed
6. Jalankan server lokal: php artisan serve.

🔑 Akun Akses
Admin: admin@sma.sch.id | Password: semuasama123
Siswa (Contoh): siswa@gmail.com | Password: semuasama123


## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

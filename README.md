# 🎓 Student Registration System (Laravel)

**Repository:**
[https://github.com/endjiansyah/Daftar-Sekolah-Laravel](https://github.com/endjiansyah/Daftar-Sekolah-Laravel)

Sistem pendaftaran siswa berbasis **Laravel 12** dengan arsitektur data terpisah (normalized), manajemen status berbasis **PHP Enum**, serta pengamanan data menggunakan **Database Transaction** dan **Role-based Access Control**.

---

## ✨ Fitur Utama

* Registrasi siswa terstruktur
* Relasi data terpisah (User, Orang Tua, Sekolah)
* Verifikasi pendaftaran oleh Admin
* Status pendaftaran berbasis **PHP Enum**
* Master data kota (Seeder)
* Email notifikasi (SMTP Mailtrap)
* Middleware custom berbasis role
* Konsisten dengan **Database Transaction**

---

## 🧰 Tech Stack

* **Laravel 12**
* **PHP 8.3+**
* **MySQL / MariaDB**
* **Blade Template Engine**
* **PHP Enums**
* **Mailtrap (SMTP Testing)**

---

## 🏗️ Arsitektur Data

### Tabel Utama

* **users** – akun, NISN, role, status pendaftaran
* **parent_details** – data orang tua / wali
* **school_details** – data akademik & asal sekolah
* **cities** – master data kota

### Relasi

* `User` **hasOne** `ParentDetail`
* `User` **hasOne** `SchoolDetail`
* `City` direferensikan oleh `users` & `school_details`

---

## 🧩 ERD (Entity Relationship Diagram)

```
┌────────────┐        ┌──────────────────┐
│   cities   │        │      users       │
├────────────┤        ├──────────────────┤
│ id         │◄───────┤ city_id          │
│ name       │        │ id               │
└────────────┘        │ name             │
                      │ email            │
                      │ nisn             │
                      │ role             │
                      │ status           │
                      └───────┬──────────┘
                              │
               ┌──────────────┴──────────────┐
               │                              │
┌────────────────────────┐     ┌────────────────────────┐
│     parent_details     │     │     school_details     │
├────────────────────────┤     ├────────────────────────┤
│ id                     │     │ id                     │
│ user_id                │◄────┤ user_id                │
│ father_name            │     │ school_name            │
│ mother_name            │     │ graduation_year        │
│ phone                  │     │ city_id                │
└────────────────────────┘     └────────────────────────┘
```

---

## 🔄 Status Pendaftaran

Dikelola menggunakan **PHP Enum**

**File:** `app/Enums/RegistrationStatus.php`

| Status          | Keterangan                     |
| --------------- | ------------------------------ |
| `DAFTAR`        | Status awal setelah registrasi |
| `TERVERIFIKASI` | Disetujui oleh admin           |
| `DITOLAK`       | Data tidak valid               |

---

## 🔐 Alur Registrasi

Pendaftaran dijalankan dalam **Database Transaction** untuk menjaga konsistensi data:

```php
DB::transaction(function () {
    // Create User
    // Create Parent Detail
    // Create School Detail
});
```

Jika salah satu proses gagal, seluruh perubahan akan di-*rollback*.

---

## 🧭 Penjelasan Komponen Utama

### Enums (`app/Enums`)

* `RegistrationStatus.php`
* `UserRole.php`

### Controllers (`app/Http/Controllers`)

* `RegistrationController.php`
* `Admin/VerificationController.php`
* `AuthController.php`

### Models (`app/Models`)

* `User.php`
* `ParentDetail.php`
* `SchoolDetail.php`
* `City.php`

### Mail (`app/Mail`)

* `WelcomeRegistrationMail.php` – email otomatis setelah pendaftaran

### Middleware (`app/Http/Middleware`)

* `RoleMiddleware.php` – pembatasan akses berbasis role

---

## 🛣️ Peta Routing (Endpoints)

### A. Guest (Publik)

| URL         | Method | Deskripsi          |
| ----------- | ------ | ------------------ |
| `/`         | GET    | Landing page       |
| `/register` | GET    | Form pendaftaran   |
| `/register` | POST   | Proses pendaftaran |
| `/login`    | GET    | Form login         |
| `/login`    | POST   | Proses login       |

### B. Student

| URL                 | Method | Deskripsi       |
| ------------------- | ------ | --------------- |
| `/dashboard`        | GET    | Dashboard siswa |
| `/dashboard/edit`   | GET    | Edit biodata    |
| `/profile/update`   | PUT    | Update profil   |
| `/profile/password` | PUT    | Update password |

### C. Admin (`/admin`)

| URL                             | Method | Deskripsi        |
| ------------------------------- | ------ | ---------------- |
| `/admin/dashboard`              | GET    | Daftar pendaftar |
| `/admin/verify/{user}/{status}` | PATCH  | Update status    |

### D. Authenticated

| URL       | Method | Deskripsi |
| --------- | ------ | --------- |
| `/logout` | POST   | Logout    |

**Keamanan:**
Menggunakan middleware `role:admin` dan `role:student`.

---

## 🗄️ Database Migration

* `create_users_table.php`
* `create_cities_table.php`
* `create_parent_details_table.php`
* `create_school_details_table.php`
* `create_cache_table.php`
* `create_jobs_table.php`

---

## 🌱 Database Seeder

* `CitySeeder.php` – master data kota Indonesia
* `DatabaseSeeder.php` – akun admin & data awal

### 🌍 Sumber Data Kota

Dataset kota diambil dari repository open-source berikut:
[https://github.com/yusufsyaifudin/wilayah-indonesia](https://github.com/yusufsyaifudin/wilayah-indonesia)
(File: `list_of_area/regencies.json`)

---

## 📧 Email & SMTP (Mailtrap)

Project ini menggunakan **Mailtrap** untuk testing email di environment development.

### Contoh konfigurasi `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin@sma.sch.id"
MAIL_FROM_NAME="Panitia PPDB"
```

---

## 🛠️ Instalasi

```bash
git clone https://github.com/endjiansyah/Daftar-Sekolah-Laravel
cd Daftar-Sekolah-Laravel

composer install
npm install && npm run dev

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve
```

---

## 🔑 Akun Admin Default

| Email                                       | Password     |
| ------------------------------------------- | ------------ |
| [admin@sma.sch.id](mailto:admin@sma.sch.id) | semuasama123 |

---

## 🙏 Credits & Acknowledgements

* **Laravel Framework**
  [https://laravel.com](https://laravel.com)

* **Wilayah Indonesia Dataset**
  [https://github.com/yusufsyaifudin/wilayah-indonesia](https://github.com/yusufsyaifudin/wilayah-indonesia)

* **Author**
  Endjiansyah
  [https://github.com/endjiansyah](https://github.com/endjiansyah)

---

## 📜 License

This project is open-sourced under the **MIT License**.

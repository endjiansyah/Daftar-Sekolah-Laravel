# 🎓 Student Registration System (Laravel)

Sistem pendaftaran siswa berbasis **Laravel** dengan arsitektur data terpisah (normalized), menggunakan **Enum** untuk status pendaftaran dan **Database Transaction** untuk menjaga konsistensi data.

---

## ✨ Fitur Utama

* Registrasi siswa terstruktur
* Relasi data terpisah (User, Orang Tua, Sekolah)
* Verifikasi pendaftaran oleh Admin
* Status pendaftaran berbasis **PHP Enum**
* Master data kota
* Aman dengan **Database Transaction**

---

## 🏗️ Arsitektur Data

### Tabel Utama

* **users** → akun & status pendaftaran
* **parent_details** → data orang tua / wali
* **school_details** → data akademik & asal sekolah
* **cities** → master data kota

### Relasi

* `User` **hasOne** `ParentDetail`
* `User` **hasOne** `SchoolDetail`
* `City` digunakan oleh `users` & `school_details`

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

Pendaftaran dijalankan dalam **Database Transaction**:

```php
DB::transaction(function () {
    // Create User
    // Create Parent Detail
    // Create School Detail
});
```

Jika salah satu proses gagal, seluruh data akan di-*rollback*.

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

## 📧 Email & SMTP (Mailtrap)

Project ini menggunakan **Mailtrap** sebagai **SMTP testing** untuk pengiriman email (development environment), seperti:

* Notifikasi pendaftaran
* Email verifikasi / status (jika diaktifkan)

Mailtrap memungkinkan pengujian email **tanpa benar-benar mengirim ke email asli**.

---

## ⚙️ Konfigurasi Mailtrap

### 1. Buat Akun Mailtrap

Daftar di:
[https://mailtrap.io](https://mailtrap.io)

Setelah login, buat **Inbox** baru.

---

### 2. Ambil Kredensial SMTP

Masuk ke Inbox → pilih **SMTP Settings** → **Laravel**
Salin konfigurasi SMTP yang disediakan Mailtrap.

---

### 3. Konfigurasi `.env`

Sesuaikan konfigurasi email di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@sma.sch.id"
MAIL_FROM_NAME="Sistem Pendaftaran Siswa"
```

> Ganti `MAIL_USERNAME` dan `MAIL_PASSWORD` sesuai dengan data dari Mailtrap.

---

### 4. Testing Email

Jalankan server:

```bash
php artisan serve
```

Setiap email yang dikirim aplikasi akan muncul di **Inbox Mailtrap**, bukan ke email asli.

---

## 📝 Catatan

* Mailtrap **hanya digunakan untuk development/testing**
* Untuk production, silakan ganti SMTP dengan:

  * Gmail
  * Outlook
  * AWS SES
  * SendGrid
  * dll

---

## 📂 Struktur Penting

### Controller

* `RegisteredUserController` → registrasi siswa
* `VerificationController` → verifikasi admin

### Model & Enum

* `User.php`
* `RegistrationStatus.php`

### View

* `admin/dashboard.blade.php`
* `dashboard.blade.php`

---

## 🔑 Akun Admin Default

| Email                                       | Password     |
| ------------------------------------------- | ------------ |
| [admin@sma.sch.id]                          | semuasama123 |

---

## 🌍 Sumber Data Kota (Seeder)

Data kota yang digunakan pada tabel **`cities`** diambil dari repository open data wilayah Indonesia berikut:

**Sumber:**
[https://raw.githubusercontent.com/yusufsyaifudin/wilayah-indonesia/master/data/list_of_area/regencies.json](https://raw.githubusercontent.com/yusufsyaifudin/wilayah-indonesia/master/data/list_of_area/regencies.json)

Data tersebut digunakan sebagai **master data kota** untuk:

* Tempat lahir siswa
* Lokasi sekolah asal

Data diproses melalui **database seeder** untuk mempermudah inisialisasi data awal dan menjaga konsistensi referensi wilayah.

---

## 🧰 Tech Stack

Project ini dibangun menggunakan teknologi berikut:

* **Laravel 12** – PHP Framework utama
* **PHP 8.3+**
* **MySQL / MariaDB**
* **Blade Template Engine**
* **PHP Enums** – manajemen status pendaftaran
* **Database Transaction** – konsistensi data

---

## 🙏 Credits & Acknowledgements

* **Laravel Framework**
  Framework PHP modern untuk pengembangan aplikasi web.
  [https://laravel.com](https://laravel.com)

* **Wilayah Indonesia Dataset**
  Digunakan sebagai sumber master data kota pada seeder.
  Data diambil dari repository open-source berikut:
  [https://github.com/yusufsyaifudin/wilayah-indonesia](https://github.com/yusufsyaifudin/wilayah-indonesia)

* **Author**
  Project ini dikembangkan oleh **Endjiansyah**
  Repository: [https://github.com/endjiansyah/Daftar-Sekolah-Laravel](https://github.com/endjiansyah/Daftar-Sekolah-Laravel)

---

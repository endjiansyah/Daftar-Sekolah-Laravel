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
git clone [url-repository]
cd [project-folder]

composer install
npm install && npm run dev

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve
```

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


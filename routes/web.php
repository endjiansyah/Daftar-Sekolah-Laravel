<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\AuthController; // Pastikan ini sudah di-import

/*
|--------------------------------------------------------------------------
| Web Routes - PPDB Online System
|--------------------------------------------------------------------------
*/

// --- 1. AKSES PUBLIK (GUEST) ---
Route::get('/', function () {
    if (Auth::check()) {
        // Cek role untuk menentukan dashboard mana yang dituju
        $role = auth()->user()->role;
        $roleValue = is_object($role) ? $role->value : $role;

        return $roleValue === 'admin' 
            ? redirect()->intended('/admin/dashboard') 
            : redirect()->intended('/dashboard');
    }
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    // Registrasi
    Route::get('/register', [RegistrationController::class, 'create'])->name('register');
    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

    // Login (Menggunakan AuthController)
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});


// --- 2. AKSES TERAUTENTIKASI (HARUS LOGIN) ---
Route::middleware('auth')->group(function () {

    // Logout (Menggunakan AuthController)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- FITUR UMUM ---
    Route::put('/profile/password', [RegistrationController::class, 'updatePassword'])->name('profile.password');

    // --- AREA SISWA (Role: Student) ---
    Route::middleware('role:student')->group(function () {
        Route::get('/dashboard', function () {
            Auth::user()->load(['parentDetail', 'schoolDetail.city', 'birthCity']);
            return view('dashboard');
        })->name('dashboard');

        Route::get('/dashboard/edit', [RegistrationController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [RegistrationController::class, 'updateProfile'])->name('profile.update');
    });

    // --- AREA ADMIN (Role: Admin) ---
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [VerificationController::class, 'index'])->name('admin.dashboard');
        Route::patch('/verify/{user}/{status}', [VerificationController::class, 'updateStatus'])->name('admin.verify');
    });
});
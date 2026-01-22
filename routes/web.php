<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes - PPDB Online System
|--------------------------------------------------------------------------
*/

// --- AKSES PUBLIK (GUEST) ---
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    // Registrasi
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

    // Login
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', function (Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect otomatis berdasarkan role (Menggunakan Enum value jika perlu)
            $role = auth()->user()->role;
            $roleValue = is_object($role) ? $role->value : $role;

            return $roleValue === 'admin'
                ? redirect()->intended('/admin/dashboard')
                : redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    })->name('login.post');
});


// --- AKSES TERAUTENTIKASI (HARUS LOGIN) ---
Route::middleware('auth')->group(function () {

    /**
     * --- AREA SISWA (Role: Student) ---
     * Kelompok route yang hanya bisa diakses oleh user dengan role student.
     */
    Route::middleware('role:student')->group(function () {

        // Menampilkan Dashboard Utama (Halaman Monitoring/Overview)
        Route::get('/dashboard', function () {
            $user = Auth::user()->load(['parentDetail', 'schoolDetail']);
            return view('dashboard', compact('user'));
        })->name('dashboard');

        // Menampilkan Form untuk Lengkapi atau Edit Data
        Route::get('/dashboard/edit', [RegistrationController::class, 'edit'])->name('student.edit');

        // Memproses pengiriman data profil, ortu, dan sekolah (POST)
        Route::post('/profile/update', [RegistrationController::class, 'updateProfile'])->name('profile.update');

        // Memproses perubahan password (PUT)
        Route::put('/profile/password', [RegistrationController::class, 'updatePassword'])->name('profile.password');
    });

    // 2. AREA ADMIN (Role: Admin)
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [VerificationController::class, 'index'])->name('admin.dashboard');
        Route::patch('/verify/{user}/{status}', [VerificationController::class, 'updateStatus'])->name('admin.verify');
    });

    // 3. LOGOUT
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

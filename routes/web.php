<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/dashboard', function () {
    $user = Auth::user()->load(['parentDetail', 'schoolDetail']); // Eager loading agar tidak berat
    return view('dashboard', compact('user'));
})->middleware(['auth'])->name('dashboard');

Route::post('/complete-profile', [RegistrationController::class, 'completeProfile'])->name('profile.complete');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/register');
})->name('logout');

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
    $user = Auth::user();
    return "Selamat datang, " . $user->full_name . ". Status pendaftaran Anda: " . $user->status->value;
})->middleware(['auth'])->name('dashboard');
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Registration routes

Route::get('/register/2fa', [App\Http\Controllers\Auth\TwoFactorRegister::class, 'showQRCodeForm'])->name('register.2fa');
Route::post('/register/2fa', [App\Http\Controllers\Auth\TwoFactorRegister::class, 'verifyTwoFactorAuth'])->name('register.verify.2fa');

// Login routes
Route::get('/verify-2fa', [App\Http\Controllers\Auth\TwoFactorRegister::class, 'showTwoFactorForm'])->name('verify.2fa.form');
Route::post('/verify-2fa', [App\Http\Controllers\Auth\TwoFactorRegister::class, 'verifyTwoFactor'])->name('verify.2fa');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

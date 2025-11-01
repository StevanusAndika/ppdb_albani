<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Dashboard\DashboardController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Password Reset Routes dengan OTP WhatsApp
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');

// Auth action routes
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Socialite Registration Route
Route::post('/socialite/register', [AuthController::class, 'handleSocialiteRegistration'])
    ->name('socialite.register.post');

// Password Reset Action Routes dengan OTP
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp'])
    ->name('password.verify.otp');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->name('password.update');
Route::post('/resend-otp', [PasswordResetController::class, 'resendOtp'])
    ->name('password.resend.otp');

// Socialite Routes
Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProvideCallback'])
    ->name('socialite.callback');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== MIDDLEWARE ROUTES ====================

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
});

// Santri Routes
Route::middleware(['auth', 'santri'])->prefix('santri')->name('santri.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'santriDashboard'])->name('dashboard');
});

// General Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

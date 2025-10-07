<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;



// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes untuk guest
Route::middleware('guest')->group(function () {
    // View routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

    // Auth action routes
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Socialite Routes
Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProvideCallback'])
    ->name('socialite.callback');

// Logout route (harus auth)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== MIDDLEWARE GROUP ====================

// Admin Group - menggunakan middleware group 'admin'
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
});

// Santri Group - menggunakan middleware group 'santri'
Route::middleware('santri')->prefix('santri')->name('santri.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'santriDashboard'])->name('dashboard');
});

// General Authenticated Routes (tanpa role specific)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

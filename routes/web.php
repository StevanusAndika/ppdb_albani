<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\BillingPackage\BillingPackageController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Password Reset Routes
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

// Password Reset Action Routes
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

    // Billing Packages Routes
    Route::prefix('billing-packages')->name('billing-packages.')->group(function () {
        Route::get('/', [BillingPackageController::class, 'index'])->name('index');
        Route::get('/create', [BillingPackageController::class, 'create'])->name('create');
        Route::post('/', [BillingPackageController::class, 'store'])->name('store');
        Route::get('/{billingPackage}', [BillingPackageController::class, 'show'])->name('show');
        Route::get('/{billingPackage}/edit', [BillingPackageController::class, 'edit'])->name('edit');
        Route::put('/{billingPackage}', [BillingPackageController::class, 'update'])->name('update');
        Route::delete('/{billingPackage}', [BillingPackageController::class, 'destroy'])->name('destroy');
        Route::post('/{billingPackage}/toggle-status', [BillingPackageController::class, 'toggleStatus'])->name('toggle-status');
    });
});

// Santri Routes
Route::middleware(['auth', 'santri'])->prefix('santri')->name('santri.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'santriDashboard'])->name('dashboard');
});

// General Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

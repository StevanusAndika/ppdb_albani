<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\PackageSetting\PackageController;
use App\Http\Controllers\PriceSetting\PriceController;
use App\Http\Controllers\adminsetting\SettingController;
use App\Http\Controllers\KontenSetting\KontenController;
use App\Http\Controllers\ManageUser\ManageUserController;
use App\Http\Controllers\WelcomeController;

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // ==================== CONTENT MANAGEMENT ROUTES ====================
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/', [KontenController::class, 'index'])->name('index');
        Route::put('/update', [KontenController::class, 'update'])->name('update');
        Route::delete('/file/{fileType}', [KontenController::class, 'deleteFile'])->name('file.delete');
        Route::post('/program/add', [KontenController::class, 'addProgram'])->name('program.add');
        Route::delete('/program/{index}', [KontenController::class, 'deleteProgram'])->name('program.delete');
    });

    // ==================== SETTINGS ROUTES ====================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
        Route::post('/google/disconnect', [SettingController::class, 'disconnectGoogle'])->name('google.disconnect');
    });

    // ==================== BILLING PACKAGES ROUTES ====================
    Route::prefix('billing')->name('billing.')->group(function () {
        // Packages Routes
        Route::prefix('packages')->name('packages.')->group(function () {
            Route::get('/', [PackageController::class, 'index'])->name('index');
            Route::get('/create', [PackageController::class, 'create'])->name('create');
            Route::post('/', [PackageController::class, 'store'])->name('store');
            Route::get('/{package}/edit', [PackageController::class, 'edit'])->name('edit');
            Route::put('/{package}', [PackageController::class, 'update'])->name('update');
            Route::delete('/{package}', [PackageController::class, 'destroy'])->name('destroy');
            Route::post('/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('toggle-status');

            // Prices Routes
            Route::prefix('{package}/prices')->name('prices.')->group(function () {
                Route::get('/', [PriceController::class, 'index'])->name('index');
                Route::get('/create', [PriceController::class, 'create'])->name('create');
                Route::post('/', [PriceController::class, 'store'])->name('store');
                Route::get('/{price}/edit', [PriceController::class, 'edit'])->name('edit');
                Route::put('/{price}', [PriceController::class, 'update'])->name('update');
                Route::delete('/{price}', [PriceController::class, 'destroy'])->name('destroy');
                Route::post('/{price}/toggle-status', [PriceController::class, 'toggleStatus'])->name('toggle-status');
                Route::post('/reorder', [PriceController::class, 'reorder'])->name('reorder');
            });
        });
    });

    // Tambahkan dalam group admin
    Route::prefix('manage-users')->name('manage-users.')->group(function () {
    Route::get('/', [ManageUserController::class, 'index'])->name('index');
    Route::get('/create', [ManageUserController::class, 'create'])->name('create');
    Route::post('/', [ManageUserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [ManageUserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [ManageUserController::class, 'update'])->name('update');
    Route::delete('/{user}', [ManageUserController::class, 'destroy'])->name('destroy');
    Route::post('/{user}/toggle-status', [ManageUserController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/generate-password', [ManageUserController::class, 'generatePassword'])->name('generate-password');
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

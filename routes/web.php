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
use App\Http\Controllers\Biodata\BiodataController;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Admin\RegistrationController;

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Auth Routes
Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');
Route::post('/check-phone', [AuthController::class, 'checkPhoneNumber'])->name('check.phone');
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

    // Content Management
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/', [KontenController::class, 'index'])->name('index');
        Route::put('/update', [KontenController::class, 'update'])->name('update');
        Route::delete('/file/{fileType}', [KontenController::class, 'deleteFile'])->name('file.delete');
        Route::post('/program/add', [KontenController::class, 'addProgram'])->name('program.add');
        Route::delete('/program/{index}', [KontenController::class, 'deleteProgram'])->name('program.delete');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
        Route::post('/google/disconnect', [SettingController::class, 'disconnectGoogle'])->name('google.disconnect');
    });

    // Billing Packages
    Route::prefix('billing')->name('billing.')->group(function () {
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

    // Manage Users
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

    // Registration Management
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/', [RegistrationController::class, 'index'])->name('index');
        Route::get('/{registration}', [RegistrationController::class, 'show'])->name('show');
        Route::put('/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('update-status');
        Route::post('/{registration}/send-notification', [RegistrationController::class, 'sendNotification'])->name('send-notification');
    });
});

// Santri Routes
Route::middleware(['auth', 'santri'])->prefix('santri')->name('santri.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'santriDashboard'])->name('dashboard');

    // Biodata Routes
    Route::prefix('biodata')->name('biodata.')->group(function () {
        Route::get('/', [BiodataController::class, 'index'])->name('index');
        Route::post('/', [BiodataController::class, 'store'])->name('store');
        Route::get('/package/{package}/prices', [BiodataController::class, 'getPackagePrices'])->name('package.prices');
    });

    // Document Routes - COMPLETE VERSION
   // Dalam routes/web.php - bagian santri documents
    Route::prefix('documents')->name('documents.')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('index');
    Route::post('/upload/{documentType}', [DocumentController::class, 'upload'])->name('upload');
    Route::delete('/delete/{documentType}', [DocumentController::class, 'delete'])->name('delete');
    Route::get('/file/{documentType}', [DocumentController::class, 'getFile'])->name('file');
    Route::get('/download/{documentType}', [DocumentController::class, 'download'])->name('download');
    Route::post('/complete', [DocumentController::class, 'completeRegistration'])->name('complete');
    Route::get('/progress', [DocumentController::class, 'getProgress'])->name('progress');
    Route::get('/test-image', [DocumentController::class, 'testImage'])->name('test-image');
        Route::get('/download-all', [DocumentController::class, 'downloadAll'])->name('download-all');

});
});

// General Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

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
use App\Http\Controllers\Admin\UserBiodataController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\FAQ\FAQController;
use App\Http\Controllers\Kegiatan\KegiatanController;
use App\Http\Controllers\Announcement\AnnouncementController;
use App\Http\Controllers\usersetting\SettingController as UserSettingController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\Quota\QuotaController;
use App\Http\Controllers\Announcement\SeleksiAnnoucementController;
use App\Http\Controllers\CameraTestController;
use App\Http\Controllers\Admin\QRcodeScannerController;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\Admin\LandingContentController;

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa');

Route::get('/camera-test', [CameraTestController::class, 'index'])->name('camera-test.index');
Route::post('/camera-test', [CameraTestController::class, 'store'])->name('camera-test.store');

// QR Code Routes
Route::prefix('barcode')->name('barcode.')->group(function () {
    Route::get('/{id_pendaftaran}', [BarcodeController::class, 'show'])->name('show');
    Route::get('/{id_pendaftaran}/download', [BarcodeController::class, 'download'])->name('download');
    Route::get('/{id_pendaftaran}/image', [BarcodeController::class, 'getQrCode'])->name('image');
    Route::get('/scan/{id_pendaftaran}', [BarcodeController::class, 'scan'])->name('scan');
    Route::get('/generate/all', [BarcodeController::class, 'generateAll'])->name('generate.all');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');
    Route::post('/check-phone', [AuthController::class, 'checkPhoneNumber'])->name('check.phone');
    Route::get('/check-quota', [AuthController::class, 'checkQuota'])->name('auth.checkQuota');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.verify.otp');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    Route::post('/resend-otp', [PasswordResetController::class, 'resendOtp'])->name('password.resend.otp');
    Route::post('/check-password-cooldown', [PasswordResetController::class, 'checkCooldown'])->name('password.check.cooldown');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('socialite.redirect');
    Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProvideCallback'])->name('socialite.callback');
});

// Socialite Registration Route
Route::post('/socialite/register', [AuthController::class, 'handleSocialiteRegistration'])->name('socialite.register.post');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== MIDDLEWARE ROUTES ====================

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // camera untuk scan barcode qrcode-scanner
    Route::get('/qrcode-scanner', [QRcodeScannerController::class, 'index'])->name('qrcode-scanner.index');
    Route::post('/qrcode-scanner', [QRcodeScannerController::class, 'store'])->name('qrcode-scanner.store');
    
    // Landing Content Management Routes
    Route::prefix('/landing')->name('landing.')->group(function () {
        Route::get('/', [LandingContentController::class, 'index'])->name('index');
        Route::post('/update', [LandingContentController::class, 'update'])->name('update');
    });

 

    // Announcement Routes (Pengumuman Kelulusan)
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('index');
        Route::post('/send-individual/{registrationId}', [AnnouncementController::class, 'sendIndividualMessage'])->name('send-individual');
        Route::post('/send-bulk', [AnnouncementController::class, 'sendBulkMessage'])->name('send-bulk');
        Route::post('/send-all-santri', [AnnouncementController::class, 'sendToAllSantri'])->name('send-all-santri'); // TAMBAHKAN INI
        Route::post('/update-status-seleksi/{registrationId}', [AnnouncementController::class, 'updateStatusSeleksi'])->name('update-status-seleksi');
    });

    // Seleksi Announcement Routes (Undangan Tes Seleksi)
    Route::prefix('seleksi-announcements')->name('seleksi-announcements.')->group(function () {
        Route::get('/', [SeleksiAnnoucementController::class, 'index'])->name('index');
        Route::post('/send-individual/{registrationId}', [SeleksiAnnoucementController::class, 'sendIndividualSeleksi'])->name('send-individual');
        Route::post('/send-bulk', [SeleksiAnnoucementController::class, 'sendBulkSeleksi'])->name('send-bulk');
        Route::post('/send-all-santri', [SeleksiAnnoucementController::class, 'sendToAllSantriSeleksi'])->name('send-all-santri');
    });

    // Quota Management Routes
    Route::prefix('quota')->name('quota.')->group(function () {
        Route::get('/', [QuotaController::class, 'index'])->name('index');
        Route::post('/', [QuotaController::class, 'store'])->name('store');
        Route::put('/{quota}', [QuotaController::class, 'update'])->name('update');
        Route::delete('/{quota}', [QuotaController::class, 'destroy'])->name('destroy');
        Route::post('/{quota}/activate', [QuotaController::class, 'activate'])->name('activate');
        Route::post('/{quota}/reset', [QuotaController::class, 'reset'])->name('reset');
        Route::get('/check-availability', [QuotaController::class, 'checkAvailability'])->name('check-availability');
    });

    // Content Management
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/', [KontenController::class, 'index'])->name('index');
        Route::put('/update', [KontenController::class, 'update'])->name('update');
        Route::delete('/file/{fileType}', [KontenController::class, 'deleteFile'])->name('file.delete');
        Route::post('/program/add', [KontenController::class, 'addProgram'])->name('program.add');
        Route::delete('/program/{index}', [KontenController::class, 'deleteProgram'])->name('program.delete');
        Route::post('/faq/add', [KontenController::class, 'addFaq'])->name('faq.add');
        Route::delete('/faq/{index}', [KontenController::class, 'deleteFaq'])->name('faq.delete');
        Route::post('/kegiatan/add', [KontenController::class, 'addKegiatan'])->name('kegiatan.add');
        Route::delete('/kegiatan/{index}', [KontenController::class, 'deleteKegiatan'])->name('kegiatan.delete');
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

        // Biodata Routes
        Route::prefix('{user}/biodata')->name('biodata.')->group(function () {
            Route::get('/', [UserBiodataController::class, 'show'])->name('show');
            Route::get('/edit-documents', [UserBiodataController::class, 'editDocuments'])->name('edit-documents');
            Route::get('/edit-registration', [UserBiodataController::class, 'editRegistration'])->name('edit-registration');
            Route::post('/save-registration', [UserBiodataController::class, 'saveRegistration'])->name('save-registration');
            Route::post('/upload-document', [UserBiodataController::class, 'uploadDocument'])->name('upload-document');
            Route::delete('/document/{documentType}', [UserBiodataController::class, 'deleteDocument'])->name('delete-document');
            Route::get('/document/{documentType}/download', [UserBiodataController::class, 'downloadDocument'])->name('download-document');
        });
    });

    // Registration Management
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/', [RegistrationController::class, 'index'])->name('index');
        Route::get('/{registration}', [RegistrationController::class, 'show'])->name('show');

        // Update Status Routes
        Route::put('/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('update-status');
        Route::post('/{registration}/status-seleksi', [RegistrationController::class, 'updateStatusSeleksi'])->name('update-status-seleksi');
        Route::post('/{registration}/update-documents-rejection', [RegistrationController::class, 'updateDocumentsWithRejection'])->name('update-documents-rejection');

        // Notification Routes
        Route::post('/{registration}/send-notification', [RegistrationController::class, 'sendNotification'])->name('send-notification');

        // Document Management Routes
        Route::post('/{registration}/upload-document', [RegistrationController::class, 'uploadDocument'])->name('upload-document');
        Route::put('/{registration}/admin-notes', [RegistrationController::class, 'updateAdminNotes'])->name('update-admin-notes');
        Route::post('/{registration}/reset-pending', [RegistrationController::class, 'resetToPending'])->name('reset-pending');

        // Document View/Download Routes
        Route::get('/{registration}/document/{documentType}/view', [RegistrationController::class, 'viewDocument'])->name('view-document');
        Route::get('/{registration}/document/{documentType}/download', [RegistrationController::class, 'downloadDocument'])->name('download-document');
        Route::get('/{registration}/document/{documentType}/url', [RegistrationController::class, 'getDocumentUrl'])->name('get-document-url');
        Route::get('/{registration}/debug-documents', [RegistrationController::class, 'debugDocumentPaths'])->name('debug-documents');
    });

    // Transaction Management - UPDATE DENGAN ROUTES INVOICE
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/search', [AdminPaymentController::class, 'search'])->name('search');
        Route::get('/{payment}', [AdminPaymentController::class, 'show'])->name('show');
        Route::put('/{payment}/status', [AdminPaymentController::class, 'updateStatus'])->name('update-status');
        Route::post('/{payment}/verify-bank-transfer', [AdminPaymentController::class, 'verifyBankTransfer'])->name('verify-bank-transfer');
        Route::post('/{payment}/reject-bank-transfer', [AdminPaymentController::class, 'rejectBankTransfer'])->name('reject-bank-transfer');
        Route::post('/bulk-update', [AdminPaymentController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/export', [AdminPaymentController::class, 'export'])->name('export');

        // Manual sync routes untuk admin
        Route::get('/{paymentCode}/manual-sync', [AdminPaymentController::class, 'manualSync'])
             ->name('manual-sync.ajax');
        Route::post('/{paymentCode}/manual-sync', [AdminPaymentController::class, 'manualSyncPost'])
             ->name('manual-sync');

        // Invoice routes untuk admin (Cash dan Xendit)
        Route::get('/invoice/{paymentCode}/pdf', [AdminPaymentController::class, 'downloadInvoicePdf'])
             ->name('invoice.pdf');
        Route::get('/invoice/{paymentCode}', [AdminPaymentController::class, 'downloadInvoice'])
             ->name('invoice');
    });
});

// Santri Routes
Route::middleware(['auth', 'santri'])->prefix('santri')->name('santri.')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'santriDashboard'])->name('dashboard');
    Route::get('/dashboard/document-progress', [DashboardController::class, 'getDocumentProgress'])->name('dashboard.document-progress');

    // FAQ Routes
    Route::prefix('faq')->name('faq.')->group(function () {
        Route::get('/', [FAQController::class, 'index'])->name('index');
    });

    // Kegiatan Routes
    Route::prefix('kegiatan')->name('kegiatan.')->group(function () {
        Route::get('/', [KegiatanController::class, 'index'])->name('index');
    });

    // Biodata Routes
    Route::prefix('biodata')->name('biodata.')->group(function () {
        Route::get('/', [BiodataController::class, 'index'])->name('index');
        Route::post('/', [BiodataController::class, 'store'])->name('store');
        Route::get('/show', [BiodataController::class, 'show'])->name('show');
        Route::get('/edit', [BiodataController::class, 'edit'])->name('edit');
        Route::put('/update', [BiodataController::class, 'update'])->name('update');
        Route::get('/package/{package}/prices', [BiodataController::class, 'getPackagePrices'])->name('package.prices');
    });

    // Document Routes
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::post('/upload/{documentType}', [DocumentController::class, 'upload'])->name('upload');
        Route::delete('/delete/{documentType}', [DocumentController::class, 'delete'])->name('delete');
        Route::get('/file/{documentType}', [DocumentController::class, 'getFile'])->name('file');
        Route::get('/download/{documentType}', [DocumentController::class, 'download'])->name('download');
        Route::post('/complete', [DocumentController::class, 'completeRegistration'])->name('complete');
        Route::get('/progress', [DocumentController::class, 'getProgress'])->name('progress');
        Route::get('/download-all', [DocumentController::class, 'downloadAll'])->name('download-all');
        Route::get('/test-image', [DocumentController::class, 'testImage'])->name('test-image');
        Route::get('/check-quota-delete-all', [DocumentController::class, 'checkQuotaForDeleteAll'])->name('check-quota-delete-all');
        Route::delete('/delete-all', [DocumentController::class, 'deleteAllDocuments'])->name('delete-all');
        Route::get('/check-complete', [DocumentController::class, 'checkAllDocumentsCompleteApi'])->name('check-complete');
    });

    // Payment Routes untuk Santri
   // routes/web.php - Update bagian santri payments routes:

Route::prefix('payments')->name('payments.')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    Route::get('/create', [PaymentController::class, 'create'])->name('create');
    Route::post('/', [PaymentController::class, 'store'])->name('store');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/failed', [PaymentController::class, 'failed'])->name('failed');

    // Route untuk invoice HTML
    Route::get('/invoice/{paymentCode}', [PaymentController::class, 'downloadInvoice'])->name('download-invoice');

    // Route untuk invoice PDF
    Route::get('/invoice/{paymentCode}/pdf', [PaymentController::class, 'downloadInvoicePdf'])->name('download-invoice-pdf');

    Route::get('/{id}/detail', [PaymentController::class, 'detail'])->name('detail');
    Route::get('/check-status/{paymentCode}', [PaymentController::class, 'checkStatus'])->name('check-status');
    Route::get('/retry/{paymentCode}', [PaymentController::class, 'retryPayment'])->name('retry');

    // Manual sync dengan dua metode (GET untuk AJAX, POST untuk form)
    Route::get('/manual-sync/{paymentCode}', [PaymentController::class, 'manualSync'])
         ->name('manual-sync.ajax');

    Route::post('/manual-sync/{paymentCode}', [PaymentController::class, 'manualSyncPost'])
         ->name('manual-sync');

    Route::get('/check-quota', [PaymentController::class, 'checkQuota'])->name('check-quota');
});

    // Settings Routes untuk Calon Santri
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [UserSettingController::class, 'index'])->name('index');
        Route::put('/profile', [UserSettingController::class, 'updateProfile'])->name('profile.update');
        Route::post('/google/disconnect', [UserSettingController::class, 'disconnectGoogle'])->name('google.disconnect');
    });
});

// General Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Xendit Webhook Route (TANPA MIDDLEWARE CSRF)
Route::post('/webhook/xendit', [PaymentController::class, 'webhook'])
    ->name('webhook.xendit')
    ->withoutMiddleware(['web']);

// ==================== ROUTES FALLBACK ====================
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('welcome');
});

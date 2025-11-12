@extends('layouts.app')

@section('title', 'Pembayaran - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('santri.dashboard') }}" class="text-primary hover:text-secondary font-medium">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Pembayaran Pendaftaran</h1>
            <p class="text-gray-600 mb-6">Pilih metode pembayaran untuk menyelesaikan pendaftaran</p>

            @if($registration)
            <!-- Detail Pendaftaran -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center mb-3">
                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                    <h3 class="font-semibold text-blue-800">Detail Pendaftaran</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-blue-600 font-medium">ID Pendaftaran:</span>
                        <p class="text-blue-800">{{ $registration->id_pendaftaran }}</p>
                    </div>
                    <div>
                        <span class="text-blue-600 font-medium">Program Unggulan:</span>
                        <p class="text-blue-800">{{ $programUnggulanName }}</p>
                    </div>
                    <div>
                        <span class="text-blue-600 font-medium">Paket Dipilih:</span>
                        <p class="text-blue-800">{{ $registration->package->name ?? 'Paket Pendaftaran' }}</p>
                    </div>
                    <div>
                        <span class="text-blue-600 font-medium">Total Biaya:</span>
                        <p class="text-blue-800 font-bold text-lg">
                            @if($registration->total_biaya > 0)
                                {{ $registration->formatted_total_biaya }}
                            @elseif(isset($manualTotal) && $manualTotal > 0)
                                Rp {{ number_format($manualTotal, 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Rincian Harga -->
            @if($packagePrices->count() > 0)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-800 mb-3">Rincian Biaya Paket {{ $registration->package->name ?? 'Paket Pendaftaran' }}</h4>
                <div class="space-y-2">
                    @foreach($packagePrices as $price)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <div>
                            <span class="text-gray-700">{{ $price->item_name }}</span>
                            @if($price->description)
                            <p class="text-xs text-gray-500">{{ $price->description }}</p>
                            @endif
                        </div>
                        <span class="font-semibold text-primary">{{ $price->formatted_amount }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between items-center py-2 border-t border-gray-300 mt-2">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="font-bold text-lg text-primary">
                            @if($registration->total_biaya > 0)
                                {{ $registration->formatted_total_biaya }}
                            @elseif(isset($manualTotal) && $manualTotal > 0)
                                Rp {{ number_format($manualTotal, 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @endif

            @if(($registration->total_biaya > 0) || (isset($manualTotal) && $manualTotal > 0))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cash Payment -->
                <div class="border-2 border-gray-300 rounded-xl p-6 hover:border-primary transition duration-300">
                    <div class="text-center">
                        <i class="fas fa-money-bill-wave text-4xl text-green-500 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pembayaran Cash</h3>
                        <p class="text-gray-600 mb-4">Bayar langsung di pesantren</p>
                        <div class="text-2xl font-bold text-primary mb-4">
                            @if($registration->total_biaya > 0)
                                {{ $registration->formatted_total_biaya }}
                            @else
                                Rp {{ number_format($manualTotal, 0, ',', '.') }}
                            @endif
                        </div>

                        <form action="{{ route('santri.payments.store') }}" method="POST" id="cashPaymentForm">
                            @csrf
                            <input type="hidden" name="payment_method" value="cash">
                            <button type="submit"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-money-bill-wave"></i>
                                Pilih Cash
                            </button>
                        </form>

                        <div class="mt-4 text-sm text-gray-600">
                            <p class="font-semibold">Keuntungan Pembayaran Cash:</p>
                            <ul class="mt-2 space-y-1">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    Tidak ada biaya administrasi
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    Konfirmasi langsung oleh admin
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    Dapat konsultasi langsung
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Xendit Payment -->
                <div class="border-2 border-gray-300 rounded-xl p-6 hover:border-primary transition duration-300">
                    <div class="text-center">
                        <i class="fas fa-credit-card text-4xl text-blue-500 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pembayaran Online</h3>
                        <p class="text-gray-600 mb-4">Transfer bank, e-wallet, dll via Xendit</p>
                        <div class="text-2xl font-bold text-primary mb-4">
                            @if($registration->total_biaya > 0)
                                {{ $registration->formatted_total_biaya }}
                            @else
                                Rp {{ number_format($manualTotal, 0, ',', '.') }}
                            @endif
                        </div>

                        <form action="{{ route('santri.payments.store') }}" method="POST" id="onlinePaymentForm">
                            @csrf
                            <input type="hidden" name="payment_method" value="xendit">
                            <button type="submit"
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-credit-card"></i>
                                Bayar Online
                            </button>
                        </form>

                        <div class="mt-4 text-sm text-gray-600">
                            <p class="font-semibold">Metode Pembayaran Tersedia:</p>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <span class="flex items-center">
                                    <i class="fas fa-university text-blue-500 mr-1"></i>
                                    Transfer Bank
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-mobile-alt text-green-500 mr-1"></i>
                                    E-Wallet
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-credit-card text-purple-500 mr-1"></i>
                                    Virtual Account
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-store text-orange-500 mr-1"></i>
                                    Retail
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Confirmation -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800 mb-2">Konfirmasi Pembayaran</h4>
                        <p class="text-yellow-700 text-sm mb-2">
                            Dengan melanjutkan pembayaran, Anda menyetujui bahwa:
                        </p>
                        <ul class="text-yellow-700 text-sm space-y-1">
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-500 mt-1 mr-2 text-xs"></i>
                                <span>Data pendaftaran sudah benar dan lengkap</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-500 mt-1 mr-2 text-xs"></i>
                                <span>Semua dokumen sudah diunggah</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-500 mt-1 mr-2 text-xs"></i>
                                <span>Pembayaran hanya dilakukan sekali</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-500 mt-1 mr-2 text-xs"></i>
                                <span>Program Unggulan: <strong>{{ $programUnggulanName }}</strong></span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-500 mt-1 mr-2 text-xs"></i>
                                <span>Paket: <strong>{{ $registration->package->name ?? 'Paket Pendaftaran' }}</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                <h3 class="text-red-800 font-semibold">Total Biaya Masih Rp 0</h3>
                <p class="text-red-600 text-sm">Silakan hubungi admin untuk mengatur harga paket yang dipilih.</p>
                <p class="text-red-600 text-sm mt-2">Package: {{ $registration->package->name ?? 'Paket Pendaftaran' }}</p>
                <a href="{{ route('santri.dashboard') }}" class="inline-block mt-3 bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition duration-300">
                    Kembali ke Dashboard
                </a>
            </div>
            @endif

            <!-- Important Information -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-blue-800 mb-2">Informasi Penting</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
                            <div class="space-y-2">
                                <div class="flex items-start">
                                    <i class="fas fa-clock text-blue-500 mt-1 mr-2"></i>
                                    <span>Pembayaran online kadaluarsa dalam 24 jam</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-sync-alt text-blue-500 mt-1 mr-2"></i>
                                    <span>Status diperiksa otomatis setiap 30 detik</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-whatsapp text-green-500 mt-1 mr-2"></i>
                                    <span>Notifikasi WhatsApp dikirim otomatis</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-start">
                                    <i class="fas fa-user-check text-blue-500 mt-1 mr-2"></i>
                                    <span>Status pendaftaran otomatis berubah setelah pembayaran sukses</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-file-pdf text-red-500 mt-1 mr-2"></i>
                                    <span>Invoice dapat didownload setelah pembayaran berhasil</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-phone text-blue-500 mt-1 mr-2"></i>
                                    <span>Hubungi admin jika mengalami kendala</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Data Pendaftaran Tidak Ditemukan</h3>
                <p class="text-gray-600 mb-4">Silakan lengkapi pendaftaran terlebih dahulu</p>
                <a href="{{ route('santri.biodata.index') }}" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                    Lengkapi Pendaftaran
                </a>
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 text-center">
        <i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Memproses Pembayaran</h3>
        <p class="text-gray-600">Sedang membuat pembayaran, harap tunggu...</p>
    </div>
</div>

<script>
// Handle form submission dengan loading
document.addEventListener('DOMContentLoaded', function() {
    const cashForm = document.getElementById('cashPaymentForm');
    const onlineForm = document.getElementById('onlinePaymentForm');
    const loadingModal = document.getElementById('loadingModal');

    function showLoading() {
        loadingModal.classList.remove('hidden');
    }

    function hideLoading() {
        loadingModal.classList.add('hidden');
    }

    if (cashForm) {
        cashForm.addEventListener('submit', function(e) {
            showLoading();
            // Biarkan form submit normal
        });
    }

    if (onlineForm) {
        onlineForm.addEventListener('submit', function(e) {
            showLoading();
            // Biarkan form submit normal
        });
    }

    // Sembunyikan loading jika halaman selesai dimuat (fallback)
    window.addEventListener('load', function() {
        hideLoading();
    });
});

// Prevent double submission
let isSubmitting = false;

document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            isSubmitting = true;

            // Disable submit buttons
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitButtons.forEach(button => {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            });

            // Re-enable after 5 seconds (safety)
            setTimeout(() => {
                isSubmitting = false;
                submitButtons.forEach(button => {
                    button.disabled = false;
                    button.innerHTML = button.getAttribute('data-original-text') || button.innerHTML;
                });
            }, 5000);
        });
    });
});
</script>

<style>
.full-width-page {
    width: 100%;
    min-height: 100vh;
}

/* Animation for payment cards */
.payment-card {
    transition: all 0.3s ease;
}

.payment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-spinner {
    animation: spin 1s linear infinite;
}
</style>
@endsection

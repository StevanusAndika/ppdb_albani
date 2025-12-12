@extends('layouts.app')

@section('title', 'Pembayaran - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
   @include('layouts.components.calon_santri.navbar')

    <main class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Pembayaran Pendaftaran</h1>
            <p class="text-gray-600 mb-6">Pilih metode pembayaran untuk menyelesaikan pendaftaran</p>

            <!-- Kuota Information -->
            @if($quota)
            <div class="bg-{{ $quota->isAvailable() ? 'green' : 'red' }}-50 border border-{{ $quota->isAvailable() ? 'green' : 'red' }}-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-users text-{{ $quota->isAvailable() ? 'green' : 'red' }}-500 mr-3 text-lg"></i>
                        <div>
                            <h3 class="font-semibold text-{{ $quota->isAvailable() ? 'green' : 'red' }}-800">
                                Kuota Pendaftaran
                                @if($quota->isAvailable())
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full ml-2">Tersedia</span>
                                @else
                                <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded-full ml-2">Penuh</span>
                                @endif
                            </h3>
                            <p class="text-{{ $quota->isAvailable() ? 'green' : 'red' }}-600 text-sm">
                                Sisa kuota: <strong>{{ $quota->sisa }}</strong> dari <strong>{{ $quota->kuota }}</strong> slot
                                ({{ number_format($quota->persentase_terpakai, 1) }}% terpakai)
                            </p>
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-32">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-{{ $quota->isAvailable() ? 'green' : 'red' }}-500 h-2 rounded-full"
                                 style="width: {{ $quota->persentase_terpakai }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

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
                    {{-- <div>
                        <span class="text-blue-600 font-medium">Program Unggulan:</span>
                    </div> --}}
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
                @if($quota && $quota->isAvailable())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Cash Payment -->
                    <div class="border-2 border-gray-300 rounded-xl p-6 hover:border-primary transition duration-300 payment-card">
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
                                        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2"
                                        data-original-text="Pilih Cash">
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

                    <!-- Bank Transfer Payment -->
                    <div class="border-2 border-gray-300 rounded-xl p-6 hover:border-primary transition duration-300 payment-card">
                        <div class="text-center">
                            <i class="fas fa-university text-4xl text-purple-500 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Transfer Bank</h3>
                            <p class="text-gray-600 mb-4">Bayar via transfer bank manual</p>
                            <div class="text-2xl font-bold text-primary mb-4">
                                @if($registration->total_biaya > 0)
                                    {{ $registration->formatted_total_biaya }}
                                @else
                                    Rp {{ number_format($manualTotal, 0, ',', '.') }}
                                @endif
                            </div>

                            <button type="button"
                                    class="w-full bg-purple-500 hover:bg-purple-600 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2"
                                    onclick="toggleBankTransferForm()"
                                    id="bankTransferToggleBtn">
                                <i class="fas fa-university"></i>
                                Pilih Transfer Bank
                            </button>

                            <div class="mt-4 text-sm text-gray-600">
                                <p class="font-semibold">Keuntungan Transfer Bank:</p>
                                <ul class="mt-2 space-y-1">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-purple-500 mr-2"></i>
                                        Verifikasi cepat dari admin
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-purple-500 mr-2"></i>
                                        Upload bukti transfer
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-purple-500 mr-2"></i>
                                        Konfirmasi otomatis
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Xendit Payment -->
                    <div class="border-2 border-gray-300 rounded-xl p-6 hover:border-primary transition duration-300 payment-card">
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
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2"
                                        data-original-text="Bayar Online">
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
                                {{-- <li class="flex items-start">
                                    <i class="fas fa-check text-yellow-500 mt-1 mr-2 text-xs"></i>
                                    <span>Paket: <strong>{{ $registration->package->name }}</strong></span>
                                </li> --}}
                                <li class="flex items-start">
                                    <i class="fas fa-check text-yellow-500 mt-1 mr-2 text-xs"></i>
                                    <span>Paket: <strong>{{ $registration->package->name ?? 'Paket Pendaftaran' }}</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-yellow-500 mt-1 mr-2 text-xs"></i>
                                    <span>Kuota tersedia: <strong>{{ $quota->sisa }} slot</strong> dari {{ $quota->kuota }} total</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @else
                <!-- Kuota Penuh Warning -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <h3 class="text-red-800 font-bold text-xl mb-2">Kuota Pendaftaran Sudah Penuh</h3>
                    <p class="text-red-600 mb-4">
                        Maaf, kuota pendaftaran untuk periode ini sudah terpenuhi.
                        Tidak dapat melakukan pembayaran baru.
                    </p>
                    <div class="bg-white rounded-lg p-4 inline-block">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Total Kuota:</span>
                                <p class="font-semibold">{{ $quota->kuota ?? 0 }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Terpakai:</span>
                                <p class="font-semibold text-red-600">{{ $quota->terpakai ?? 0 }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Sisa:</span>
                                <p class="font-semibold">{{ $quota->sisa ?? 0 }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Persentase:</span>
                                <p class="font-semibold">{{ number_format($quota->persentase_terpakai ?? 0, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('santri.payments.index') }}"
                           class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition duration-300 inline-flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Riwayat Pembayaran
                        </a>
                    </div>
                </div>
                @endif
            @else
            <!-- Total Biaya Masih 0 Warning -->
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
                                @if($quota)
                                <div class="flex items-start">
                                    <i class="fas fa-users text-indigo-500 mt-1 mr-2"></i>
                                    <span>Kuota tersedia: {{ $quota->sisa }} dari {{ $quota->kuota }} slot</span>
                                </div>
                                @endif
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
            <!-- Data Pendaftaran Tidak Ditemukan -->
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
    @include('layouts.components.admin.footer')
</div>

<!-- Bank Transfer Form Modal -->
<div id="bankTransferModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Form Transfer Bank</h3>
            <button type="button" onclick="closeBankTransferForm()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form action="{{ route('santri.payments.store') }}" method="POST" id="bankTransferForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="payment_method" value="bank_transfer">

            <!-- Sender Name -->
            <div class="mb-4">
                <label for="sender_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Atas Nama Pengirim *
                </label>
                <input type="text" 
                       id="sender_name" 
                       name="sender_name" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       placeholder="Nama sesuai rekening bank"
                       required>
                @error('sender_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Proof Upload -->
            <div class="mb-4">
                <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Transfer *
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-purple-500 transition">
                    <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                    <input type="file" 
                           id="payment_proof" 
                           name="payment_proof" 
                           accept=".jpg,.jpeg,.png,.pdf"
                           class="hidden"
                           required>
                    <label for="payment_proof" class="cursor-pointer">
                        <p class="text-gray-600 font-medium">Pilih File Bukti Transfer</p>
                        <p class="text-gray-500 text-sm">JPG, PNG, atau PDF (Max 5MB)</p>
                    </label>
                </div>
                <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                @error('payment_proof')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-700">
                <i class="fas fa-info-circle mr-2"></i>
                <span>Pastikan bukti transfer jelas dan memuat informasi lengkap tentang transaksi</span>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="button" 
                        onclick="closeBankTransferForm()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i>
                    Kirim Bukti
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 text-center">
        <i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Memproses Pembayaran</h3>
        <p class="text-gray-600">Sedang membuat pembayaran, harap tunggu...</p>
        <p class="text-sm text-gray-500 mt-2">Mereservasi kuota dan membuat pembayaran...</p>
    </div>
</div>

<!-- Kuota Check Modal -->
<div id="quotaCheckModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 text-center max-w-sm">
        <i class="fas fa-sync-alt fa-spin text-3xl text-blue-500 mb-4"></i>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Memeriksa Kuota</h3>
        <p class="text-gray-600">Memeriksa ketersediaan kuota...</p>
    </div>
</div>

<script>
// Handle form submission dengan loading dan pengecekan kuota
document.addEventListener('DOMContentLoaded', function() {
    const cashForm = document.getElementById('cashPaymentForm');
    const onlineForm = document.getElementById('onlinePaymentForm');
    const loadingModal = document.getElementById('loadingModal');
    const quotaCheckModal = document.getElementById('quotaCheckModal');

    function showLoading() {
        loadingModal.classList.remove('hidden');
    }

    function hideLoading() {
        loadingModal.classList.add('hidden');
    }

    function showQuotaCheck() {
        quotaCheckModal.classList.remove('hidden');
    }

    function hideQuotaCheck() {
        quotaCheckModal.classList.add('hidden');
    }

    // Function untuk cek kuota sebelum submit
    async function checkQuotaBeforeSubmit(form, paymentMethod) {
        showQuotaCheck();

        try {
            const response = await fetch('/santri/payments/check-quota', {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            hideQuotaCheck();

            if (data.success && data.available) {
                // Kuota tersedia, lanjutkan pembayaran
                showLoading();
                form.submit();
            } else {
                // Kuota habis, tampilkan pesan error
                showQuotaError();
            }
        } catch (error) {
            hideQuotaCheck();
            console.error('Error checking quota:', error);
            // Fallback: lanjutkan tanpa pengecekan kuota
            showLoading();
            form.submit();
        }
    }

    function showQuotaError() {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>Kuota sudah penuh! Tidak dapat melanjutkan pembayaran.</span>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
            // Refresh halaman untuk update status kuota
            window.location.reload();
        }, 5000);
    }

    if (cashForm) {
        cashForm.addEventListener('submit', function(e) {
            e.preventDefault();
            checkQuotaBeforeSubmit(this, 'cash');
        });
    }

    if (onlineForm) {
        onlineForm.addEventListener('submit', function(e) {
            e.preventDefault();
            checkQuotaBeforeSubmit(this, 'xendit');
        });
    }

    // Sembunyikan loading jika halaman selesai dimuat (fallback)
    window.addEventListener('load', function() {
        hideLoading();
        hideQuotaCheck();
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
                const originalText = button.innerHTML;
                button.setAttribute('data-original-text', originalText);
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            });

            // Re-enable after 10 seconds (safety)
            setTimeout(() => {
                isSubmitting = false;
                submitButtons.forEach(button => {
                    button.disabled = false;
                    const originalText = button.getAttribute('data-original-text');
                    if (originalText) {
                        button.innerHTML = originalText;
                    }
                });
            }, 10000);
        });
    });
});

// Real-time quota check (optional)
function checkQuotaPeriodically() {
    setInterval(async () => {
        try {
            const response = await fetch('/santri/payments/check-quota');
            const data = await response.json();

            if (!data.available) {
                // Jika kuota habis, redirect ke index
                window.location.href = '{{ route("santri.payments.index") }}';
            }
        } catch (error) {
            console.error('Error checking quota:', error);
        }
    }, 30000); // Check every 30 seconds
}

// Jalankan pengecekan kuota periodik jika ada kuota dan tersedia
@if($quota && $quota->isAvailable())
document.addEventListener('DOMContentLoaded', checkQuotaPeriodically);
@endif

// Bank Transfer Form Functions
function toggleBankTransferForm() {
    const modal = document.getElementById('bankTransferModal');
    modal.classList.remove('hidden');
}

function closeBankTransferForm() {
    const modal = document.getElementById('bankTransferModal');
    modal.classList.add('hidden');
}

// Check quota before submit bank transfer
async function checkQuotaBeforeSubmitBankTransfer(form, originalText, submitBtn) {
    const quotaCheckModal = document.getElementById('quotaCheckModal');
    quotaCheckModal.classList.remove('hidden');

    try {
        const response = await fetch('/santri/payments/check-quota', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        quotaCheckModal.classList.add('hidden');

        if (data.success && data.available) {
            // Kuota tersedia, lanjutkan pembayaran dengan mengirim form secara tradisional
            const loadingModal = document.getElementById('loadingModal');
            loadingModal.classList.remove('hidden');
            
            // Submit form secara tradisional
            setTimeout(() => {
                form.submit();
            }, 500);
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            alert('Kuota sudah penuh! Tidak dapat melanjutkan pembayaran.');
        }
    } catch (error) {
        quotaCheckModal.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        console.error('Error checking quota:', error);
        alert('Terjadi kesalahan saat memeriksa kuota. Silakan coba lagi.');
    }
}

// File input display name
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('payment_proof');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const fileName = document.getElementById('fileName');
            if (this.files && this.files[0]) {
                fileName.textContent = '✓ ' + this.files[0].name + ' (' + (this.files[0].size / 1024).toFixed(2) + ' KB)';
            }
        });
    }

    // Bank transfer form submission
    const bankTransferForm = document.getElementById('bankTransferForm');
    if (bankTransferForm) {
        bankTransferForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!document.getElementById('sender_name').value.trim()) {
                alert('Silakan isi nama pengirim');
                return;
            }
            
            if (!document.getElementById('payment_proof').files.length) {
                alert('Silakan pilih file bukti transfer');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengunggah...';

            // Check quota then submit - update untuk handle redirect dengan form biasa
            checkQuotaBeforeSubmitBankTransfer(this, originalText, submitBtn);
        });
    }

    // Close modal when clicking outside
    const modal = document.getElementById('bankTransferModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBankTransferForm();
            }
        });
    }
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

/* Progress bar animation */
.progress-bar {
    transition: width 0.3s ease;
}

/* Responsive design */
@media (max-width: 768px) {
    .payment-card {
        margin-bottom: 1rem;
    }
}
</style>
@endsection

@extends('layouts.app')

@section('title', 'Riwayat Pembayaran - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    @include('layouts.components.calon_santri.navbar')

    <main class="max-w-6xl mx-auto py-8 px-4">
        <!-- Kuota Information Card -->
        @if($quota)
        <div class="bg-white rounded-xl shadow-md p-6 mb-6 border-l-4 {{ $quotaAvailable ? 'border-green-500' : 'border-red-500' }}">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">
                        Informasi Kuota Pendaftaran
                        @if($quotaAvailable)
                        <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full ml-2">Tersedia</span>
                        @else
                        <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded-full ml-2">Penuh</span>
                        @endif
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Total Kuota:</span>
                            <span class="font-semibold ml-2">{{ $quota->kuota }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Terpakai:</span>
                            <span class="font-semibold ml-2">{{ $quota->terpakai }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Sisa:</span>
                            <span class="font-semibold ml-2 {{ $quotaAvailable ? 'text-green-600' : 'text-red-600' }}">
                                {{ $quota->sisa }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Persentase:</span>
                            <span class="font-semibold ml-2">{{ number_format($quota->persentase_terpakai, 1) }}%</span>
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-{{ $quotaAvailable ? 'green' : 'red' }}-500 h-2.5 rounded-full"
                                 style="width: {{ $quota->persentase_terpakai }}%"></div>
                        </div>
                    </div>
                </div>

                @if(!$quotaAvailable && !$hasSuccessfulPayment)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-red-800 font-medium">Kuota sudah penuh</span>
                    </div>
                    <p class="text-red-600 text-sm mt-1">Tidak dapat melakukan pembayaran baru</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Riwayat Pembayaran</h1>
                    <p class="text-gray-600">Lihat status dan riwayat pembayaran Anda</p>
                </div>

                <div class="flex gap-3">
                    @if(!$hasSuccessfulPayment && $quotaAvailable)
                    <a href="{{ route('santri.payments.create') }}"
                       class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-lg font-semibold transition duration-300 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Buat Pembayaran Baru
                    </a>
                    @elseif($hasSuccessfulPayment)
                    <div class="bg-green-100 border border-green-200 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-2 text-green-800">
                            <i class="fas fa-check-circle"></i>
                            <span class="font-semibold">Pembayaran Sudah Lunas</span>
                        </div>
                    </div>
                    @elseif(!$quotaAvailable)
                    <div class="bg-red-100 border border-red-200 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-2 text-red-800">
                            <i class="fas fa-times-circle"></i>
                            <span class="font-semibold">Kuota Penuh</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $payments->count() }}</div>
                    <div class="text-sm text-blue-600">Total Transaksi</div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $payments->whereIn('status', ['success', 'lunas'])->count() }}
                    </div>
                    <div class="text-sm text-green-600">Berhasil</div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ $payments->whereIn('status', ['pending', 'waiting_payment', 'processing'])->count() }}
                    </div>
                    <div class="text-sm text-yellow-600">Menunggu</div>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-red-600">
                        {{ $payments->whereIn('status', ['failed', 'expired'])->count() }}
                    </div>
                    <div class="text-sm text-red-600">Gagal</div>
                </div>
            </div>

            @if($payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Kode Pembayaran</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Paket</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Metode</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50 payment-row"
                            data-payment-code="{{ $payment->payment_code }}"
                            data-payment-status="{{ $payment->status }}"
                            data-payment-method="{{ $payment->payment_method }}">
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900">{{ $payment->payment_code }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->registration->id_pendaftaran }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-700">{{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</span>
                            </td>
                            
                            <td class="py-4 px-4">
                                <div class="font-semibold text-primary">{{ $payment->formatted_amount }}</div>
                            </td>
                            <td class="py-4 px-4">
                                @if($payment->payment_method === 'cash')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-money-bill-wave mr-1"></i> Cash
                                </span>
                                @else
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-credit-card mr-1"></i> Online
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $payment->status_color }}" id="status-{{ $payment->payment_code }}">
                                    <i class="fas {{ $payment->status_icon }} mr-1"></i>
                                    {{ $payment->status_label }}
                                </span>
                                @if($payment->expired_at && $payment->isPending())
                                <div class="text-xs text-gray-500 mt-1">
                                    Kedaluarsa: {{ $payment->expired_at->translatedFormat('d M Y H:i') }}
                                </div>
                                @endif

                                <!-- Auto Sync Indicator -->
                                @if($payment->isPending() && $payment->payment_method === 'xendit')
                                <div class="text-xs text-blue-600 mt-1 flex items-center">
                                    <i class="fas fa-sync-alt fa-spin mr-1"></i>
                                    <span id="sync-indicator-{{ $payment->payment_code }}">Auto sync aktif</span>
                                </div>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-500">
                                <div>{{ $payment->created_at->translatedFormat('d F Y') }}</div>
                                <div>{{ $payment->created_at->format('H:i') }}</div>
                                @if($payment->paid_at)
                                <div class="text-green-600 text-xs">
                                    Dibayar: {{ $payment->paid_at->format('d/m H:i') }}
                                </div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    @if($payment->payment_method === 'xendit' && $payment->isPending() && $payment->xendit_response)
                                    <a href="{{ $payment->xendit_response['invoice_url'] ?? '#' }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900 transition duration-200 p-2 rounded-full hover:bg-blue-50"
                                       title="Lanjutkan Pembayaran">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    @endif

                                    {{-- @if($payment->isPaid())
                                    <!-- Tombol Download Invoice PDF -->
                                    <a href="{{ route('santri.payments.download-invoice-pdf', $payment->payment_code) }}"
                                       target="_blank"
                                       class="text-green-600 hover:text-green-900 transition duration-200 p-2 rounded-full hover:bg-green-50 download-invoice-btn"
                                       title="Download Invoice PDF"
                                       data-payment-code="{{ $payment->payment_code }}">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @endif --}}

                                    <!-- Manual Sync Button -->
                                    @if($payment->payment_method === 'xendit' && $payment->isPending())
                                    <button type="button"
                                            class="text-orange-600 hover:text-orange-900 transition duration-200 p-2 rounded-full hover:bg-orange-50 sync-btn"
                                            title="Sinkronisasi Status Sekarang"
                                            data-payment-code="{{ $payment->payment_code }}">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    @endif

                                    <!-- Retry Button untuk payment expired -->
                                    @if($payment->status === 'expired' && !$hasSuccessfulPayment && $quotaAvailable)
                                    <a href="{{ route('santri.payments.retry', $payment->payment_code) }}"
                                       class="text-red-600 hover:text-red-900 transition duration-200 p-2 rounded-full hover:bg-red-50"
                                       title="Coba Lagi">
                                        <i class="fas fa-redo-alt"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Payment Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-2">Ringkasan Pembayaran</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Total Transaksi:</span>
                        <span class="font-semibold ml-2">{{ $payments->count() }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Total Dibayar:</span>
                        <span class="font-semibold text-primary ml-2">
                            Rp {{ number_format($payments->whereIn('status', ['success', 'lunas'])->sum('amount'), 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Status Akhir:</span>
                        @if($hasSuccessfulPayment)
                        <span class="font-semibold text-green-600 ml-2">LUNAS</span>
                        @else
                        <span class="font-semibold text-yellow-600 ml-2">MENUNGGU</span>
                        @endif
                    </div>
                </div>
            </div>

            @else
            <div class="text-center py-12">
                <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Pembayaran</h3>
                <p class="text-gray-500 mb-6">Anda belum melakukan pembayaran apapun</p>
                @if($quotaAvailable)
                <a href="{{ route('santri.payments.create') }}"
                   class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300">
                    Buat Pembayaran Pertama
                </a>
                @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 inline-block">
                    <p class="text-red-600">Tidak dapat membuat pembayaran karena kuota sudah penuh</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Payment Instructions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-primary mb-4">Informasi Pembayaran</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <span>Pembayaran hanya perlu dilakukan sekali untuk setiap pendaftaran</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-clock text-yellow-500 mt-1 mr-3"></i>
                        <span>Pembayaran online kadaluarsa dalam 24 jam</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-money-bill-wave text-green-500 mt-1 mr-3"></i>
                        <span>Pembayaran cash akan diverifikasi admin dalam 1x24 jam</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-file-pdf text-purple-500 mt-1 mr-3"></i>
                        <span>Download invoice PDF setelah pembayaran berhasil</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-sync-alt text-orange-500 mt-1 mr-3"></i>
                        <span>Status pembayaran online diperiksa otomatis setiap 30 detik</span>
                    </div>
                    @if($quota)
                    <div class="flex items-start">
                        <i class="fas fa-users text-indigo-500 mt-1 mr-3"></i>
                        <span>Kuota tersedia: {{ $quota->sisa }} dari {{ $quota->kuota }} slot</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Support -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-primary mb-4">Bantuan</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-green-500 mr-3"></i>
                        <span>Telepon: (021) 1234-5678</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-blue-500 mr-3"></i>
                        <span>Email: admin@banisyahid.sch.id</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                        <span>Alamat: Pondok Pesantren Al-Quran Bani Syahid</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-orange-500 mr-3"></i>
                        <span>Jam Operasional: Senin-Jumat 08:00-16:00</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Calon Santri -->
    @include('layouts.components.calon_santri.footer')
</div>

<!-- Modal untuk Loading -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 text-center max-w-sm">
        <i class="fas fa-spinner fa-spin text-3xl text-primary mb-4"></i>
        <h3 class="text-lg font-bold text-gray-800 mb-2" id="loadingTitle">Memproses...</h3>
        <p class="text-gray-600" id="loadingMessage">Harap tunggu sebentar</p>
    </div>
</div>

<!-- Modal untuk Konfirmasi -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi</h3>
        <p class="text-gray-600 mb-4" id="confirmationMessage"></p>
        <div class="flex justify-end gap-3">
            <button type="button" onclick="hideConfirmation()" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                Batal
            </button>
            <button type="button" onclick="confirmAction()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary font-medium">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
// Global variables
let currentAction = null;
let currentPaymentCode = null;

// Show/hide loading modal
function showLoading(title = 'Memproses...', message = 'Harap tunggu sebentar') {
    const modal = document.getElementById('loadingModal');
    if (modal) {
        const titleEl = document.getElementById('loadingTitle');
        const messageEl = document.getElementById('loadingMessage');
        if (titleEl) titleEl.textContent = title;
        if (messageEl) messageEl.textContent = message;
        modal.classList.remove('hidden');
    }
}

function hideLoading() {
    const modal = document.getElementById('loadingModal');
    if (modal) modal.classList.add('hidden');
}

// Show/hide confirmation modal
function showConfirmation(message, action, paymentCode) {
    const modal = document.getElementById('confirmationModal');
    const messageEl = document.getElementById('confirmationMessage');

    if (modal && messageEl) {
        currentAction = action;
        currentPaymentCode = paymentCode;
        messageEl.textContent = message;
        modal.classList.remove('hidden');
    }
}

function hideConfirmation() {
    const modal = document.getElementById('confirmationModal');
    if (modal) modal.classList.add('hidden');
    currentAction = null;
    currentPaymentCode = null;
}

function confirmAction() {
    if (currentAction === 'sync' && currentPaymentCode) {
        manualSync(currentPaymentCode);
    }
    hideConfirmation();
}

// Function untuk sinkronisasi manual (AJAX GET)
async function manualSync(paymentCode) {
    showLoading('Mensinkronisasi', 'Memeriksa status pembayaran...');

    try {
        // Gunakan route AJAX
        const response = await fetch(`/santri/payments/manual-sync/${paymentCode}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        // Cek content type
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            // Jika bukan JSON, mungkin error page
            const text = await response.text();
            console.error('Server returned non-JSON response:', text.substring(0, 200));

            // Coba parse sebagai HTML untuk error messages
            const parser = new DOMParser();
            const htmlDoc = parser.parseFromString(text, 'text/html');
            const errorMsg = htmlDoc.querySelector('.error-message, .alert-danger')?.textContent ||
                           htmlDoc.title ||
                           'Terjadi kesalahan pada server';

            throw new Error(`Format response tidak valid: ${errorMsg}`);
        }

        const data = await response.json();

        if (!response.ok) {
            // Handle HTTP errors
            if (response.status === 401 || response.status === 403) {
                throw new Error('Anda tidak memiliki izin untuk aksi ini.');
            } else if (response.status === 404) {
                throw new Error('Pembayaran tidak ditemukan.');
            } else if (response.status === 500) {
                throw new Error('Terjadi kesalahan pada server.');
            } else {
                throw new Error(data.message || `Error ${response.status}: ${response.statusText}`);
            }
        }

        if (data.success) {
            if (data.status_updated) {
                showNotification('Status pembayaran berhasil diperbarui! Halaman akan refresh...', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showNotification(data.message || 'Tidak ada perubahan status', 'info');
            }
        } else {
            throw new Error(data.message || 'Gagal sinkronisasi');
        }
    } catch (error) {
        console.error('Sync error:', error);

        // Tampilkan pesan error yang lebih user-friendly
        let errorMessage = 'Gagal sinkronisasi: ';

        if (error.message.includes('JSON') || error.message.includes('Format response')) {
            errorMessage = 'Terjadi kesalahan pada server. Silakan refresh halaman dan coba lagi.';
        } else if (error.message.includes('Network') || error.message.includes('Failed to fetch')) {
            errorMessage = 'Koneksi jaringan bermasalah. Periksa koneksi internet Anda.';
        } else if (error.message.includes('401') || error.message.includes('403')) {
            errorMessage = 'Sesi Anda mungkin telah berakhir. Silakan login ulang.';
        } else if (error.message.includes('404')) {
            errorMessage = 'Data pembayaran tidak ditemukan.';
        } else if (error.message.includes('500')) {
            errorMessage = 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
        } else {
            errorMessage += error.message;
        }

        showNotification(errorMessage, 'error');

        // Log error untuk debugging
        if (typeof console !== 'undefined' && console.error) {
            console.error('Full error:', error);
        }
    } finally {
        hideLoading();
    }
}

// Function untuk check status payment (auto sync)
async function checkPaymentStatus(paymentCode) {
    try {
        const response = await fetch(`/santri/payments/check-status/${paymentCode}`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        // Skip if response is not JSON
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            return;
        }

        const data = await response.json();

        if (data.success && data.status_updated) {
            // Update status indicator
            const indicator = document.getElementById(`sync-indicator-${paymentCode}`);
            if (indicator) {
                indicator.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Status diperbarui';
                indicator.className = 'text-xs text-green-600 mt-1 flex items-center';
            }

            // Show notification and reload after 2 seconds
            showNotification('Status pembayaran diperbarui otomatis!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else if (data.success) {
            // Just update the indicator
            const indicator = document.getElementById(`sync-indicator-${paymentCode}`);
            if (indicator) {
                const now = new Date();
                indicator.innerHTML = `<i class="fas fa-sync-alt fa-spin mr-1"></i>Terakhir dicek: ${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
            }
        }
    } catch (error) {
        console.error('Auto sync error for', paymentCode, ':', error);
        // Don't show error notifications for auto sync
    }
}

// Function untuk download invoice
async function downloadInvoice(paymentCode) {
    showLoading('Menyiapkan Invoice', 'Membuat file PDF...');

    try {
        // Trigger download in new tab
        window.open(`/santri/payments/invoice/${paymentCode}/pdf`, '_blank');

        // Wait a bit then hide loading
        setTimeout(() => {
            hideLoading();
            showNotification('Invoice sedang dipersiapkan...', 'info');
        }, 1000);

    } catch (error) {
        console.error('Download error:', error);
        showNotification('Gagal mempersiapkan invoice', 'error');
        hideLoading();
    }
}

// Check kuota availability
async function checkQuotaAvailability() {
    try {
        const response = await fetch(`/santri/payments/check-quota`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success && !data.available) {
            showNotification('Kuota pendaftaran sudah penuh!', 'warning');
        }
    } catch (error) {
        console.error('Error checking quota:', error);
    }
}

// Notification system
function showNotification(message, type = 'info') {
    const types = {
        'success': {
            icon: 'fa-check-circle',
            bgColor: 'bg-green-500',
            textColor: 'text-white'
        },
        'error': {
            icon: 'fa-exclamation-triangle',
            bgColor: 'bg-red-500',
            textColor: 'text-white'
        },
        'info': {
            icon: 'fa-info-circle',
            bgColor: 'bg-blue-500',
            textColor: 'text-white'
        },
        'warning': {
            icon: 'fa-exclamation-circle',
            bgColor: 'bg-yellow-500',
            textColor: 'text-white'
        }
    };

    const config = types[type] || types.info;

    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    if (existingNotifications.length > 3) {
        existingNotifications[0].remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 ${config.bgColor} ${config.textColor} px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${config.icon} mr-3"></i>
            <span class="font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:opacity-75">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Auto sync untuk pembayaran pending
function setupAutoSync() {
    const pendingPayments = document.querySelectorAll('.payment-row[data-payment-status="pending"][data-payment-method="xendit"]');

    pendingPayments.forEach(paymentRow => {
        const paymentCode = paymentRow.getAttribute('data-payment-code');

        // Check immediately
        setTimeout(() => {
            checkPaymentStatus(paymentCode);
        }, 1000);

        // Then check every 30 seconds
        setInterval(() => {
            checkPaymentStatus(paymentCode);
        }, 30000);
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Setup sync button click handlers
    document.querySelectorAll('.sync-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const paymentCode = this.getAttribute('data-payment-code');

            if (paymentCode) {
                showConfirmation(
                    'Apakah Anda yakin ingin mensinkronisasi status pembayaran ini?',
                    'sync',
                    paymentCode
                );
            }
        });
    });

    // Setup download invoice button click handlers
    document.querySelectorAll('.download-invoice-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const paymentCode = this.getAttribute('data-payment-code');

            if (paymentCode) {
                downloadInvoice(paymentCode);
            }
        });
    });

    // Setup auto sync for pending payments
    setupAutoSync();

    // Check kuota setiap 2 menit
    setInterval(() => {
        checkQuotaAvailability();
    }, 120000);

    // Global error handler untuk AJAX
    window.addEventListener('unhandledrejection', function(event) {
        console.error('Unhandled promise rejection:', event.reason);

        // Show user-friendly error
        if (event.reason && event.reason.message) {
            showNotification('Terjadi kesalahan: ' + event.reason.message, 'error');
        }
    });
});

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }

    .notification-toast {
        animation: slideIn 0.3s ease-out forwards;
    }

    .notification-toast.fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }

    .fa-spin {
        animation: fa-spin 2s infinite linear;
    }

    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection

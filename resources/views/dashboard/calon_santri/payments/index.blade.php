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
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Program Unggulan</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Metode</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50"
                            @if($payment->isPending())
                            data-payment-pending="true"
                            data-payment-code="{{ $payment->payment_code }}"
                            @endif>
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900">{{ $payment->payment_code }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->registration->id_pendaftaran }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-700">{{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-700">
                                    @if($payment->registration->programUnggulan && $payment->registration->programUnggulan->judul)
                                        {{ $payment->registration->programUnggulan->judul }}
                                    @else
                                        Tidak ada program unggulan
                                    @endif
                                </span>
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
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $payment->status_color }}">
                                    <i class="fas {{ $payment->status_icon }} mr-1"></i>
                                    {{ $payment->status_label }}
                                </span>
                                @if($payment->expired_at && $payment->isPending())
                                <div class="text-xs text-gray-500 mt-1">
                                    Kedaluarsa: {{ $payment->expired_at->translatedFormat('d M Y H:i') }}
                                </div>
                                @endif

                                <!-- Auto Sync Indicator -->
                                @if($payment->isPending())
                                <div class="text-xs text-blue-600 mt-1 flex items-center">
                                    <i class="fas fa-sync-alt fa-spin mr-1"></i>
                                    Auto sync aktif
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

                                    @if($payment->isPaid())
                                    <a href="{{ route('santri.payments.download-invoice', $payment->payment_code) }}"
                                       target="_blank"
                                       class="text-green-600 hover:text-green-900 transition duration-200 p-2 rounded-full hover:bg-green-50"
                                       title="Download Invoice PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @endif

                                    <!-- Manual Sync Button -->
                                    @if($payment->payment_method === 'xendit' && $payment->isPending())
                                    <form action="{{ route('santri.payments.manual-sync', $payment->payment_code) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-orange-600 hover:text-orange-900 transition duration-200 p-2 rounded-full hover:bg-orange-50"
                                                title="Sinkronisasi Status Sekarang">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
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
                        <span>Alamat: Pondok Pesantren Al-Qur'an Bani Syahid</span>
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

<script>
// Auto sync untuk pembayaran pending
function autoSyncPayments() {
    const pendingPayments = document.querySelectorAll('[data-payment-pending]');

    pendingPayments.forEach(paymentElement => {
        const paymentCode = paymentElement.getAttribute('data-payment-code');

        // Check status setiap 30 detik untuk payment pending
        setInterval(() => {
            checkPaymentStatus(paymentCode);
        }, 30000);
    });
}

// Function untuk check status payment
function checkPaymentStatus(paymentCode) {
    fetch(`/santri/payments/check-status/${paymentCode}`, {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.status_updated) {
                // Jika status berubah, refresh halaman
                showStatusUpdateNotification();
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }
    })
    .catch(error => {
        console.error('Error checking payment status:', error);
    });
}

// Check kuota availability
function checkQuotaAvailability() {
    fetch(`/santri/payments/check-quota`, {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (!data.available) {
                // Jika kuota habis, show notification
                showQuotaFullNotification();
            }
        }
    })
    .catch(error => {
        console.error('Error checking quota:', error);
    });
}

// Show notification ketika status berubah
function showStatusUpdateNotification() {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-sync-alt mr-2"></i>
            <span>Status pembayaran diperbarui. Halaman akan refresh...</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Show notification ketika kuota penuh
function showQuotaFullNotification() {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span>Kuota pendaftaran sudah penuh!</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Jalankan auto sync ketika halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    autoSyncPayments();

    // Check kuota setiap 2 menit
    setInterval(() => {
        checkQuotaAvailability();
    }, 120000);

    // Juga check status setiap 60 detik untuk semua payment
    setInterval(() => {
        const pendingPayments = document.querySelectorAll('[data-payment-pending]');
        pendingPayments.forEach(paymentElement => {
            const paymentCode = paymentElement.getAttribute('data-payment-code');
            checkPaymentStatus(paymentCode);
        });
    }, 60000);
});
</script>
@endsection

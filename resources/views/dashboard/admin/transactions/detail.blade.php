@extends('layouts.app')

@section('title', 'Detail Transaksi - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Detail Transaksi</h1>
                    <p class="text-secondary">Kode: {{ $payment->payment_code }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.transactions.index') }}"
                       class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300 flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>

                   
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Transaction Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Transaksi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kode Pembayaran</label>
                            <p class="font-mono font-bold text-lg text-primary">{{ $payment->payment_code }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $payment->status_color }}">
                                {{ $payment->status_label }}
                            </span>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Metode Pembayaran</label>
                            <p class="font-semibold text-gray-800">
                                @if($payment->payment_method === 'cash')
                                <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Cash
                                @elseif($payment->payment_method === 'bank_transfer')
                                <i class="fas fa-university text-purple-500 mr-2"></i>Transfer Bank
                                @else
                                <i class="fas fa-credit-card text-blue-500 mr-2"></i>Online (Xendit)
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Jumlah</label>
                            <p class="text-2xl font-bold text-primary">{{ $payment->formatted_amount }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Dibuat</label>
                            <p class="text-gray-800">{{ $payment->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Bayar</label>
                            <p class="text-gray-800">
                                {{ $payment->paid_at ? $payment->paid_at->translatedFormat('d F Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Santri Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Santri</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama Santri</label>
                            <p class="font-semibold text-gray-800">{{ $payment->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-800">{{ $payment->user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Telepon</label>
                            <p class="text-gray-800">{{ $payment->user->phone_number ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">ID Pendaftaran</label>
                            <p class="font-mono text-gray-800">{{ $payment->registration->id_pendaftaran ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Package Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Paket</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama Paket</label>
                            <p class="font-semibold text-gray-800">{{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tipe Paket</label>
                            <p class="text-gray-800">{{ $payment->registration->package->type_label ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                            <p class="text-gray-800">{{ $payment->registration->package->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Xendit Info (if online payment) -->
                @if($payment->payment_method === 'xendit' && $payment->xendit_response)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Xendit</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Xendit ID</label>
                            <p class="font-mono text-sm text-gray-800 break-all">{{ $payment->xendit_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">External ID</label>
                            <p class="font-mono text-sm text-gray-800 break-all">{{ $payment->xendit_external_id }}</p>
                        </div>
                        @if($payment->xendit_response['invoice_url'] ?? false)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Invoice URL</label>
                            <a href="{{ $payment->xendit_response['invoice_url'] }}"
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 break-all block mt-1">
                                <i class="fas fa-external-link-alt mr-1"></i> {{ $payment->xendit_response['invoice_url'] }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Bank Transfer Info (if bank transfer payment) -->
                @if($payment->payment_method === 'bank_transfer')
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Transfer Bank</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Atas Nama Pengirim</label>
                            <p class="font-semibold text-gray-800">{{ $payment->sender_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status Verifikasi</label>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                @if($payment->status === 'waiting_verification')
                                    bg-yellow-100 text-yellow-800
                                @elseif($payment->status === 'lunas')
                                    bg-green-100 text-green-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                                @if($payment->status === 'waiting_verification')
                                    Menunggu Verifikasi
                                @elseif($payment->status === 'lunas')
                                    Terverifikasi
                                @else
                                    Ditolak
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Payment Proof -->
                    @if($payment->payment_proof)
                    <div class="mt-6 pt-6 border-t">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Bukti Transfer</label>
                        @php
                            $ext = pathinfo($payment->payment_proof, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                            <div class="bg-gray-100 rounded-lg p-4 flex justify-center">
                                <img src="{{ asset('storage/' . $payment->payment_proof) }}" 
                                     alt="Bukti Transfer" 
                                     class="max-h-96 rounded-lg shadow-md cursor-pointer"
                                     onclick="openImageModal('{{ asset('storage/' . $payment->payment_proof) }}')">
                            </div>
                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" 
                               target="_blank"
                               class="inline-block mt-3 text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka di tab baru
                            </a>
                        @elseif(strtolower($ext) === 'pdf')
                            <div class="bg-gray-100 rounded-lg p-6 text-center">
                                <i class="fas fa-file-pdf text-6xl text-red-500 mb-3"></i>
                                <p class="text-gray-700 mb-3">File PDF</p>
                                <a href="{{ asset('storage/' . $payment->payment_proof) }}" 
                                   target="_blank"
                                   class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                    <i class="fas fa-download mr-2"></i> Download PDF
                                </a>
                            </div>
                        @endif
                    </div>
                    @else
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-gray-500 text-sm">Belum ada bukti transfer yang diunggah</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi</h2>

                    @if($payment->payment_method === 'cash' && $payment->isPending())
                    <!-- Update Status Form -->
                    <form action="{{ route('admin.transactions.update-status', $payment) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                            <select name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Status</option>
                                <option value="lunas">Lunas</option>
                                <option value="failed">Gagal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="admin_notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Opsional..."></textarea>
                        </div>
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 rounded-lg hover:bg-secondary transition duration-300 font-semibold">
                            Update Status
                        </button>
                    </form>

                    @elseif($payment->payment_method === 'bank_transfer' && $payment->status === 'waiting_verification')
                    <!-- Verify Bank Transfer -->
                    <div class="space-y-3">
                        <button onclick="verifyBankTransfer()"
                                class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition duration-300 font-semibold flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Verifikasi Transfer Bank
                        </button>
                        <button onclick="openRejectModal()"
                                class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition duration-300 font-semibold flex items-center justify-center gap-2">
                            <i class="fas fa-times-circle"></i>
                            Tolak Pembayaran
                        </button>
                    </div>

                    @else
                    <p class="text-gray-500 text-sm mb-4">Download PDF Untuk Melihat Data Transaksi Calon Santri.</p>
                    @endif

                    <!-- Invoice Buttons -->
                    @if($payment->isPaid())
                    <div class="pt-4 border-t">

                        <div class="flex gap-2">
                            <a href="{{ route('admin.transactions.invoice.pdf', ['paymentCode' => $payment->payment_code]) }}"
                               class="flex-1 bg-green-100 text-green-700 hover:bg-green-200 text-center py-2 rounded-lg transition duration-300 font-medium">
                                <i class="fas fa-download mr-2"></i>PDF
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Manual Sync Button untuk Xendit -->
                    @if($payment->payment_method === 'xendit' && $payment->xendit_id && !$payment->isPaid())
                    <div class="pt-4 border-t">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Sinkronisasi Xendit</h3>
                        <form action="{{ route('admin.transactions.manual-sync', ['paymentCode' => $payment->payment_code]) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-yellow-100 text-yellow-700 hover:bg-yellow-200 py-2 rounded-lg transition duration-300 font-medium">
                                <i class="fas fa-sync-alt mr-2"></i>Cek Status Xendit
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <!-- Admin Notes -->
                @if($payment->admin_notes)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Catatan Admin</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $payment->admin_notes }}</p>
                </div>
                @endif

                <!-- Status History -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Status</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Dibuat</span>
                            <span class="text-xs text-gray-500">{{ $payment->created_at->format('d/m H:i') }}</span>
                        </div>
                        @if($payment->paid_at)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Dibayar</span>
                            <span class="text-xs text-gray-500">{{ $payment->paid_at->format('d/m H:i') }}</span>
                        </div>
                        @endif
                        @if($payment->updated_at->gt($payment->created_at))
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Diupdate</span>
                            <span class="text-xs text-gray-500">{{ $payment->updated_at->format('d/m H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.components.admin.footer')
</div>

<!-- Reject Bank Transfer Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Tolak Pembayaran</h3>
            <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form action="{{ route('admin.transactions.reject-bank-transfer', $payment) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan *
                </label>
                <textarea id="rejection_reason" 
                          name="rejection_reason" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Jelaskan alasan penolakan..."
                          required></textarea>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-sm text-red-700">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>Santri akan menerima notifikasi tentang penolakan ini.</span>
            </div>

            <div class="flex gap-2">
                <button type="button" 
                        onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-times-circle"></i>
                    Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50" onclick="closeImageModal()">
    <div class="relative max-w-3xl max-h-96 mx-4">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="previewImage" src="" alt="Preview" class="max-w-full max-h-96 rounded-lg">
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div id="successMessage" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div id="errorMessage" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

<script>
// Modal Functions
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('previewImage');
    img.src = src;
    modal.classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Verify Bank Transfer
function verifyBankTransfer() {
    if (confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.transactions.verify-bank-transfer", $payment) }}';
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto hide messages
setTimeout(function() {
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');

    if (successMessage) successMessage.style.display = 'none';
    if (errorMessage) errorMessage.style.display = 'none';
}, 5000);
</script>
@endsection

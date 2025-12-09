@extends('layouts.app')

@section('title', 'Undangan Tes Seleksi - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
     @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Undangan Tes Seleksi</h1>
        <p class="text-secondary">Kirim undangan tes seleksi kepada calon santri yang memenuhi syarat</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Calon Santri yang Memenuhi Syarat -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Calon Santri yang Memenuhi Syarat Seleksi</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                            {{ $eligibleRegistrations->count() }} calon santri
                        </span>
                    </div>

                    @if($eligibleRegistrations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">Nama Calon Santri</th>
                                    <th class="px-4 py-3">Status Dokumen</th>
                                    <th class="px-4 py-3">Paket</th>
                                    <th class="px-4 py-3">Telepon</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eligibleRegistrations as $registration)
                                <tr class="border-b hover:bg-gray-50" data-registration-id="{{ $registration->id }}">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $registration->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $registration->id_pendaftaran }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColor = match($registration->status_pendaftaran) {
                                                'diterima' => 'green',
                                                'telah_dilihat' => 'blue',
                                                'menunggu_diverifikasi' => 'yellow',
                                                'perlu_review' => 'orange',
                                                'ditolak' => 'red',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <span class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ $registration->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                                            {{ $registration->package->name ?? 'Tidak ada paket' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs">
                                        {{ $registration->user->phone_number ?? 'Tidak ada telepon' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <button
                                            onclick="openIndividualModal({{ $registration->id }}, '{{ $registration->nama_lengkap }}')"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition duration-200"
                                        >
                                            <i class="fas fa-calendar-alt mr-1"></i> Undang
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada calon santri yang memenuhi syarat seleksi</p>
                        <p class="text-gray-400 text-sm mt-2">
                            @if($sentCount > 0)
                                Semua calon santri yang memenuhi syarat sudah dikirimi undangan
                            @else
                                Syarat: Dokumen lengkap, pembayaran lunas, status belum mengikuti seleksi
                            @endif
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Riwayat Undangan Seleksi -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Undangan Seleksi</h3>

                    @if($announcements->count() > 0)
                    <div class="space-y-4">
                        @foreach($announcements as $announcement)
                        <div class="border-l-4 {{ $announcement->status === 'sent' ? 'border-green-500' : ($announcement->status === 'failed' ? 'border-red-500' : 'border-yellow-500') }} bg-gray-50 p-4 rounded-r">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-gray-800">{{ $announcement->title }}</h4>
                                        @if(is_null($announcement->registration_id))
                                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Bulk</span>
                                        @else
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Individual</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->message, 150) }}</p>
                                    <div class="flex items-center mt-2 text-xs text-gray-500 flex-wrap gap-2">
                                        <span>
                                            <i class="fas fa-users mr-1"></i>
                                            {{ count($announcement->recipients ?? []) }} penerima
                                        </span>
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $announcement->created_at->translatedFormat('d M Y H:i') }}
                                        </span>
                                        <span class="{{ $announcement->status === 'sent' ? 'text-green-600' : ($announcement->status === 'failed' ? 'text-red-600' : 'text-yellow-600') }}">
                                            <i class="fas fa-circle mr-1"></i>
                                            {{ $announcement->status === 'sent' ? 'Terkirim' : ($announcement->status === 'failed' ? 'Gagal' : 'Menunggu') }}
                                        </span>
                                        @if(!is_null($announcement->registration_id) && $announcement->registration)
                                        <span class="text-gray-400">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $announcement->registration->nama_lengkap }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $announcements->links() }}
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada riwayat undangan seleksi</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right: Action Panel -->
            <div class="space-y-6">
                <!-- Bulk Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Massal</h3>

                    <div class="space-y-4">
                        <!-- Kirim ke yang memenuhi syarat -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Kirim Undangan Seleksi</h4>
                            <p class="text-sm text-gray-600 mb-3">Kirim undangan dengan range tanggal ke calon santri yang memenuhi syarat</p>
                            <button
                                onclick="openBulkModal()"
                                class="w-full bg-primary hover:bg-secondary text-white py-2 rounded-lg transition duration-200 flex items-center justify-center"
                                {{ $eligibleRegistrations->count() === 0 ? 'disabled' : '' }}
                            >
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Undangan ({{ $eligibleRegistrations->count() }})
                            </button>
                        </div>

                        <!-- Kirim ke semua santri -->
                        {{-- <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Kirim ke Semua Santri</h4>
                            <p class="text-sm text-gray-600 mb-3">Kirim undangan ke semua user dengan role calon_santri</p>
                            <button
                                onclick="openAllSantriModal()"
                                class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 rounded-lg transition duration-200 flex items-center justify-center"
                            >
                                <i class="fas fa-broadcast-tower mr-2"></i>
                                Kirim ke Semua Santri
                            </button>
                        </div> --}}
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h4 class="font-semibold text-blue-800 mb-3">Syarat Undangan Seleksi</h4>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Status pendaftaran: <strong>Diterima/Telah Dilihat/Menunggu Verifikasi/Perlu Review</strong></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Status seleksi: <strong>Belum Mengikuti Seleksi</strong></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Dokumen lengkap (KK, Ijazah, Akta, Foto)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Pembayaran lunas (Xendit/Cash)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Nomor telepon terdaftar</span>
                        </li>
                    </ul>
                </div>

                <!-- Status Panel -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <h4 class="font-semibold text-green-800 mb-3">Status Pengiriman</h4>
                    <div class="space-y-2 text-sm text-green-700">
                        <div class="flex justify-between">
                            <span>Total Terkirim:</span>
                            <span class="font-semibold">{{ $sentCount }} undangan</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Gagal:</span>
                            <span class="font-semibold">{{ $failedCount }} undangan</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Siap Dikirim:</span>
                            <span class="font-semibold">{{ $eligibleRegistrations->count() }} calon santri</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
     @include('layouts.components.admin.footer')
</div>

<!-- Modal Individual -->
<div id="individualModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Kirim Undangan Individual</h3>
        <form id="individualForm">
            @csrf
            <input type="hidden" id="individualRegistrationId" name="registration_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Calon Santri</label>
                <input type="text" id="individualSantriName" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Seleksi *</label>
                <input type="date" id="individualTanggalSeleksi" name="tanggal_seleksi" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeIndividualModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Kirim Undangan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Bulk -->
<div id="bulkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Kirim Undangan Massal</h3>
        <form id="bulkForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                <input type="date" id="bulkTanggalMulai" name="tanggal_mulai" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                <input type="date" id="bulkTanggalSelesai" name="tanggal_selesai" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4 p-3 bg-blue-50 rounded-md">
                <p class="text-sm text-blue-700">Akan mengirim undangan ke <strong>{{ $eligibleRegistrations->count() }}</strong> calon santri</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeBulkModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-secondary">Kirim Massal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal All Santri -->
<div id="allSantriModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Kirim ke Semua Santri</h3>
        <form id="allSantriForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                <input type="date" id="allSantriTanggalMulai" name="tanggal_mulai" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                <input type="date" id="allSantriTanggalSelesai" name="tanggal_selesai" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4 p-3 bg-purple-50 rounded-md">
                <p class="text-sm text-purple-700">Akan mengirim undangan ke <strong>semua</strong> calon santri terdaftar</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeAllSantriModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">Kirim ke Semua</button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal Functions
function openIndividualModal(registrationId, santriName) {
    document.getElementById('individualRegistrationId').value = registrationId;
    document.getElementById('individualSantriName').value = santriName;
    document.getElementById('individualModal').classList.remove('hidden');
}

function closeIndividualModal() {
    document.getElementById('individualModal').classList.add('hidden');
    document.getElementById('individualForm').reset();
}

function openBulkModal() {
    document.getElementById('bulkModal').classList.remove('hidden');
}

function closeBulkModal() {
    document.getElementById('bulkModal').classList.add('hidden');
    document.getElementById('bulkForm').reset();
}

function openAllSantriModal() {
    document.getElementById('allSantriModal').classList.remove('hidden');
}

function closeAllSantriModal() {
    document.getElementById('allSantriModal').classList.add('hidden');
    document.getElementById('allSantriForm').reset();
}

// Form Submissions
document.getElementById('individualForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const registrationId = document.getElementById('individualRegistrationId').value;
    const formData = new FormData(this);

    sendIndividualSeleksi(registrationId, formData);
});

document.getElementById('bulkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    sendBulkSeleksi(formData);
});

document.getElementById('allSantriForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    sendToAllSantriSeleksi(formData);
});

// API Functions
function sendIndividualSeleksi(registrationId, formData) {
    const button = document.querySelector('#individualForm button[type="submit"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
    button.disabled = true;

    fetch(`/admin/seleksi-announcements/send-individual/${registrationId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Undangan seleksi berhasil dikirim!');
            closeIndividualModal();

            if (data.remove_from_list) {
                const row = document.querySelector(`tr[data-registration-id="${registrationId}"]`);
                if (row) {
                    row.style.opacity = '0.5';
                    setTimeout(() => {
                        row.remove();
                        updateEligibleCount();
                    }, 1000);
                }
            }
        } else {
            showAlert('error', 'Gagal mengirim undangan: ' + data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Terjadi kesalahan: ' + error);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function sendBulkSeleksi(formData) {
    const button = document.querySelector('#bulkForm button[type="submit"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
    button.disabled = true;

    fetch('/admin/seleksi-announcements/send-bulk', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let successMessage = `Berhasil! ${data.data.success} undangan terkirim`;

            if (data.data.failed > 0) {
                successMessage += `, ${data.data.failed} gagal`;
            }

            if (data.data.already_sent > 0) {
                successMessage += `, ${data.data.already_sent} sudah pernah dikirim sebelumnya`;
            }

            showAlert('success', successMessage);
            closeBulkModal();

            if (data.data.successful_registrations && data.data.successful_registrations.length > 0) {
                data.data.successful_registrations.forEach(registrationId => {
                    const row = document.querySelector(`tr[data-registration-id="${registrationId}"]`);
                    if (row) {
                        row.style.opacity = '0.5';
                        setTimeout(() => {
                            row.remove();
                        }, 1000);
                    }
                });

                setTimeout(() => {
                    updateEligibleCount();
                    location.reload();
                }, 1500);
            } else {
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        } else {
            showAlert('error', 'Gagal mengirim undangan: ' + data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Terjadi kesalahan: ' + error);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function sendToAllSantriSeleksi(formData) {
    const button = document.querySelector('#allSantriForm button[type="submit"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
    button.disabled = true;

    fetch('/admin/seleksi-announcements/send-all-santri', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let successMessage = `Berhasil! ${data.data.success} undangan terkirim`;

            if (data.data.failed > 0) {
                successMessage += `, ${data.data.failed} gagal`;
            }

            if (data.data.already_sent > 0) {
                successMessage += `, ${data.data.already_sent} sudah pernah dikirim sebelumnya`;
            }

            showAlert('success', successMessage);
            closeAllSantriModal();
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showAlert('error', 'Gagal mengirim undangan: ' + data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Terjadi kesalahan: ' + error);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Helper Functions
function updateEligibleCount() {
    const countElement = document.querySelector('.bg-blue-100.text-blue-800');
    const table = document.querySelector('tbody');
    const rows = table ? table.querySelectorAll('tr') : [];
    const currentCount = rows.length;

    if (countElement) {
        countElement.textContent = `${currentCount} calon santri`;
    }

    const bulkButton = document.querySelector('button[onclick="openBulkModal()"]');
    if (bulkButton) {
        bulkButton.innerHTML = `<i class="fas fa-paper-plane mr-2"></i> Kirim Undangan (${currentCount})`;
        bulkButton.disabled = currentCount === 0;

        if (currentCount === 0) {
            bulkButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            bulkButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';

    const alertDiv = document.createElement('div');
    alertDiv.className = `border-l-4 p-4 mb-4 rounded ${alertClass} fixed top-4 right-4 z-50 min-w-80`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Mobile menu toggle
document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu) mobileMenu.classList.toggle('hidden');
});

// Set min date untuk input tanggal
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.min = today;
    });

    updateEligibleCount();
});
</script>

<style>
.full-width-page {
    margin: 0;
    padding: 0;
    width: 100%;
}

.nav-container {
    margin-left: 0.5rem;
    margin-right: 0.5rem;
}

@media (min-width: 768px) {
    .nav-container {
        margin-left: 1rem;
        margin-right: 1rem;
    }
}

/* Animation for modal */
.modal-enter {
    animation: modalEnter 0.3s ease-out;
}

@keyframes modalEnter {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Disabled state styling */
button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Row removal animation */
tr {
    transition: all 0.3s ease;
}
</style>
@endsection

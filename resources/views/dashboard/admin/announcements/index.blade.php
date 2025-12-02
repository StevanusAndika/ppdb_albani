@extends('layouts.app')

@section('title', 'Pengumuman Kelulusan - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
   @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Pengumuman Kelulusan</h1>
        <p class="text-secondary">Kirim pesan kelulusan kepada calon santri yang memenuhi syarat</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Calon Santri yang Memenuhi Syarat -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Calon Santri yang Memenuhi Syarat</h3>
                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                            {{ $eligibleRegistrations->count() }} calon santri
                        </span>
                    </div>

                    @if($eligibleRegistrations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">Nama Calon Santri</th>
                                    <th class="px-4 py-3">Status Seleksi</th>
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
                                            $statusSeleksiColor = match($registration->status_seleksi) {
                                                'sudah_mengikuti_seleksi' => 'green',
                                                'belum_mengikuti_seleksi' => 'yellow',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <span class="bg-{{ $statusSeleksiColor }}-100 text-{{ $statusSeleksiColor }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ $registration->status_seleksi_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                            {{ $registration->package->name ?? 'Tidak ada paket' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs">
                                        {{ $registration->user->phone_number ?? 'Tidak ada telepon' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            <button
                                                onclick="sendIndividualMessage({{ $registration->id }})"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition duration-200"
                                            >
                                                <i class="fas fa-paper-plane mr-1"></i> Chat
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada calon santri yang memenuhi syarat</p>
                        <p class="text-gray-400 text-sm mt-2">
                            @if($sentCount > 0)
                                Semua calon santri yang memenuhi syarat sudah dikirimi pesan
                            @else
                                Syarat: Dokumen lengkap, pembayaran lunas, dan status diterima
                            @endif
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Riwayat Pengumuman -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pengumuman</h3>

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
                                    <p class="text-sm text-gray-600 mt-1">{{ $announcement->message }}</p>
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
                        <p class="text-gray-500">Belum ada riwayat pengumuman</p>
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
                        {{-- <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Kirim ke Calon Santri Lolos</h4>
                            <p class="text-sm text-gray-600 mb-3">Kirim pesan ke semua calon santri yang memenuhi syarat</p>
                            <button
                                onclick="sendBulkMessage()"
                                class="w-full bg-primary hover:bg-secondary text-white py-2 rounded-lg transition duration-200 flex items-center justify-center"
                                {{ $eligibleRegistrations->count() === 0 ? 'disabled' : '' }}
                            >
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim ({{ $eligibleRegistrations->count() }})
                            </button>
                        </div> --}}

                        <!-- Kirim ke semua santri -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Kirim ke Semua Santri</h4>
                            <p class="text-sm text-gray-600 mb-3">Kirim pesan ke semua user dengan role calon_santri</p>
                            <button
                                onclick="sendToAllSantri()"
                                class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 rounded-lg transition duration-200 flex items-center justify-center"
                            >
                                <i class="fas fa-broadcast-tower mr-2"></i>
                                Kirim ke Semua
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h4 class="font-semibold text-blue-800 mb-3">Syarat Kelulusan</h4>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Status pendaftaran: <strong>Diterima</strong></span>
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
                            <span class="font-semibold">{{ $sentCount }} pesan</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Gagal:</span>
                            <span class="font-semibold">{{ $failedCount }} pesan</span>
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

<!-- Modal Status Seleksi -->
<div id="statusSeleksiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Update Status Seleksi</h3>
        <form id="statusSeleksiForm">
            @csrf
            <input type="hidden" id="statusSeleksiRegistrationId" name="registration_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Calon Santri</label>
                <input type="text" id="statusSeleksiSantriName" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Seleksi *</label>
                <select id="statusSeleksiSelect" name="status_seleksi" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    <option value="belum_mengikuti_seleksi">Belum Mengikuti Seleksi</option>
                    <option value="sudah_mengikuti_seleksi">Sudah Mengikuti Seleksi</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeStatusSeleksiModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Update Status</button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal Functions untuk Status Seleksi
function openStatusSeleksiModal(registrationId, santriName, currentStatus) {
    document.getElementById('statusSeleksiRegistrationId').value = registrationId;
    document.getElementById('statusSeleksiSantriName').value = santriName;
    document.getElementById('statusSeleksiSelect').value = currentStatus;
    document.getElementById('statusSeleksiModal').classList.remove('hidden');
}

function closeStatusSeleksiModal() {
    document.getElementById('statusSeleksiModal').classList.add('hidden');
    document.getElementById('statusSeleksiForm').reset();
}

// Form Submission untuk Status Seleksi
document.getElementById('statusSeleksiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const registrationId = document.getElementById('statusSeleksiRegistrationId').value;
    const formData = new FormData(this);

    updateStatusSeleksi(registrationId, formData);
});

// API Function untuk Update Status Seleksi
function updateStatusSeleksi(registrationId, formData) {
    const button = document.querySelector('#statusSeleksiForm button[type="submit"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengupdate...';
    button.disabled = true;

    fetch(`/admin/announcements/update-status-seleksi/${registrationId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Status seleksi berhasil diupdate!');
            closeStatusSeleksiModal();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('error', 'Gagal mengupdate status: ' + data.message);
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

// Fungsi-fungsi yang sudah ada sebelumnya (sendIndividualMessage, sendBulkMessage, sendToAllSantri, dll)
function sendIndividualMessage(registrationId) {
    if (!confirm('Kirim pesan kelulusan kepada calon santri ini?')) return;

    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
    button.disabled = true;

    fetch(`/admin/announcements/send-individual/${registrationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Pesan berhasil dikirim! Status seleksi diupdate menjadi "Sudah Mengikuti Seleksi"');

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
            showAlert('error', 'Gagal mengirim pesan: ' + data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Terjadi kesalahan: ' + error);
    })
    .finally(() => {
        button.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> Chat';
        button.disabled = false;
    });
}

function sendBulkMessage() {
    if (!confirm(`Kirim pesan kelulusan ke semua {{ $eligibleRegistrations->count() }} calon santri yang memenuhi syarat? Status seleksi akan diupdate menjadi "Sudah Mengikuti Seleksi".`)) return;

    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
    button.disabled = true;

    fetch('/admin/announcements/send-bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let successMessage = `Berhasil! ${data.data.success} pesan terkirim. Status seleksi diupdate menjadi "Sudah Mengikuti Seleksi"`;

            if (data.data.failed > 0) {
                successMessage += `, ${data.data.failed} gagal`;
            }

            if (data.data.already_sent > 0) {
                successMessage += `, ${data.data.already_sent} sudah pernah dikirim sebelumnya`;
            }

            showAlert('success', successMessage);

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
            showAlert('error', 'Gagal mengirim pesan: ' + data.message);
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        showAlert('error', 'Terjadi kesalahan: ' + error);
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function sendToAllSantri() {
    if (!confirm('Kirim pesan kelulusan ke SEMUA calon santri? Pastikan ini adalah pengumuman resmi. Status seleksi akan diupdate.')) return;

    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
    button.disabled = true;

    fetch('/admin/announcements/send-all-santri', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let successMessage = `Berhasil! ${data.data.success} pesan terkirim`;

            if (data.data.failed > 0) {
                successMessage += `, ${data.data.failed} gagal`;
            }

            if (data.data.already_sent > 0) {
                successMessage += `, ${data.data.already_sent} sudah pernah dikirim sebelumnya`;
            }

            showAlert('success', successMessage);
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showAlert('error', 'Gagal mengirim pesan: ' + data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Terjadi kesalahan: ' + error);
    })
    .finally(() => {
        button.innerHTML = '<i class="fas fa-broadcast-tower mr-2"></i> Kirim ke Semua';
        button.disabled = false;
    });
}

// Helper Functions
function updateEligibleCount() {
    const countElement = document.querySelector('.bg-green-100.text-green-800');
    const table = document.querySelector('tbody');
    const rows = table ? table.querySelectorAll('tr') : [];
    const currentCount = rows.length;

    if (countElement) {
        countElement.textContent = `${currentCount} calon santri`;
    }

    const bulkButton = document.querySelector('button[onclick="sendBulkMessage()"]');
    if (bulkButton) {
        bulkButton.innerHTML = `<i class="fas fa-paper-plane mr-2"></i> Kirim (${currentCount})`;
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

// Update count on page load
document.addEventListener('DOMContentLoaded', function() {
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

.icon-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.transition-all {
    transition: all 0.3s ease;
}

.hover-scale:hover {
    transform: scale(1.05);
}

/* Animation for row removal */
tr {
    transition: all 0.3s ease;
}

/* Disabled state styling */
button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
@endsection

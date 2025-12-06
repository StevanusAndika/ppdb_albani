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
                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full" id="eligible-count">
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
                                    <th class="px-4 py-3">Program Pendidikan</th>
                                    <th class="px-4 py-3">Dokumen</th>
                                    <th class="px-4 py-3">Telepon</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eligibleRegistrations as $registration)
                                @php
                                    $statusSeleksiColor = match($registration->status_seleksi) {
                                        'sudah_mengikuti_seleksi' => 'green',
                                        'belum_mengikuti_seleksi' => 'yellow',
                                        default => 'gray'
                                    };
                                @endphp
                                <tr class="border-b hover:bg-gray-50" data-registration-id="{{ $registration->id }}" id="registration-row-{{ $registration->id }}">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $registration->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $registration->id_pendaftaran }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="bg-{{ $statusSeleksiColor }}-100 text-{{ $statusSeleksiColor }}-800 text-xs px-2 py-1 rounded-full">
                                            {{ $registration->status_seleksi_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                            {{ $registration->program_pendidikan_label ?? $registration->program_pendidikan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            @if($registration->hasAllDocuments())
                                            <span class="text-green-600 text-xs">
                                                <i class="fas fa-check-circle mr-1"></i>Lengkap
                                            </span>
                                            @else
                                            <span class="text-yellow-600 text-xs">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $registration->uploaded_documents_count ?? 0 }}/4
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs">
                                        {{ $registration->user->phone_number ?? 'Tidak ada telepon' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            <button
                                                onclick="sendIndividualMessage({{ $registration->id }})"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition duration-200"
                                                id="send-btn-{{ $registration->id }}"
                                            >
                                                <i class="fas fa-paper-plane mr-1"></i> Kirim
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
                                Syarat: Status diterima, sudah mengikuti seleksi, dokumen lengkap, dan pembayaran lunas
                            @endif
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Riwayat Pengumuman -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pengumuman Kelulusan</h3>

                    @if($announcements->count() > 0)
                    <div class="space-y-4">
                        @foreach($announcements as $announcement)
                        <div class="border-l-4 {{ $announcement->status === 'sent' ? 'border-green-500' : ($announcement->status === 'failed' ? 'border-red-500' : 'border-yellow-500') }} bg-gray-50 p-4 rounded-r">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-gray-800">{{ $announcement->title }}</h4>
                                        @if($announcement->announcement_type === 'summary' || is_null($announcement->registration_id))
                                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Bulk</span>
                                        @else
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Individual</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->message, 150) }}</p>
                                    <div class="flex items-center mt-2 text-xs text-gray-500 flex-wrap gap-2">
                                        <span>
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $announcement->recipient_count ?? count($announcement->recipients ?? []) }} penerima
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
                                        @if($announcement->announcement_type)
                                        <span class="text-gray-400">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ ucfirst($announcement->announcement_type) }}
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
                        <p class="text-gray-500">Belum ada riwayat pengumuman kelulusan</p>
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
                        <!-- Kirim ke semua santri yang sudah mengikuti seleksi -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Kirim ke Semua Santri yang Lulus</h4>
                            <p class="text-sm text-gray-600 mb-3">Kirim pesan ke semua santri yang sudah mengikuti seleksi dan diterima</p>
                            <button
                                onclick="sendToAllSantri()"
                                class="w-full bg-primary hover:bg-secondary text-white py-2 rounded-lg transition duration-200 flex items-center justify-center"
                                id="send-all-btn"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim ke Semua Santri Lulus
                            </button>
                        </div>

                        <!-- Kirim Bulk -->


                <!-- Info Panel -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h4 class="font-semibold text-blue-800 mb-3">Syarat Pengumuman Kelulusan</h4>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Status pendaftaran: <strong>Diterima</strong></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Status seleksi: <strong>Sudah Mengikuti Seleksi</strong></span>
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
                            <span>Nomor telepon terdaftar dan valid</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                            <span>Akun Calon Santri aktif</span>
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
                        <div class="flex justify-between mt-3 pt-3 border-t border-green-200">
                            <span>Success Rate:</span>
                            <span class="font-bold">
                                @if(($sentCount + $failedCount) > 0)
                                    {{ round(($sentCount / ($sentCount + $failedCount)) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
     @include('layouts.components.admin.footer')
</div>

<script>
// Fungsi-fungsi untuk pengiriman pesan
function sendIndividualMessage(registrationId) {
    if (!confirm('Kirim pesan kelulusan kepada calon santri ini?')) return;

    const button = document.getElementById(`send-btn-${registrationId}`);
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
    button.disabled = true;

    fetch(`/admin/announcements/send-individual/${registrationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            return response.text().then(text => {
                console.error('Response bukan JSON:', text.substring(0, 200));
                throw new Error('Server mengembalikan response yang tidak valid');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', 'Pesan kelulusan berhasil dikirim!');

            if (data.remove_from_list) {
                const row = document.getElementById(`registration-row-${registrationId}`);
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
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan: ' + error.message);
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function sendBulkMessage() {
    const eligibleCount = {{ $eligibleRegistrations->count() }};
    if (eligibleCount === 0) {
        showAlert('warning', 'Tidak ada calon santri yang memenuhi syarat');
        return;
    }

    if (!confirm(`Kirim pesan kelulusan ke ${eligibleCount} calon santri yang memenuhi syarat?`)) return;

    const button = document.getElementById('bulk-send-btn');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
    button.disabled = true;

    fetch('/admin/announcements/send-bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            return response.text().then(text => {
                console.error('Response bukan JSON:', text.substring(0, 200));
                throw new Error('Server mengembalikan response yang tidak valid');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            let successMessage = `Berhasil! ${data.data.success} pesan kelulusan terkirim`;

            if (data.data.failed > 0) {
                successMessage += `, ${data.data.failed} gagal`;
            }

            if (data.data.already_sent > 0) {
                successMessage += `, ${data.data.already_sent} sudah pernah dikirim sebelumnya`;
            }

            showAlert('success', successMessage);

            // Refresh page after 2 seconds to update lists
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showAlert('error', 'Gagal mengirim pesan massal: ' + data.message);
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan: ' + error.message);
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function sendToAllSantri() {
    if (!confirm('Kirim pesan kelulusan ke SEMUA santri yang sudah mengikuti seleksi dan diterima? Pastikan ini adalah pengumuman resmi.')) return;

    const button = document.getElementById('send-all-btn');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
    button.disabled = true;

    fetch('/admin/announcements/send-all-santri', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        // Cek jika response bukan JSON (mungkin error HTML)
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            return response.text().then(text => {
                console.error('Response bukan JSON:', text.substring(0, 200));
                throw new Error('Server mengembalikan response yang tidak valid');
            });
        }
        return response.json();
    })
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
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan: ' + error.message);
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Helper Functions
function updateEligibleCount() {
    const rows = document.querySelectorAll('tbody tr');
    const count = rows.length;
    document.getElementById('eligible-count').textContent = `${count} calon santri`;

    // Update bulk button
    const bulkButton = document.getElementById('bulk-send-btn');
    if (count === 0) {
        bulkButton.disabled = true;
        bulkButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Massal';
    } else {
        bulkButton.disabled = false;
        bulkButton.innerHTML = `
            <i class="fas fa-paper-plane mr-2"></i>
            Kirim Massal
            <span class="ml-2 bg-white/20 px-2 py-1 rounded text-xs">
                ${count} penerima
            </span>
        `;
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
                      type === 'error' ? 'bg-red-100 border-red-400 text-red-700' :
                      'bg-yellow-100 border-yellow-400 text-yellow-700';

    const alertDiv = document.createElement('div');
    alertDiv.className = `border-l-4 p-4 mb-4 rounded ${alertClass} fixed top-4 right-4 z-50 min-w-80`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-exclamation-triangle'} mr-2"></i>
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

/* Line clamp for message */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection

@extends('layouts.app')

@section('title', 'Kelola Pendaftaran - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .status-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
    }
    .status-badge.belum_mendaftar { @apply bg-gray-100 text-gray-800; }
    .status-badge.telah_mengisi { @apply bg-blue-100 text-blue-800; }
    .status-badge.telah_dilihat { @apply bg-yellow-100 text-yellow-800; }
    .status-badge.menunggu_diverifikasi { @apply bg-orange-100 text-orange-800; }
    .status-badge.ditolak { @apply bg-red-100 text-red-800; }
    .status-badge.diterima { @apply bg-green-100 text-green-800; }

    .action-btn {
        @apply px-3 py-1 rounded-lg text-sm font-medium transition duration-200;
    }
    .action-btn.view { @apply bg-blue-500 text-white hover:bg-blue-600; }
    .action-btn.verify { @apply bg-green-500 text-white hover:bg-green-600; }
    .action-btn.reject { @apply bg-red-500 text-white hover:bg-red-600; }
    .action-btn.whatsapp { @apply bg-green-500 text-white hover:bg-green-600; }

    .filter-tab {
        @apply px-4 py-2 rounded-lg cursor-pointer transition duration-200;
    }
    .filter-tab.active {
        @apply bg-primary text-white;
    }
    .filter-tab:not(.active) {
        @apply bg-gray-200 text-gray-700 hover:bg-gray-300;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary font-medium">Dashboard</a>
                <a href="{{ route('admin.registrations.index') }}" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="{{ route('admin.manage-users.index') }}" class="text-primary hover:text-secondary font-medium">Kelola User</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-primary mb-2">Kelola Pendaftaran Santri</h1>
            <p class="text-secondary">Kelola dan verifikasi data pendaftaran calon santri</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pendaftaran</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $registrations->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $registrations->where('status_pendaftaran', 'menunggu_diverifikasi')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-check text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Diterima</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $registrations->where('status_pendaftaran', 'diterima')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <i class="fas fa-times text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ditolak</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $registrations->where('status_pendaftaran', 'ditolak')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-2">
                <div class="filter-tab active" data-filter="all">
                    Semua ({{ $registrations->total() }})
                </div>
                <div class="filter-tab" data-filter="menunggu_diverifikasi">
                    Menunggu Verifikasi ({{ $registrations->where('status_pendaftaran', 'menunggu_diverifikasi')->count() }})
                </div>
                <div class="filter-tab" data-filter="diterima">
                    Diterima ({{ $registrations->where('status_pendaftaran', 'diterima')->count() }})
                </div>
                <div class="filter-tab" data-filter="ditolak">
                    Ditolak ({{ $registrations->where('status_pendaftaran', 'ditolak')->count() }})
                </div>
                <div class="filter-tab" data-filter="telah_mengisi">
                    Telah Mengisi ({{ $registrations->where('status_pendaftaran', 'telah_mengisi')->count() }})
                </div>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-4">ID Pendaftaran</th>
                            <th class="px-6 py-4">Nama Santri</th>
                            <th class="px-6 py-4">Paket</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal Daftar</th>
                            <th class="px-6 py-4">Dokumen</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                        <tr class="border-b hover:bg-gray-50 registration-row" data-status="{{ $registration->status_pendaftaran }}">
                            <td class="px-6 py-4">
                                <div class="font-mono text-xs font-bold text-primary">{{ $registration->id_pendaftaran }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $registration->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $registration->user->email }}</div>
                                <div class="text-xs text-gray-500">{{ $registration->nomor_telpon_orang_tua }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $registration->package->name }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">{{ $registration->formatted_total_biaya }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="status-badge {{ $registration->status_pendaftaran }}">
                                    {{ $registration->status_label }}
                                </span>
                                @if($registration->dilihat_pada)
                                <div class="text-xs text-gray-500 mt-1">
                                    Dilihat: {{ $registration->dilihat_pada->translatedFormat('d M Y H:i') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $registration->created_at->translatedFormat('d F Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $registration->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $docCount = 0;
                                    if ($registration->kartu_keluaga_path) $docCount++;
                                    if ($registration->ijazah_path) $docCount++;
                                    if ($registration->akta_kelahiran_path) $docCount++;
                                    if ($registration->pas_foto_path) $docCount++;
                                @endphp
                                <div class="flex items-center">
                                    <span class="text-sm {{ $docCount == 4 ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $docCount }}/4
                                    </span>
                                    @if($docCount == 4)
                                    <i class="fas fa-check-circle text-green-500 ml-2"></i>
                                    @else
                                    <i class="fas fa-exclamation-triangle text-orange-500 ml-2"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('admin.registrations.show', $registration) }}"
                                       class="action-btn view text-center">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>

                                    @if($registration->status_pendaftaran == 'menunggu_diverifikasi')
                                    <button onclick="updateStatus('{{ $registration->id }}', 'diterima')"
                                            class="action-btn verify text-center">
                                        <i class="fas fa-check mr-1"></i> Terima
                                    </button>
                                    <button onclick="showRejectModal('{{ $registration->id }}')"
                                            class="action-btn reject text-center">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                    @endif

                                    @if($registration->status_pendaftaran == 'ditolak')
                                    <button onclick="sendWhatsAppNotification('{{ $registration->id }}')"
                                            class="action-btn whatsapp text-center">
                                        <i class="fab fa-whatsapp mr-1"></i> WhatsApp
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada data pendaftaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination - PERBAIKAN: Gunakan pagination yang benar -->
            @if($registrations->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $registrations->links() }}
            </div>
            @endif
        </div>
    </main>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tolak Pendaftaran</h3>
            <form id="rejectForm">
                @csrf
                <input type="hidden" id="rejectRegistrationId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea id="rejectReason" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Berikan alasan penolakan yang jelas..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200">
                        Tolak Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filter functionality
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Filter rows
            document.querySelectorAll('.registration-row').forEach(row => {
                if (filter === 'all' || row.dataset.status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Update status function
    function updateStatus(registrationId, status) {
        const message = status === 'diterima'
            ? 'Apakah Anda yakin ingin menerima pendaftaran ini?'
            : 'Apakah Anda yakin ingin menolak pendaftaran ini?';

        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'diterima' ? '#10b981' : '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: status === 'diterima' ? 'Ya, Terima' : 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/registrations/${registrationId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: status,
                        catatan: status === 'ditolak' ? 'Data tidak memenuhi persyaratan' : null
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memperbarui status.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    // Reject modal functions
    function showRejectModal(registrationId) {
        document.getElementById('rejectRegistrationId').value = registrationId;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectReason').value = '';
    }

    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const registrationId = document.getElementById('rejectRegistrationId').value;
        const reason = document.getElementById('rejectReason').value;

        if (!reason.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Harap berikan alasan penolakan.',
                confirmButtonText: 'OK'
            });
            return;
        }

        fetch(`/admin/registrations/${registrationId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: 'ditolak',
                catatan: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeRejectModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menolak pendaftaran.',
                confirmButtonText: 'OK'
            });
        });
    });

    // WhatsApp notification
    function sendWhatsAppNotification(registrationId) {
        Swal.fire({
            title: 'Kirim Notifikasi WhatsApp',
            text: 'Kirim pesan WhatsApp kepada calon santri?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#25D366',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/registrations/${registrationId}/send-notification`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: 'Silakan periksa status pendaftaran Anda di sistem PPDB Pondok Pesantren Bani Syahid. Terima kasih.'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengirim notifikasi.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    // Close modal on outside click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endsection

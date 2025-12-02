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
    .status-badge.perlu_review { @apply bg-purple-100 text-purple-800; }

    .action-btn {
        @apply px-3 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center space-x-1;
    }
    .action-btn.view { @apply bg-blue-500 text-white hover:bg-blue-600; }
    .action-btn.setuju { @apply bg-green-500 text-white hover:bg-green-600; }
    .action-btn.tolak { @apply bg-red-500 text-white hover:bg-red-600; }
    .action-btn.whatsapp { @apply bg-green-500 text-white hover:bg-green-600; }

    .filter-tab {
        @apply px-4 py-2 rounded-lg cursor-pointer transition duration-200 text-sm;
    }
    .filter-tab.active {
        @apply bg-primary text-white;
    }
    .filter-tab:not(.active) {
        @apply bg-gray-200 text-gray-700 hover:bg-gray-300;
    }

    .document-progress {
        @apply w-full bg-gray-200 rounded-full h-2;
    }
    .document-progress-bar {
        @apply h-2 rounded-full transition-all duration-300;
    }

    .needs-review-badge {
        @apply inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full ml-2;
    }

    @media (max-width: 768px) {
        .table-responsive {
            @apply block w-full overflow-x-auto;
        }
        .action-buttons {
            @apply flex flex-col space-y-1;
        }
        .filter-tabs {
            @apply flex overflow-x-auto space-x-2 pb-2;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
      @include('layouts.components.admin.navbar')

    <main class="max-w-7xl mx-auto py-6 px-3 md:px-4">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Kelola Pendaftaran Santri</h1>
            <p class="text-secondary text-sm md:text-base">Kelola dan verifikasi data pendaftaran calon santri</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-2 md:p-3">
                        <i class="fas fa-users text-white text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Total</p>
                        <p class="text-xl md:text-2xl font-semibold text-gray-900">{{ $registrations->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-2 md:p-3">
                        <i class="fas fa-clock text-white text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Menunggu</p>
                        <p class="text-xl md:text-2xl font-semibold text-gray-900">{{ $registrations->where('status_pendaftaran', 'menunggu_diverifikasi')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-2 md:p-3">
                        <i class="fas fa-redo text-white text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Perlu Review</p>
                        <p class="text-xl md:text-2xl font-semibold text-gray-900">{{ $registrations->where('status_pendaftaran', 'perlu_review')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-2 md:p-3">
                        <i class="fas fa-check text-white text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Diterima</p>
                        <p class="text-xl md:text-2xl font-semibold text-gray-900">{{ $registrations->where('status_pendaftaran', 'diterima')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-4 md:p-6 mb-6">
            <div class="filter-tabs flex space-x-2 overflow-x-auto pb-2">
                <div class="filter-tab active whitespace-nowrap" data-filter="all">
                    Semua ({{ $registrations->total() }})
                </div>
                <div class="filter-tab whitespace-nowrap" data-filter="menunggu_diverifikasi">
                    Menunggu ({{ $registrations->where('status_pendaftaran', 'menunggu_diverifikasi')->count() }})
                </div>
                <div class="filter-tab whitespace-nowrap" data-filter="perlu_review">
                    Perlu Review ({{ $registrations->where('status_pendaftaran', 'perlu_review')->count() }})
                </div>
                <div class="filter-tab whitespace-nowrap" data-filter="diterima">
                    Diterima ({{ $registrations->where('status_pendaftaran', 'diterima')->count() }})
                </div>
                <div class="filter-tab whitespace-nowrap" data-filter="ditolak">
                    Ditolak ({{ $registrations->where('status_pendaftaran', 'ditolak')->count() }})
                </div>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="table-responsive">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">ID & Nama</th>
                            <th class="px-4 py-3 hidden md:table-cell">Paket & Program</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 hidden lg:table-cell">Dokumen</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                        <tr class="border-b hover:bg-gray-50 registration-row" data-status="{{ $registration->status_pendaftaran }}">
                            <td class="px-4 py-3">
                                <div class="font-mono text-xs font-bold text-primary">{{ $registration->id_pendaftaran }}</div>
                                <div class="font-medium text-gray-900 text-sm">{{ Str::limit($registration->nama_lengkap, 20) }}</div>
                                <div class="text-xs text-gray-500">{{ $registration->user->email }}</div>
                                <div class="text-xs text-gray-500 md:hidden">
                                    {{ $registration->package->name }}
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $registration->package->name }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">{{ $registration->program_unggulan_name }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <span class="status-badge {{ $registration->status_pendaftaran }} text-xs">
                                        {{ $registration->status_label }}
                                    </span>
                                    @if($registration->needs_re_review)
                                    <span class="needs-review-badge" title="Data telah diperbarui setelah penolakan">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Perlu Review
                                    </span>
                                    @endif
                                </div>
                                @if($registration->dilihat_pada)
                                <div class="text-xs text-gray-500 mt-1 hidden lg:block">
                                    Dilihat: {{ $registration->dilihat_pada->translatedFormat('d M Y H:i') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell">
                                @php
                                    $docCount = $registration->uploaded_documents_count;
                                    $progressPercentage = ($docCount / 4) * 100;
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm {{ $docCount == 4 ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $docCount }}/4
                                    </span>
                                    <div class="document-progress w-16">
                                        <div class="document-progress-bar {{ $docCount == 4 ? 'bg-green-500' : 'bg-orange-500' }}"
                                             style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="action-buttons flex flex-col space-y-1">
                                    <a href="{{ route('admin.registrations.show', $registration) }}"
                                       class="action-btn view">
                                        <i class="fas fa-eye"></i>
                                        <span>Detail</span>
                                    </a>

                                    <!-- Tombol Setuju dan Tolak muncul untuk status menunggu dan perlu review -->
                                    {{-- @if(in_array($registration->status_pendaftaran, ['menunggu_diverifikasi', 'perlu_review']))
                                    <button onclick="updateStatus('{{ $registration->id }}', 'diterima')"
                                            class="action-btn setuju"
                                            {{ !$registration->is_documents_complete || !$registration->is_biodata_complete || !$registration->has_successful_payment ? 'disabled' : '' }}>
                                        <i class="fas fa-check"></i>
                                        <span>Setuju</span>
                                    </button>
                                    <button onclick="showRejectModal('{{ $registration->id }}')"
                                            class="action-btn tolak">
                                        <i class="fas fa-times"></i>
                                        <span>Tolak</span>
                                    </button>
                                    @endif --}}

                                    @if($registration->status_pendaftaran == 'ditolak' && !$registration->needs_re_review)
                                    <button onclick="sendWhatsAppNotification('{{ $registration->id }}')"
                                            class="action-btn whatsapp">
                                        <i class="fab fa-whatsapp"></i>
                                        <span>WhatsApp</span>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada data pendaftaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($registrations->hasPages())
            <div class="px-4 md:px-6 py-4 bg-gray-50 border-t">
                {{ $registrations->links() }}
            </div>
            @endif
        </div>

    </main>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
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
      @include('layouts.components.admin.footer')
</div>

@endsection

@section('scripts')
<script>
    // Filter functionality
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.dataset.filter;

            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

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
            ? 'Apakah Anda yakin ingin menyetujui pendaftaran ini?'
            : 'Apakah Anda yakin ingin menolak pendaftaran ini?';

        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'diterima' ? '#10b981' : '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: status === 'diterima' ? 'Ya, Setuju' : 'Ya, Tolak',
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

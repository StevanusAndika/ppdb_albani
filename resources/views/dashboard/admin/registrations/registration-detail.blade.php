@extends('layouts.app')

@section('title', 'Detail Pendaftaran - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .detail-section {
        @apply bg-white rounded-xl shadow-md p-6 mb-6;
    }
    .detail-section-title {
        @apply text-xl font-bold text-primary mb-4 pb-2 border-b border-gray-200;
    }
    .info-grid {
        @apply grid grid-cols-1 md:grid-cols-2 gap-4;
    }
    .info-item {
        @apply mb-3;
    }
    .info-label {
        @apply block text-sm font-medium text-gray-700 mb-1;
    }
    .info-value {
        @apply block text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200;
    }
    .status-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
    }
    .status-badge.belum_mendaftar { @apply bg-gray-100 text-gray-800; }
    .status-badge.telah_mengisi { @apply bg-blue-100 text-blue-800; }
    .status-badge.telah_dilihat { @apply bg-yellow-100 text-yellow-800; }
    .status-badge.menunggu_diverifikasi { @apply bg-orange-100 text-orange-800; }
    .status-badge.ditolak { @apply bg-red-100 text-red-800; }
    .status-badge.diterima { @apply bg-green-100 text-green-800; }

    .document-item {
        @apply flex items-center justify-between p-3 border border-gray-200 rounded-lg mb-2;
    }
    .document-status {
        @apply inline-flex items-center px-2 py-1 rounded-full text-xs;
    }
    .document-status.uploaded { @apply bg-green-100 text-green-800; }
    .document-status.missing { @apply bg-red-100 text-red-800; }

    .action-btn {
        @apply px-4 py-2 rounded-lg font-medium transition duration-200;
    }
    .btn-primary { @apply bg-primary text-white hover:bg-secondary; }
    .btn-success { @apply bg-green-500 text-white hover:bg-green-600; }
    .btn-danger { @apply bg-red-500 text-white hover:bg-red-600; }
    .btn-warning { @apply bg-yellow-500 text-white hover:bg-yellow-600; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
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

    <main class="max-w-6xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-primary mb-2">Detail Pendaftaran Santri</h1>
                <div class="flex items-center space-x-4">
                    <span class="status-badge {{ $registration->status_pendaftaran }} text-sm">
                        {{ $registration->status_label }}
                    </span>
                    <span class="text-sm text-gray-600">
                        ID: <strong class="font-mono">{{ $registration->id_pendaftaran }}</strong>
                    </span>
                    <span class="text-sm text-gray-600">
                        Dibuat: {{ $registration->created_at->translatedFormat('d F Y H:i') }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.registrations.index') }}"
                   class="action-btn bg-gray-500 text-white hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Status & Actions -->
        <div class="detail-section">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Status Pendaftaran</h2>
                    <p class="text-gray-600">Kelola status pendaftaran calon santri</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($registration->status_pendaftaran == 'menunggu_diverifikasi')
                    <button onclick="updateStatus('diterima')"
                            class="action-btn btn-success">
                        <i class="fas fa-check mr-2"></i> Terima
                    </button>
                    <button onclick="showRejectModal()"
                            class="action-btn btn-danger">
                        <i class="fas fa-times mr-2"></i> Tolak
                    </button>
                    @endif

                    @if($registration->status_pendaftaran == 'ditolak')
                    <button onclick="sendWhatsAppNotification()"
                            class="action-btn bg-green-500 text-white hover:bg-green-600">
                        <i class="fab fa-whatsapp mr-2"></i> Kirim WhatsApp
                    </button>
                    @endif

                    <button onclick="markAsPending()"
                            class="action-btn btn-warning">
                        <i class="fas fa-clock mr-2"></i> Tandai Menunggu
                    </button>
                </div>
            </div>

            @if($registration->status_pendaftaran == 'ditolak' && $registration->catatan_admin)
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-3"></i>
                    <div>
                        <p class="font-medium text-red-800">Alasan Penolakan:</p>
                        <p class="text-red-600 mt-1">{{ $registration->catatan_admin }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Pribadi Santri -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Pribadi Santri</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value">{{ $registration->nama_lengkap }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NIK</span>
                            <span class="info-value font-mono">{{ $registration->nik }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tempat, Tanggal Lahir</span>
                            <span class="info-value">{{ $registration->tempat_lahir }}, {{ $registration->tanggal_lahir->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jenis Kelamin</span>
                            <span class="info-value">{{ ucfirst($registration->jenis_kelamin) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Agama</span>
                            <span class="info-value">{{ ucfirst($registration->agama) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kebangsaan</span>
                            <span class="info-value">{{ $registration->kebangsaan }}</span>
                        </div>
                        <div class="info-item md:col-span-2">
                            <span class="info-label">Alamat Tinggal</span>
                            <span class="info-value">{{ $registration->alamat_tinggal }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">RT/RW</span>
                            <span class="info-value">{{ $registration->rt }}/{{ $registration->rw }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelurahan</span>
                            <span class="info-value">{{ $registration->kelurahan }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kecamatan</span>
                            <span class="info-value">{{ $registration->kecamatan }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kota</span>
                            <span class="info-value">{{ $registration->kota }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kode Pos</span>
                            <span class="info-value">{{ $registration->kode_pos }}</span>
                        </div>
                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Orang Tua</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Ibu Kandung</span>
                            <span class="info-value">{{ $registration->nama_ibu_kandung }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nama Ayah Kandung</span>
                            <span class="info-value">{{ $registration->nama_ayah_kandung }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pekerjaan Ibu</span>
                            <span class="info-value">{{ $registration->pekerjaan_ibu }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pekerjaan Ayah</span>
                            <span class="info-value">{{ $registration->pekerjaan_ayah }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Penghasilan Ibu</span>
                            <span class="info-value">
                                @if($registration->penghasilan_ibu)
                                    Rp {{ number_format($registration->penghasilan_ibu, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Penghasilan Ayah</span>
                            <span class="info-value">
                                @if($registration->penghasilan_ayah)
                                    Rp {{ number_format($registration->penghasilan_ayah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status Orang Tua</span>
                            <span class="info-value">{{ ucfirst(str_replace('_', ' ', $registration->status_orang_tua)) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nomor Telepon Orang Tua</span>
                            <span class="info-value">{{ $registration->nomor_telpon_orang_tua }}</span>
                        </div>
                    </div>
                </div>

                <!-- Data Pendidikan -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Pendidikan</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Jenjang Pendidikan Terakhir</span>
                            <span class="info-value">{{ $registration->jenjang_pendidikan_terakhir }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nama Sekolah/Pondok Terakhir</span>
                            <span class="info-value">{{ $registration->nama_sekolah_terakhir }}</span>
                        </div>
                        <div class="info-item md:col-span-2">
                            <span class="info-label">Alamat Sekolah/Pondok Terakhir</span>
                            <span class="info-value">{{ $registration->alamat_sekolah_terakhir }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NIS/NISN/NSP</span>
                            <span class="info-value">{{ $registration->nis_nisn_nsp ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Data Kesehatan -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Kesehatan</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Golongan Darah</span>
                            <span class="info-value">{{ $registration->golongan_darah ?: '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Alergi Obat</span>
                            <span class="info-value">{{ $registration->alergi_obat ?: 'Tidak ada' }}</span>
                        </div>
                        <div class="info-item md:col-span-2">
                            <span class="info-label">Penyakit Kronis</span>
                            <span class="info-value">{{ $registration->penyakit_kronis ?: 'Tidak ada' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Data Wali -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Wali</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Wali</span>
                            <span class="info-value">{{ $registration->nama_wali }}</span>
                        </div>
                        <div class="info-item md:col-span-2">
                            <span class="info-label">Alamat Wali</span>
                            <span class="info-value">{{ $registration->alamat_wali }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">RT/RW Wali</span>
                            <span class="info-value">{{ $registration->rt_wali }}/{{ $registration->rw_wali }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelurahan Wali</span>
                            <span class="info-value">{{ $registration->kelurahan_wali }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kecamatan Wali</span>
                            <span class="info-value">{{ $registration->kecamatan_wali }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kota Wali</span>
                            <span class="info-value">{{ $registration->kota_wali }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kode Pos Wali</span>
                            <span class="info-value">{{ $registration->kode_pos_wali }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nomor Telepon Wali</span>
                            <span class="info-value">{{ $registration->nomor_telpon_wali }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Paket & Biaya -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Paket & Biaya</h2>
                    <div class="mb-4">
                        <span class="info-label">Paket Dipilih</span>
                        <span class="info-value bg-blue-50 border-blue-200">{{ $registration->package->name }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="info-label">Detail Biaya</span>
                        <div class="space-y-2 mt-2">
                            @foreach($registration->package->prices as $price)
                            <div class="flex justify-between text-sm">
                                <span>{{ $price->item_name }}</span>
                                <span class="font-medium">Rp {{ number_format($price->amount, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t pt-3">
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total Biaya</span>
                            <span class="text-primary">{{ $registration->formatted_total_biaya }}</span>
                        </div>
                    </div>
                </div>

                <!-- Dokumen -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Dokumen</h2>
                    <div class="space-y-3">
                        @php
                            $documents = [
                                'kartu_keluarga' => [
                                    'name' => 'Kartu Keluarga',
                                    'path' => $registration->kartu_keluaga_path,
                                    'icon' => 'fas fa-id-card'
                                ],
                                'ijazah' => [
                                    'name' => 'Ijazah',
                                    'path' => $registration->ijazah_path,
                                    'icon' => 'fas fa-graduation-cap'
                                ],
                                'akta_kelahiran' => [
                                    'name' => 'Akta Kelahiran',
                                    'path' => $registration->akta_kelahiran_path,
                                    'icon' => 'fas fa-birthday-cake'
                                ],
                                'pas_foto' => [
                                    'name' => 'Pas Foto',
                                    'path' => $registration->pas_foto_path,
                                    'icon' => 'fas fa-camera'
                                ]
                            ];
                        @endphp

                        @foreach($documents as $type => $doc)
                        <div class="document-item">
                            <div class="flex items-center">
                                <i class="{{ $doc['icon'] }} text-gray-500 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $doc['name'] }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if($doc['path'])
                                            {{ basename($doc['path']) }}
                                        @else
                                            Belum diunggah
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div>
                                @if($doc['path'])
                                <a href="{{ Storage::url($doc['path']) }}" target="_blank"
                                   class="text-blue-500 hover:text-blue-700 mr-2" title="Lihat Dokumen">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <span class="document-status uploaded">
                                    <i class="fas fa-check mr-1"></i> Terunggah
                                </span>
                                @else
                                <span class="document-status missing">
                                    <i class="fas fa-times mr-1"></i> Belum
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Progress Dokumen -->
                    @php
                        $uploadedCount = 0;
                        if ($registration->kartu_keluaga_path) $uploadedCount++;
                        if ($registration->ijazah_path) $uploadedCount++;
                        if ($registration->akta_kelahiran_path) $uploadedCount++;
                        if ($registration->pas_foto_path) $uploadedCount++;
                        $progressPercentage = ($uploadedCount / 4) * 100;
                    @endphp

                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Kelengkapan Dokumen</span>
                            <span>{{ $uploadedCount }}/4 ({{ $progressPercentage }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full transition-all duration-300"
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Akun -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Informasi Akun</h2>
                    <div class="space-y-3">
                        <div>
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $registration->user->email }}</span>
                        </div>
                        <div>
                            <span class="info-label">Nomor Telepon</span>
                            <span class="info-value">{{ $registration->user->phone_number ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="info-label">Tanggal Daftar Akun</span>
                            <span class="info-value">{{ $registration->user->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Catatan Admin -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Catatan Admin</h2>
                    <form id="adminNotesForm">
                        @csrf
                        <textarea name="catatan_admin" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Tambah catatan untuk pendaftaran ini...">{{ old('catatan_admin', $registration->catatan_admin) }}</textarea>
                        <button type="submit"
                                class="mt-2 w-full action-btn btn-primary">
                            <i class="fas fa-save mr-2"></i> Simpan Catatan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Tolak Pendaftaran</h3>
        <form id="rejectForm">
            @csrf
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
@endsection

@section('scripts')
<script>
    // Update status function
    function updateStatus(status) {
        const message = status === 'diterima'
            ? 'Apakah Anda yakin ingin menerima pendaftaran ini?'
            : 'Apakah Anda yakin ingin menolak pendaftaran ini?';

        const actionText = status === 'diterima' ? 'menerima' : 'menolak';

        Swal.fire({
            title: 'Konfirmasi',
            text: `Anda akan ${actionText} pendaftaran ${$registration->nama_lengkap}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'diterima' ? '#10b981' : '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: status === 'diterima' ? 'Ya, Terima' : 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ route('admin.registrations.update-status', $registration->id) }}`, {
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
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectReason').value = '';
    }

    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();

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

        fetch(`{{ route('admin.registrations.update-status', $registration->id) }}`, {
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

    // Mark as pending
    function markAsPending() {
        Swal.fire({
            title: 'Tandai Menunggu Verifikasi',
            text: 'Ubah status menjadi menunggu verifikasi?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Tandai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ route('admin.registrations.update-status', $registration->id) }}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: 'menunggu_diverifikasi'
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

    // WhatsApp notification
    function sendWhatsAppNotification() {
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
                fetch(`{{ route('admin.registrations.send-notification', $registration->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: `Kepada Yth. Orang Tua/Wali Santri {{ $registration->nama_lengkap }},\n\nDengan hormat, kami sampaikan bahwa pendaftaran calon santri *{{ $registration->nama_lengkap }}* memerlukan perbaikan data. Silakan login ke sistem PPDB untuk melengkapi data yang diperlukan.\n\nTerima kasih atas perhatiannya.\n\nSalam,\nPanitia PPDB Pondok Pesantren Bani Syahid`
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

    // Save admin notes
    document.getElementById('adminNotesForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(`{{ route('admin.registrations.update-status', $registration->id) }}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Catatan berhasil disimpan.',
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
                text: 'Terjadi kesalahan saat menyimpan catatan.',
                confirmButtonText: 'OK'
            });
        });
    });

    // Close modal on outside click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endsection

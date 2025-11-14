 @extends('layouts.app')
@section('title', 'Detail Pendaftaran - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .detail-section {
        @apply bg-white rounded-xl shadow-md p-4 md:p-6 mb-4 md:mb-6;
    }
    .detail-section-title {
        @apply text-lg md:text-xl font-bold text-primary mb-3 md:mb-4 pb-2 border-b border-gray-200;
    }
    .info-grid {
        @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4;
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
    .status-badge.perlu_review { @apply bg-purple-100 text-purple-800; }

    .document-item {
        @apply flex items-center justify-between p-3 border border-gray-200 rounded-lg mb-2;
    }
    .document-status {
        @apply inline-flex items-center px-2 py-1 rounded-full text-xs;
    }
    .document-status.uploaded { @apply bg-green-100 text-green-800; }
    .document-status.missing { @apply bg-red-100 text-red-800; }

    .action-btn {
        @apply px-3 md:px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center space-x-2 text-sm md:text-base;
    }
    .btn-primary { @apply bg-primary text-white hover:bg-secondary; }
    .btn-success { @apply bg-green-500 text-white hover:bg-green-600; }
    .btn-danger { @apply bg-red-500 text-white hover:bg-red-600; }
    .btn-warning { @apply bg-yellow-500 text-white hover:bg-yellow-600; }
    .btn-info { @apply bg-blue-500 text-white hover:bg-blue-600; }
    .btn-secondary { @apply bg-gray-500 text-white hover:bg-gray-600; }

    .requirement-item {
        @apply flex items-center space-x-2 p-2 rounded-lg border;
    }
    .requirement-met { @apply bg-green-50 border-green-200 text-green-800; }
    .requirement-not-met { @apply bg-red-50 border-red-200 text-red-800; }

    .whatsapp-btn {
        @apply bg-green-500 text-white hover:bg-green-600;
    }

    .needs-review-badge {
        @apply inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full ml-2;
    }

    .current-status {
        @apply bg-primary text-white px-3 py-2 rounded-lg text-center mb-3;
    }

    .document-icon {
        @apply text-xl mr-3;
    }
    .document-icon.kartu_keluarga { @apply text-blue-500; }
    .document-icon.ijazah { @apply text-green-500; }
    .document-icon.akta_kelahiran { @apply text-purple-500; }
    .document-icon.pas_foto { @apply text-orange-500; }

    .modal-content {
         @apply bg-white rounded-xl max-w-4xl max-h-full w-full mx-4;
    }
    .modal-header {
     @apply flex justify-between items-center p-4 border-b;
     }

    .modal-body {
         @apply p-4 max-h-96 overflow-auto;
    }
    .modal-footer {
        @apply p-4 border-t flex justify-end space-x-3;
    }

    /* Debug info untuk troubleshooting */
    .debug-info {
        @apply bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4 text-sm;
    }

    @media (max-width: 768px) {
        .mobile-stack {
            @apply flex flex-col space-y-4;
        }
        .action-buttons {
            @apply grid grid-cols-2 gap-2;
        }
        .info-grid {
            @apply grid-cols-1;
        }
        .modal-content {
            @apply mx-2;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-3 px-4 md:px-6 rounded-xl mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-2 md:space-y-0">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>
            <div class="flex flex-wrap gap-2 md:gap-4 items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary font-medium text-sm">Dashboard</a>
                <a href="{{ route('admin.registrations.index') }}" class="text-primary hover:text-secondary font-medium text-sm">Pendaftaran</a>
                <a href="{{ route('admin.manage-users.index') }}" class="text-primary hover:text-secondary font-medium text-sm">Kelola User</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full transition duration-300 text-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-3 md:px-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Detail Pendaftaran Santri</h1>
                <div class="flex flex-wrap items-center gap-2 md:gap-4">
                    <span class="status-badge {{ $registration->status_pendaftaran }} text-xs md:text-sm">
                        {{ $registration->status_label }}
                    </span>
                    @if($registration->needs_re_review)
                    <span class="needs-review-badge" title="Data telah diperbarui setelah penolakan">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Perlu Review
                    </span>
                    @endif
                    <span class="text-xs md:text-sm text-gray-600">
                        ID: <strong class="font-mono">{{ $registration->id_pendaftaran }}</strong>
                    </span>
                    <span class="text-xs md:text-sm text-gray-600">
                        Dibuat: {{ $registration->created_at->translatedFormat('d M Y H:i') }}
                    </span>
                    @if($registration->dilihat_pada)
                    <span class="text-xs md:text-sm text-gray-600">
                        Dilihat: {{ $registration->dilihat_pada->translatedFormat('d M Y H:i') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.registrations.index') }}"
                   class="action-btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden md:inline">Kembali</span>
                </a>
            </div>
        </div>

        <!-- Status & Actions & Requirements -->
        <div class="detail-section">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
                <!-- Status & Requirements -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Persyaratan Pendaftaran</h3>
                    <div class="space-y-2">
                        <div class="requirement-item {{ $registration->is_documents_complete ? 'requirement-met' : 'requirement-not-met' }}">
                            <i class="fas {{ $registration->is_documents_complete ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }}"></i>
                            <span class="text-sm">Dokumen Lengkap ({{ $registration->uploaded_documents_count }}/4)</span>
                        </div>
                        <div class="requirement-item {{ $registration->is_biodata_complete ? 'requirement-met' : 'requirement-not-met' }}">
                            <i class="fas {{ $registration->is_biodata_complete ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }}"></i>
                            <span class="text-sm">Biodata Lengkap</span>
                        </div>
                        <div class="requirement-item {{ $registration->has_successful_payment ? 'requirement-met' : 'requirement-not-met' }}">
                            <i class="fas {{ $registration->has_successful_payment ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }}"></i>
                            <span class="text-sm">Pembayaran Lunas</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col space-y-2">
                    <!-- Status Saat Ini -->
                    <div class="current-status">
                        <span class="text-sm font-medium">Status Saat Ini:</span>
                        <div class="font-bold">{{ $registration->status_label }}</div>
                    </div>

                    <!-- PERBAIKAN: Tombol Terima dan Tolak - SELALU AKTIF TAPI DENGAN VALIDASI -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Tombol Terima - SELALU AKTIF, validasi di backend -->
                        <button onclick="updateStatus('diterima')"
                                class="action-btn btn-success"
                                title="Terima pendaftaran ini">
                            <i class="fas fa-check"></i>
                            <span>Terima</span>
                        </button>

                        <!-- Tombol Tolak - SELALU AKTIF -->
                        <button onclick="showRejectModal()"
                                class="action-btn btn-danger"
                                title="Tolak pendaftaran ini">
                            <i class="fas fa-times"></i>
                            <span>Tolak</span>
                        </button>
                    </div>

                    <!-- Tombol Tambahan Berdasarkan Status -->
                    @if($registration->status_pendaftaran === 'ditolak' && !$registration->needs_re_review)
                    <button onclick="sendWhatsAppNotification()"
                            class="action-btn whatsapp-btn w-full"
                            title="Kirim notifikasi WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                        <span>Kirim WhatsApp</span>
                    </button>
                    @endif

                    @if($registration->status_pendaftaran === 'perlu_review')
                    <button onclick="updateStatus('menunggu_diverifikasi')"
                            class="action-btn btn-warning w-full"
                            title="Reset status ke menunggu verifikasi">
                        <i class="fas fa-redo"></i>
                        <span>Reset ke Menunggu</span>
                    </button>
                    @endif

                    @if($registration->status_pendaftaran === 'telah_mengisi')
                    <button onclick="updateStatus('telah_dilihat')"
                            class="action-btn btn-info w-full"
                            title="Tandai sebagai telah dilihat">
                        <i class="fas fa-eye"></i>
                        <span>Tandai Dilihat</span>
                    </button>
                    @endif

                    @if(in_array($registration->status_pendaftaran, ['diterima', 'ditolak', 'menunggu_diverifikasi']))
                    <button onclick="updateStatus('perlu_review')"
                            class="action-btn btn-warning w-full"
                            title="Tandai perlu review ulang">
                        <i class="fas fa-undo"></i>
                        <span>Perlu Review</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-4 md:space-y-6">
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
                            <span class="info-label">Program Unggulan</span>
                            <span class="info-value bg-yellow-50 border-yellow-200">
                                <strong>{{ $registration->program_unggulan_name }}</strong>
                                @if($registration->program_unggulan_description)
                                <br><span class="text-xs text-gray-600">{{ $registration->program_unggulan_description }}</span>
                                @endif
                            </span>
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
                        <div class="info-item md:col-span-2 lg:col-span-3">
                            <span class="info-label">Alamat Tinggal</span>
                            <span class="info-value">{{ $registration->alamat_tinggal }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">RT/RW</span>
                            <span class="info-value">{{ $registration->rt ?: '-' }}/{{ $registration->rw ?: '-' }}</span>
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
                        <div class="info-item md:col-span-2 lg:col-span-3">
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
                        <div class="info-item md:col-span-2 lg:col-span-3">
                            <span class="info-label">Penyakit Kronis</span>
                            <span class="info-value">{{ $registration->penyakit_kronis ?: 'Tidak ada' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Data Wali -->
                @if($registration->nama_wali)
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Wali</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Wali</span>
                            <span class="info-value">{{ $registration->nama_wali }}</span>
                        </div>
                        <div class="info-item md:col-span-2 lg:col-span-3">
                            <span class="info-label">Alamat Wali</span>
                            <span class="info-value">{{ $registration->alamat_wali }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">RT/RW Wali</span>
                            <span class="info-value">{{ $registration->rt_wali ?: '-' }}/{{ $registration->rw_wali ?: '-' }}</span>
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
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-4 md:space-y-6">
                <!-- Paket & Biaya -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Paket & Biaya</h2>
                    <div class="mb-4">
                        <span class="info-label">Paket Dipilih</span>
                        <span class="info-value bg-blue-50 border-blue-200">{{ $registration->package->name }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="info-label">Detail Biaya</span>
                        <div class="space-y-2 mt-2 max-h-40 overflow-y-auto">
                            @foreach($registration->package->prices as $price)
                            <div class="flex justify-between text-sm p-2 hover:bg-gray-50 rounded">
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

                    <!-- Status Pembayaran -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium">Status Pembayaran:</span>
                            <span class="text-sm font-semibold {{ $registration->has_successful_payment ? 'text-green-600' : 'text-red-600' }}">
                                {{ $registration->has_successful_payment ? 'LUNAS' : 'BELUM LUNAS' }}
                            </span>
                        </div>
                        @if($registration->has_successful_payment && $registration->successful_payment)
                        <div class="text-xs text-gray-500 mt-1">
                            Payment Code: {{ $registration->successful_payment->payment_code }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Dokumen - TANPA FITUR UPLOAD -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Dokumen</h2>
                    <div class="space-y-3">
                        @php
                            $documents = [
                                'kartu_keluarga' => [
                                    'name' => 'Kartu Keluarga',
                                    'path' => $registration->kartu_keluaga_path,
                                    'icon' => 'fas fa-id-card',
                                    'field' => 'kartu_keluaga_path'
                                ],
                                'ijazah' => [
                                    'name' => 'Ijazah',
                                    'path' => $registration->ijazah_path,
                                    'icon' => 'fas fa-graduation-cap',
                                    'field' => 'ijazah_path'
                                ],
                                'akta_kelahiran' => [
                                    'name' => 'Akta Kelahiran',
                                    'path' => $registration->akta_kelahiran_path,
                                    'icon' => 'fas fa-birthday-cake',
                                    'field' => 'akta_kelahiran_path'
                                ],
                                'pas_foto' => [
                                    'name' => 'Pas Foto',
                                    'path' => $registration->pas_foto_path,
                                    'icon' => 'fas fa-camera',
                                    'field' => 'pas_foto_path'
                                ]
                            ];
                        @endphp

                        @foreach($documents as $type => $doc)
                        <div class="document-item">
                            <div class="flex items-center">
                                <i class="{{ $doc['icon'] }} document-icon {{ $type }}"></i>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 text-sm">{{ $doc['name'] }}</p>
                                    <p class="text-xs text-gray-500 truncate max-w-xs">
                                        @if($doc['path'])
                                            {{ basename($doc['path']) }}
                                        @else
                                            Belum diunggah
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($doc['path'])
                                <button onclick="showDocumentModal('{{ $type }}', '{{ $doc['name'] }}')"
                                        class="text-blue-500 hover:text-blue-700"
                                        title="Lihat Dokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.registrations.download-document', [$registration->id, $type]) }}"
                                   class="text-green-500 hover:text-green-700"
                                   title="Download Dokumen">
                                    <i class="fas fa-download"></i>
                                </a>
                                <span class="document-status uploaded text-xs">
                                    <i class="fas fa-check mr-1"></i> Terunggah
                                </span>
                                @else
                                <span class="document-status missing text-xs">
                                    <i class="fas fa-times mr-1"></i> Belum Diunggah
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Progress Dokumen -->
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Kelengkapan Dokumen</span>
                            <span>{{ $registration->uploaded_documents_count }}/4 ({{ number_format(($registration->uploaded_documents_count / 4) * 100, 0) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full transition-all duration-300"
                                 style="width: {{ ($registration->uploaded_documents_count / 4) * 100 }}%"></div>
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
                        <div>
                            <span class="info-label">Status Akun</span>
                            <span class="info-value">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $registration->user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $registration->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Catatan Admin -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Catatan Admin</h2>
                    <form id="adminNotesForm">
                        @csrf
                        <textarea name="catatan_admin" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                  placeholder="Tambah catatan untuk pendaftaran ini...">{{ old('catatan_admin', $registration->catatan_admin) }}</textarea>
                        <button type="submit"
                                class="mt-2 w-full action-btn btn-primary text-sm">
                            <i class="fas fa-save"></i>
                            <span>Simpan Catatan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold text-gray-800">Tolak Pendaftaran</h3>
            <button onclick="closeRejectModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="rejectForm">
            @csrf
            <div class="modal-body">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea id="rejectReason" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Berikan alasan penolakan yang jelas..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
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

<!-- WhatsApp Message Modal -->
<div id="whatsappModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold text-gray-800">Kirim Pesan WhatsApp</h3>
            <button onclick="closeWhatsAppModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="whatsappForm">
            @csrf
            <div class="modal-body">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                    <textarea id="whatsappMessage" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Tulis pesan untuk calon santri..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeWhatsAppModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200">
                    Kirim WhatsApp
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Document Preview Modal -->
<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-50 p-4">
    <div class="modal-content bg-white">
        <div class="modal-header">
            <h3 class="text-lg font-bold text-gray-800" id="documentModalTitle"></h3>
            <button onclick="closeDocumentModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="documentContent" class="flex justify-center items-center">
                <!-- Content akan diisi oleh JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <a id="downloadDocumentLink" href="" class="action-btn btn-success">
                <i class="fas fa-download"></i>
                <span>Download</span>
            </a>
            <button onclick="closeDocumentModal()" class="action-btn btn-secondary">
                <i class="fas fa-times"></i>
                <span>Tutup</span>
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // PERBAIKAN: Tombol Terima sekarang SELALU AKTIF
    // Validasi akan dilakukan di backend/side JavaScript

    // Update status function dengan validasi yang lebih baik
    function updateStatus(status) {
        const messages = {
            'diterima': 'Apakah Anda yakin ingin menerima pendaftaran ini?',
            'ditolak': 'Apakah Anda yakin ingin menolak pendaftaran ini?',
            'menunggu_diverifikasi': 'Ubah status menjadi menunggu verifikasi?',
            'telah_dilihat': 'Tandai sebagai telah dilihat?',
            'perlu_review': 'Tandai sebagai perlu review ulang?'
        };

        const actionTexts = {
            'diterima': 'menerima',
            'ditolak': 'menolak',
            'menunggu_diverifikasi': 'mengubah status menjadi menunggu verifikasi',
            'telah_dilihat': 'menandai sebagai telah dilihat',
            'perlu_review': 'menandai sebagai perlu review ulang'
        };

        // PERBAIKAN: Untuk status 'diterima', berikan warning jika persyaratan belum lengkap
        if (status === 'diterima') {
            const isComplete = {{ $registration->is_documents_complete && $registration->is_biodata_complete && $registration->has_successful_payment ? 'true' : 'false' }};

            if (!isComplete) {
                Swal.fire({
                    title: 'Peringatan',
                    html: `
                        <div class="text-left">
                            <p class="mb-2">Persyaratan belum lengkap:</p>
                            <ul class="list-disc list-inside text-sm text-red-600">
                                ${!{{ $registration->is_documents_complete ? 'true' : 'false' }} ? '<li>Dokumen belum lengkap</li>' : ''}
                                ${!{{ $registration->is_biodata_complete ? 'true' : 'false' }} ? '<li>Biodata belum lengkap</li>' : ''}
                                ${!{{ $registration->has_successful_payment ? 'true' : 'false' }} ? '<li>Pembayaran belum lunas</li>' : ''}
                            </ul>
                            <p class="mt-2 text-sm">Apakah Anda yakin ingin menerima pendaftaran ini?</p>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Terima',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        proceedWithStatusUpdate(status);
                    }
                });
                return;
            }
        }

        // Untuk status lainnya, langsung konfirmasi biasa
        Swal.fire({
            title: 'Konfirmasi',
            text: messages[status] || `Anda akan ${actionTexts[status]} pendaftaran {{ $registration->nama_lengkap }}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'diterima' ? '#10b981' :
                              status === 'ditolak' ? '#ef4444' :
                              status === 'menunggu_diverifikasi' ? '#f59e0b' :
                              status === 'perlu_review' ? '#8b5cf6' : '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: status === 'diterima' ? 'Ya, Terima' :
                             status === 'ditolak' ? 'Ya, Tolak' :
                             status === 'menunggu_diverifikasi' ? 'Ya, Tandai' :
                             status === 'perlu_review' ? 'Ya, Tandai' : 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                proceedWithStatusUpdate(status);
            }
        });
    }

    // Function untuk proses update status
    function proceedWithStatusUpdate(status) {
        fetch(`{{ route('admin.registrations.update-status', $registration->id) }}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status,
                catatan: status === 'ditolak' ? document.getElementById('rejectReason')?.value || 'Data tidak memenuhi persyaratan' : null
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
        updateStatus('ditolak');
    });

    // WhatsApp notification functions
    function sendWhatsAppNotification() {
        document.getElementById('whatsappModal').classList.remove('hidden');
    }

    function closeWhatsAppModal() {
        document.getElementById('whatsappModal').classList.add('hidden');
        document.getElementById('whatsappMessage').value = '';
    }

    document.getElementById('whatsappForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const message = document.getElementById('whatsappMessage').value;

        if (!message.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Harap tulis pesan terlebih dahulu.',
                confirmButtonText: 'OK'
            });
            return;
        }

        fetch(`{{ route('admin.registrations.send-notification', $registration->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeWhatsAppModal();
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
    });

    // Document modal functions - TAMPILKAN DOKUMEN HANYA DI MODAL
    function showDocumentModal(documentType, title) {
        const viewUrl = `{{ route('admin.registrations.view-document', [$registration->id, 'DOC_TYPE']) }}`.replace('DOC_TYPE', documentType);
        const downloadUrl = `{{ route('admin.registrations.download-document', [$registration->id, 'DOC_TYPE']) }}`.replace('DOC_TYPE', documentType);

        document.getElementById('documentModalTitle').textContent = title;
        document.getElementById('downloadDocumentLink').href = downloadUrl;

        // Tampilkan loading terlebih dahulu
        document.getElementById('documentContent').innerHTML = `
            <div class="flex flex-col items-center justify-center p-8">
                <i class="fas fa-spinner fa-spin text-4xl text-gray-600 mb-4"></i>
                <p class="text-gray-600">Memuat dokumen...</p>
            </div>
        `;

        document.getElementById('documentModal').classList.remove('hidden');

        // Cek tipe file untuk menentukan tampilan
        fetch(viewUrl)
            .then(response => {
                const contentType = response.headers.get('content-type');

                if (contentType.includes('image')) {
                    // Untuk gambar, tampilkan langsung
                    document.getElementById('documentContent').innerHTML = `
                        <img src="${viewUrl}" alt="${title}" class="max-w-full max-h-80 object-contain rounded-lg">
                    `;
                } else if (contentType.includes('pdf')) {
                    // Untuk PDF, tampilkan embed dengan fallback
                    document.getElementById('documentContent').innerHTML = `
                        <div class="w-full h-96">
                            <embed src="${viewUrl}" type="application/pdf" width="100%" height="100%" class="rounded-lg">
                            <p class="text-sm text-gray-600 mt-2 text-center">
                                Jika PDF tidak tampil, <a href="${downloadUrl}" class="text-blue-600 hover:underline font-medium">download file</a>
                            </p>
                        </div>
                    `;
                } else {
                    // Untuk tipe lain, tampilkan download link
                    document.getElementById('documentContent').innerHTML = `
                        <div class="flex flex-col items-center justify-center p-8">
                            <i class="fas fa-file text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 mb-4">File tidak dapat ditampilkan preview</p>
                            <a href="${downloadUrl}" class="action-btn btn-success">
                                <i class="fas fa-download"></i>
                                <span>Download File</span>
                            </a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading document:', error);
                // Error message
                document.getElementById('documentContent').innerHTML = `
                    <div class="flex flex-col items-center justify-center p-8">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                        <p class="text-gray-600 mb-2">Gagal memuat dokumen</p>
                        <p class="text-gray-500 text-sm text-center">Silakan coba download file secara manual</p>
                    </div>
                `;
            });
    }

    function closeDocumentModal() {
        document.getElementById('documentModal').classList.add('hidden');
        document.getElementById('documentContent').innerHTML = '';
    }

    // Save admin notes
    document.getElementById('adminNotesForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(`{{ route('admin.registrations.update-admin-notes', $registration->id) }}`, {
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
                text: 'Terjadi kesalahan saat menyimpan catatan.',
                confirmButtonText: 'OK'
            });
        });
    });

    // Close modals on outside click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    document.getElementById('whatsappModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeWhatsAppModal();
        }
    });

    document.getElementById('documentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDocumentModal();
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
            closeWhatsAppModal();
            closeDocumentModal();
        }
    });
</script>
@endsection

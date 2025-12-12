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

    .status-seleksi-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
    }
    .status-seleksi-badge.sudah_mengikuti_seleksi { @apply bg-green-100 text-green-800; }
    .status-seleksi-badge.belum_mengikuti_seleksi { @apply bg-yellow-100 text-yellow-800; }

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
        @apply flex items-center justify-between p-3 bg-white rounded-lg shadow-sm border;
    }
    .requirement-met { @apply bg-green-50 border-green-200; }
    .requirement-not-met { @apply bg-red-50 border-red-200; }

    .requirement-icon {
        @apply flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center;
    }
    .requirement-icon.met { @apply bg-green-100 text-green-600; }
    .requirement-icon.not-met { @apply bg-red-100 text-red-600; }

    .whatsapp-btn {
        @apply bg-green-500 text-white hover:bg-green-600;
    }

    .needs-review-badge {
        @apply inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full ml-2;
    }

    .current-status {
        @apply bg-gradient-to-r from-primary to-secondary text-white px-4 py-3 rounded-xl text-center mb-4 shadow-md;
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

    .checkbox-group {
        @apply flex items-center space-x-2 p-3 border border-gray-200 rounded-lg mb-2;
    }
    .checkbox-group label {
        @apply flex-1 cursor-pointer;
    }
    .rejection-reason {
        @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm;
    }

    .stats-card {
        @apply bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4;
    }

    .progress-ring {
        transform: rotate(-90deg);
    }

    .progress-ring-circle {
        transition: stroke-dashoffset 0.35s;
        transform: rotate(90deg);
        transform-origin: 50% 50%;
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
      @include('layouts.components.admin.navbar')

              <!-- Header Hero -->
    <header class="py-8 px-4 text-center">
        <div class="flex space-x-2">
                <a href="{{ route('admin.registrations.index') }}"
                   class="action-btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden md:inline">Kembali</span>
                </a>
            </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Detail Pendaftaran Santri</h1>
        <p class="text-secondary">Kelola dan tinjau detail pendaftaran calon santri di sini.</p>
    </header>
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="bg-white rounded-xl shadow-md p-6 mx-auto text-center">
                <h2 class="text-2xl font-bold text-primary">{{$registration->nama_lengkap }}</h2>
                <div class="flex flex-wrap items-center gap-2 md:gap-4 justify-center mt-3">
                    <span class="status-badge {{ $registration->status_pendaftaran }} text-xs md:text-sm">
                        {{ $registration->status_label }}
                    </span>
                    <span class="status-seleksi-badge {{ $registration->status_seleksi }} text-xs md:text-sm">
                        {{ $registration->status_seleksi_label }}
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content  -->

        </div>

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">

        </div>

        <!-- Status & Actions & Requirements -->
        <div class="detail-section mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
                <!-- Kolom 1: Persyaratan Pendaftaran -->
                <div class="space-y-4 bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Persyaratan Pendaftaran</h3>

                    <!-- Card Persyaratan -->
                    <div class="stats-card">
                        <div class="space-y-3">
                            <!-- Dokumen Lengkap -->
                            <div class="requirement-item {{ $registration->is_documents_complete ? 'requirement-met' : 'requirement-not-met' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="requirement-icon {{ $registration->is_documents_complete ? 'met' : 'not-met' }}">
                                        <i class="fas {{ $registration->is_documents_complete ? 'fa-check-circle' : 'fa-times-circle' }} text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800 text-sm">Dokumen Lengkap</p>
                                        <p class="text-xs text-gray-600">{{ $registration->uploaded_documents_count }}/4 dokumen</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold {{ $registration->is_documents_complete ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $registration->is_documents_complete ? 'LENGKAP' : 'BELUM' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Biodata Lengkap -->
                            <div class="requirement-item {{ $registration->is_biodata_complete ? 'requirement-met' : 'requirement-not-met' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="requirement-icon {{ $registration->is_biodata_complete ? 'met' : 'not-met' }}">
                                        <i class="fas {{ $registration->is_biodata_complete ? 'fa-check-circle' : 'fa-times-circle' }} text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800 text-sm">Biodata Lengkap</p>
                                        <p class="text-xs text-gray-600">Data pribadi & orang tua</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold {{ $registration->is_biodata_complete ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $registration->is_biodata_complete ? 'LENGKAP' : 'BELUM' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Pembayaran Lunas -->
                            <div class="requirement-item {{ $registration->has_successful_payment ? 'requirement-met' : 'requirement-not-met' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="requirement-icon {{ $registration->has_successful_payment ? 'met' : 'not-met' }}">
                                        <i class="fas {{ $registration->has_successful_payment ? 'fa-check-circle' : 'fa-times-circle' }} text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800 text-sm">Pembayaran Lunas</p>
                                        <p class="text-xs text-gray-600">Status pembayaran</p>

                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold {{ $registration->has_successful_payment ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $registration->has_successful_payment ? 'LUNAS' : 'BELUM' }}
                                    </span>
                                     <!-- Button Lihat Pembayaran -->



                                </div>


                            </div>

                            <!-- Status Seleksi -->
                            <div class="requirement-item {{ $registration->status_seleksi === 'sudah_mengikuti_seleksi' ? 'requirement-met' : 'bg-yellow-50 border-yellow-200' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="requirement-icon {{ $registration->status_seleksi === 'sudah_mengikuti_seleksi' ? 'met' : 'bg-yellow-100 text-yellow-600' }}">
                                        <i class="fas {{ $registration->status_seleksi === 'sudah_mengikuti_seleksi' ? 'fa-check-circle' : 'fa-hourglass-half' }} text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800 text-sm">Status Seleksi</p>
                                        <p class="text-xs text-gray-600">Tes Seleksi</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold {{ $registration->status_seleksi === 'sudah_mengikuti_seleksi' ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ $registration->status_seleksi_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Ring -->
                        <div class="mt-4 flex items-center justify-center">
                            <div class="relative">
                                <svg class="w-20 h-20" viewBox="0 0 36 36">
                                    <path class="text-gray-200"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        fill="transparent"
                                        d="M18 2.0845
                                          a 15.9155 15.9155 0 0 1 0 31.831
                                          a 15.9155 15.9155 0 0 1 0 -31.831"
                                    />
                                    <path class="text-primary"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        stroke-dasharray="100, 100"
                                        fill="transparent"
                                        d="M18 2.0845
                                          a 15.9155 15.9155 0 0 1 0 31.831
                                          a 15.9155 15.9155 0 0 1 0 -31.831"
                                    />
                                    <text x="18" y="20.5" class=" font-bold text-gray-800 text-center" dominant-baseline="middle" text-anchor="middle">
                                        {{ $registration->uploaded_documents_count }}/4
                                    </text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2: Status Seleksi -->
                <div class="space-y-4 bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Kelola Status Seleksi</h3>

                    <!-- Card Status Seleksi -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-clipboard-check text-green-600 text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-800">Status Seleksi Saat Ini</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $registration->status_seleksi_label }}</p>
                        </div>

                        <!-- Dropdown Status Seleksi -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ubah Status Seleksi</label>
                                <select id="statusSeleksiDropdown" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                                    <option value="belum_mengikuti_seleksi" {{ $registration->status_seleksi === 'belum_mengikuti_seleksi' ? 'selected' : '' }}>Belum Mengikuti Seleksi</option>
                                    <option value="sudah_mengikuti_seleksi" {{ $registration->status_seleksi === 'sudah_mengikuti_seleksi' ? 'selected' : '' }}>Sudah Mengikuti Seleksi</option>
                                </select>
                            </div>
                            <button onclick="updateStatusSeleksi()" class="w-full action-btn btn-success">
                                <i class="fas fa-sync-alt"></i>
                                <span>Update Status Seleksi</span>
                            </button>
                        </div>

                        <!-- Info Tambahan -->
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <p class="text-xs text-blue-700">
                                    Status seleksi digunakan untuk menandai apakah calon santri sudah mengikuti proses tes seleksi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Action Buttons -->
                <div class="space-y-4 bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Aksi Admin</h3>

                    <!-- Status Saat Ini -->
                    <div class="current-status">
                        <div class="flex items-center justify-center space-x-2 mb-2">
                            <i class="fas fa-flag text-white"></i>
                            <span class="font-medium">Status Pendaftaran :</span>
                            <span class="font-bold text-gray-500">{{ $registration->status_label }}</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Utama -->
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="updateStatus('diterima')"
                                class="action-btn btn-success"
                                title="Terima pendaftaran ini">
                            <i class="fas fa-check-circle"></i>
                            <span>Terima</span>
                        </button>

                        <button onclick="showRejectModal()"
                                class="action-btn btn-danger"
                                title="Revisi pendaftaran ini">
                            <i class="fas fa-times-circle"></i>
                            <span>Revisi</span>
                        </button>
                    </div>

                    <!-- Tombol Tambahan -->
                    <div class="space-y-2">
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

                    <!-- Quick Stats -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-4">
                        <div class="grid grid-cols-2 gap-2 text-center">
                            <div>
                                <div class="text-lg font-bold text-primary">{{ $registration->uploaded_documents_count }}</div>
                                <div class="text-xs text-gray-600">Dokumen</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-green-600">
                                    {{ $registration->has_successful_payment ? '✓' : '✗' }}
                                </div>
                                <div class="text-xs text-gray-600">Pembayaran</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-4 md:space-y-6 bg-white rounded-xl shadow-md p-6 mb-6">
                <!-- Data Pribadi Santri -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Pribadi Santri</h2>
                    <table>
                        <tr>
                            <td>Nama Lengkap </td><td>: {{ $registration->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td>NIK</td><td>: {{ $registration->nik }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Tempat, Tanggal Lahir</td>
                            <td class="info-value">: {{ $registration->tempat_lahir }}, : {{ $registration->tanggal_lahir->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Jenis Kelamin</td>
                            <td class="info-value">: {{ ucfirst($registration->jenis_kelamin) }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Agama</td>
                            <td class="info-value">: {{ ucfirst($registration->agama) }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kebangsaan</td>
                            <td class="info-value">: {{ $registration->kebangsaan }}</td>
                        </tr>
                        <tr class="info-item md:col-td-2 lg:col-td-3">
                            <td class="info-label">Alamat Tinggal</td>
                            <td class="info-value">: {{ $registration->alamat_tinggal }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">RT/RW</td>
                            <td class="info-value">: {{ $registration->rt ?: '-' }}/: {{ $registration->rw ?: '-' }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kelurahan</td>
                            <td class="info-value">: {{ $registration->kelurahan }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kecamatan</td>
                            <td class="info-value">: {{ $registration->kecamatan }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kota</td>
                            <td class="info-value">: {{ $registration->kota }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kode Pos</td>
                            <td class="info-value">: {{ $registration->kode_pos }}</td>
                        </tr>

                    </table>
                    <div class="info-grid">

                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Orang Tua</h2>
                    <table>
                        <tr>
                            <td>Nama Ibu Kandung </td><td>: {{ $registration->nama_ibu_kandung }}</td>
                        </tr>
                        <tr>
                            <td>Nama Ayah Kandung</td><td>: {{ $registration->nama_ayah_kandung }}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan Ibu</td><td>: {{ $registration->pekerjaan_ibu }}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan Ayah</td><td>: {{ $registration->pekerjaan_ayah }}</td>
                        </tr>
                        <tr>
                            <td>Penghasilan Ibu</td><td>:
                                @if($registration->penghasilan_ibu)
                                    Rp {{ number_format($registration->penghasilan_ibu, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Penghasilan Ayah</td><td>:
                                @if($registration->penghasilan_ayah)
                                    Rp {{ number_format($registration->penghasilan_ayah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Status Orang Tua</td><td>: {{ ucfirst(str_replace('_', ' ', $registration->status_orang_tua)) }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Telepon Orang Tua</td><td>: {{ $registration->nomor_telpon_orang_tua }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Data Pendidikan -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Pendidikan</h2>
                    <table>
                        <tr>
                            <td>Jenjang Pendidikan Terakhir </td><td>: {{ $registration->jenjang_pendidikan_terakhir }}</td>
                        </tr>
                        <tr>
                            <td>Nama Sekolah/Pondok Terakhir</td><td>: {{ $registration->nama_sekolah_terakhir }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Sekolah/Pondok Terakhir</td><td>: {{ $registration->alamat_sekolah_terakhir }}</td>
                        </tr>
                        <tr>
                            <td>NIS/NISN/NSP</td><td>: {{ $registration->nis_nisn_nsp ?: '-' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Data Kesehatan -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Kesehatan</h2>
                    <table>
                        <tr>
                            <td>Tinggi Badan </td><td>: {{ $registration->tinggi_badan ? $registration->tinggi_badan . ' cm' : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Berat Badan</td><td>: {{ $registration->berat_badan ? $registration->berat_badan . ' kg' : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Riwayat Penyakit Serius</td><td>: {{ $registration->riwayat_penyakit_serius ?: 'Tidak ada' }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Golongan Darah</td>
                            <td class="info-value">: {{ $registration->golongan_darah ?: '-' }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Alergi Obat</td>
                            <td class="info-value">: {{ $registration->alergi_obat ?: 'Tidak ada' }}</td>
                        </tr>
                        <tr class="info-item md:col-td-2 lg:col-td-3">
                            <td class="info-label">Penyakit Kronis</td>
                            <td class="info-value">: {{ $registration->penyakit_kronis ?: 'Tidak ada' }}</td>
                        </tr>
                    </table>
                    <div class="info-grid">

                    </div>
                </div>

                <!-- Data Wali -->
                @if($registration->nama_wali)
                <div class="detail-section">
                    <h2 class="detail-section-title">Data Wali</h2>
                    <table class="info-grid">
                        <tr class="info-item">
                            <td class="info-label">Nama Wali</td>
                            <td class="info-value">:    {{ $registration->nama_wali }}</td>
                        </tr>
                        <tr class="info-item md:col-td-2 lg:col-td-3">
                            <td class="info-label">Alamat Wali</td>
                            <td class="info-value">:    {{ $registration->alamat_wali }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">RT/RW Wali</td>
                            <td class="info-value">:    {{ $registration->rt_wali ?: '-' }}/:  {{ $registration->rw_wali ?: '-' }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kelurahan Wali</td>
                            <td class="info-value">:    {{ $registration->kelurahan_wali }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kecamatan Wali</td>
                            <td class="info-value">:    {{ $registration->kecamatan_wali }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kota Wali</td>
                            <td class="info-value">:    {{ $registration->kota_wali }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Kode Pos Wali</td>
                            <td class="info-value">:    {{ $registration->kode_pos_wali }}</td>
                        </tr>
                        <tr class="info-item">
                            <td class="info-label">Nomor Telepon Wali</td>
                            <td class="info-value">:    {{ $registration->nomor_telpon_wali }}</td>
                        </tr>
                    </table>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="">
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <!-- Paket & Biaya -->
                    <div class="detail-section">
                        <h2 class="detail-section-title">Paket & Biaya</h2>
                        <div class="mb-4">
                            <table>
                                <tr>
                                    <td class="info-label">Paket</td>
                                    <td class="info-value border-blue-200">: {{ $registration->package->name }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Program</td>
                                    <td class="info-value border-blue-200">: {{ $registration->program_pendidikan }}</td>
                                </tr>
                            </table>
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
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-medium">Status Pembayaran:</span>
                        <span class="text-sm font-semibold {{ $registration->has_successful_payment ? 'text-green-600' : 'text-red-600' }}">
                            {{ $registration->has_successful_payment ? 'LUNAS' : 'BELUM LUNAS' }}
                        </span>
                    </div>

                    @if($registration->has_successful_payment && $registration->successful_payment)
                        <div class="text-xs text-gray-500 mb-3">
                            Payment Code: {{ $registration->successful_payment->payment_code }}
                        </div>
                    @endif

                    <a href="{{ route('admin.transactions.index') }}"
                        class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-credit-card mr-2"></i>
                        Lihat Transaksi
                    </a>
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
                                    <div class="flex items-center gap-3">
                                        <i class="{{ $doc['icon'] }} document-icon {{ $type }}"></i>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800 text-sm">{{ $doc['name'] }}</p>
                                            <p class="text-xs text-gray-500 truncate max-w-xs">
                                                @if($doc['path'])

                                                @else
                                                    Belum diunggah
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if($doc['path'])
                                            <button onclick="showDocumentModal('{{ $type }}', '{{ $doc['name'] }}')"
                                                    class="text-blue-500 hover:text-blue-700 transition-colors"
                                                    title="Lihat Dokumen">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.registrations.download-document', [$registration->id, $type]) }}"
                                            class="text-green-500 hover:text-green-700 transition-colors"
                                            title="Download Dokumen">
                                                <i class="fas fa-download"></i>
                                            </a>

                                            <!-- Tooltip Icon untuk Terunggah -->
                                            <div class="relative group">
                                                <button class="text-green-600 hover:text-green-800 transition-colors focus:outline-none"
                                                        title="Status Dokumen">
                                                    <i class="fas fa-check-circle text-lg"></i>
                                                </button>
                                                <!-- Tooltip -->
                                                <div class="absolute z-10 invisible group-hover:visible opacity-0 group-hover:opacity-100
                                                            transition-all duration-200 bottom-full left-1/2 transform -translate-x-1/2 mb-2">
                                                    <div class="bg-gray-800 text-white text-xs rounded py-1.5 px-3 whitespace-nowrap">
                                                        Dokumen Terunggah
                                                        <div class="absolute left-1/2 transform -translate-x-1/2 top-full w-0 h-0
                                                                    border-l-4 border-l-transparent border-r-4 border-r-transparent border-t-4 border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <!-- Tooltip Icon untuk Belum Diunggah -->
                                            <div class="relative group">
                                                <button class="text-red-600 hover:text-red-800 transition-colors focus:outline-none"
                                                        title="Status Dokumen">
                                                    <i class="fas fa-exclamation-circle text-lg"></i>
                                                </button>
                                                <!-- Tooltip -->
                                                <div class="absolute z-10 invisible group-hover:visible opacity-0 group-hover:opacity-100
                                                            transition-all duration-200 bottom-full left-1/2 transform -translate-x-1/2 mb-2">
                                                    <div class="bg-gray-800 text-white text-xs rounded py-1.5 px-3 whitespace-nowrap">
                                                        Belum Diunggah
                                                        <div class="absolute left-1/2 transform -translate-x-1/2 top-full w-0 h-0
                                                                    border-l-4 border-l-transparent border-r-4 border-r-transparent border-t-4 border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
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
                                </div>
                                <div class="bg-white rounded-xl shadow-md p-6">
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
                                        <textarea id="adminNotes" name="catatan_admin" rows="4"
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

                        </div>

                    </main>
                    @include('layouts.components.admin.footer')
                </div>

<!-- Reject Modal dengan Form Dokumen -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="modal-content bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <div class="modal-header mb-4 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Revisi Pendaftaran & Kelola Dokumen</h3>
            <button onclick="closeRejectModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="rejectForm">
            @csrf
            <div class="modal-body">
                <!-- Pilihan Dokumen yang Akan Dihapus -->
               <div class="mb-4">
                <h4 class="font-medium mb-3">Pilih dokumen yang harus direvisi:</h4>
                    <div class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft" id="keep_kartu_keluarga" name="keep_kartu_keluarga">
                        <label for="keep_kartu_keluarga" class="select-none ms-2 text-sm font-medium text-heading">
                            <span class="">Kartu Keluarga</span>
                            @if($registration->kartu_keluaga_path)
                            <span class="text-xs ml-2">(Sudah diunggah)</span>
                            @else
                            <span class="text-xs text-red-600 ml-2">(Belum diunggah)</span>
                            @endif
                        </label>
                            </input>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft" id="keep_ijazah" name="keep_ijazah">
                        <label for="keep_ijazah" class="select-none ms-2 text-sm font-medium text-heading">
                            <span class="">Ijazah</span>
                            @if($registration->ijazah_path)
                            <span class="text-xs ml-2">(Sudah diunggah)</span>
                            @else
                            <span class="text-xs text-red-600 ml-2">(Belum diunggah)</span>
                            @endif
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft" id="keep_akta_kelahiran" name="keep_akta_kelahiran">
                        <label for="keep_akta_kelahiran" class="select-none ms-2 text-sm font-medium text-heading">
                            <span class="">Akta Kelahiran</span>
                            @if($registration->akta_kelahiran_path)
                            <span class="text-xs ml-2">(Sudah diunggah)</span>
                            @else
                            <span class="text-xs text-red-600 ml-2">(Belum diunggah)</span>
                            @endif
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft" id="keep_pas_foto" name="keep_pas_foto">
                        <label for="keep_pas_foto" class="select-none ms-2 text-sm font-medium text-heading">
                            <span class="">Pas Foto</span>
                            @if($registration->pas_foto_path)
                            <span class="text-xs ml-2">(Sudah diunggah)</span>
                            @else
                            <span class="text-xs text-red-600 ml-2">(Belum diunggah)</span>
                            @endif
                        </label>
                    </div>

                    <p class="text-xs mt-2 text-white">
                        <i class="fas fa-info-circle mr-1"></i>
                        Centang dokumen yang ingin dipertahankan. Dokumen yang tidak dicentang akan dihapus dari sistem.
                    </p>
                </div>

                <!-- Alasan Penolakan -->
                <div class="mb-4 mx-auto max-w-lg w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea id="rejectReason" name="reject_reason" rows="4" required
                              class="rejection-reason w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                              placeholder="Berikan alasan penolakan yang jelas dan informatif...">

                              {{ old('reject_reason', $registration->catatan_admin) }}
                            </textarea>
                </div>

                <!-- Catatan Admin Sebelumnya -->
                @if($registration->catatan_admin)
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h4 class="font-medium text-yellow-800 mb-2">Catatan Admin Sebelumnya:</h4>
                    <p class="text-sm text-yellow-700">{{ $registration->catatan_admin }}</p>
                </div>
                @endif
            </div>
            <div class="modal-footer flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-yellow-500 rounded-lg hover:bg-red-600 transition duration-200">
                    Revisi Pendaftaran
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
    // Update status seleksi
    function updateStatusSeleksi() {
        const statusSeleksi = document.getElementById('statusSeleksiDropdown').value;

        fetch(`{{ route('admin.registrations.update-status-seleksi', $registration->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status_seleksi: statusSeleksi
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
                text: 'Terjadi kesalahan saat memperbarui status seleksi.',
                confirmButtonText: 'OK'
            });
        });
    }

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

        // Untuk status 'diterima', berikan warning jika persyaratan belum lengkap
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
                status_seleksi: document.getElementById('statusSeleksiDropdown').value,
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

    // Reject modal functions dengan form dokumen
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Sync checked document names into rejectReason and admin notes
    function getCheckedDocNames() {
        const checkboxes = document.querySelectorAll('#rejectModal input[type="checkbox"]');
        const names = [];
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const label = document.querySelector(`label[for="${cb.id}"]`);
                if (label) {
                    const span = label.querySelector('span');
                    let text = span ? span.textContent.trim() : label.textContent.trim();
                    text = text.split('(')[0].trim();
                    if (text) names.push(text);
                }
            }
        });
        return names;
    }

    function updateNotesFromCheckboxes() {
        const names = getCheckedDocNames();
        const rejectEl = document.getElementById('rejectReason');
        const adminEl = document.getElementById('adminNotes') || document.querySelector('textarea[name="catatan_admin"]');

        // Build the document list string
        const docListText = names.length ? 'Dokumen yang perlu diperbaiki: ' + names.join(', ') : '';

        // Update reject reason textarea
        if (rejectEl) {
            let currentValue = rejectEl.value.trim();
            // Remove any existing "Dokumen yang perlu diperbaiki:" line
            let lines = currentValue ? currentValue.split('\n') : [];
            lines = lines.filter(l => !l.trim().startsWith('Dokumen yang perlu diperbaiki:'));

            // Add the new document list at the beginning if there are checked items
            if (docListText) {
                lines.unshift(docListText);
            }

            rejectEl.value = lines.join('\n').trim();
        }

        // Update admin notes textarea
        if (adminEl) {
            let currentValue = adminEl.value.trim();
            // Remove any existing "Dokumen yang perlu diperbaiki:" line
            let lines = currentValue ? currentValue.split('\n') : [];
            lines = lines.filter(l => !l.trim().startsWith('Dokumen yang perlu diperbaiki:'));

            // Add the new document list at the end if there are checked items
            if (docListText) {
                lines.push(docListText);
            }

            adminEl.value = lines.join('\n').trim();
        }
    }

    // Attach listeners to checkboxes inside reject modal
    document.querySelectorAll('#rejectModal input[type="checkbox"]').forEach(cb => cb.addEventListener('change', updateNotesFromCheckboxes));

    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const rejectReason = document.getElementById('rejectReason').value;

        if (!rejectReason.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Harap berikan alasan penolakan.',
                confirmButtonText: 'OK'
            });
            return;
        }

        const formData = new FormData(this);

        fetch(`{{ route('admin.registrations.update-documents-rejection', $registration->id) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
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

    // Document modal functions
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

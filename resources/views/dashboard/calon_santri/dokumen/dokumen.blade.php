@extends('layouts.app')

@section('title', 'Upload Dokumen - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .document-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .document-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .document-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .document-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .document-card-body {
        padding: 1.5rem;
    }

    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #fafafa;
        margin-bottom: 1rem;
    }

    .upload-area:hover {
        border-color: #667eea;
        background: #f0f4ff;
    }

    .upload-area.active {
        border-color: #667eea;
        background: #f0f4ff;
    }

    .upload-icon {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 1rem;
    }

    .file-info {
        background: #ecfdf5;
        border: 1px solid #d1fae5;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .file-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5a6fd8;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-uploaded {
        background: #d1fae5;
        color: #065f46;
    }

    .status-missing {
        background: #fee2e2;
        color: #991b1b;
    }

    .progress-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-top: 2rem;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin: 1rem 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .document-requirements {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .requirement-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .requirement-list li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0;
        color: #64748b;
        font-size: 0.875rem;
    }

    .requirement-list li i {
        color: #10b981;
    }

    .hidden {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg py-4 px-6 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Ponpes Al Bani</h1>
                    <p class="text-sm text-gray-600">Pendaftaran Santri Baru</p>
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <a href="{{ route('santri.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="{{ route('santri.biodata.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition duration-300">
                    <i class="fas fa-user mr-2"></i>Biodata
                </a>
                <a href="{{ route('santri.documents.index') }}" class="text-blue-600 font-medium">
                    <i class="fas fa-file-alt mr-2"></i>Dokumen
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-4 py-2 rounded-full hover:shadow-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Upload Dokumen Persyaratan</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Lengkapi dokumen-dokumen berikut untuk menyelesaikan proses pendaftaran Anda
            </p>
        </div>

        <!-- Registration Status -->
        @if($registration)
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <i class="fas fa-id-card text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Status Pendaftaran</h3>
                            <p class="text-2xl font-bold {{ $registration->status_pendaftaran == 'ditolak' ? 'text-red-600' : 'text-blue-600' }}">
                                {{ $registration->status_label }}
                            </p>
                            @if($registration->status_pendaftaran == 'ditolak' && $registration->catatan_admin)
                            <p class="text-red-600 mt-2"><i class="fas fa-exclamation-triangle mr-2"></i>{{ $registration->catatan_admin }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-center lg:text-right">
                    <p class="text-sm text-gray-600 mb-1">ID Pendaftaran</p>
                    <p class="text-xl font-mono font-bold text-gray-900 bg-gray-100 px-4 py-2 rounded-lg">
                        {{ $registration->id_pendaftaran }}
                    </p>
                </div>
            </div>

            <!-- Package Info -->
            @if($registration->package)
            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h4 class="font-semibold text-blue-800 mb-2">Paket Yang Dipilih</h4>
                        <p class="text-blue-700 font-medium">{{ $registration->package->name }}</p>
                        <p class="text-blue-600 text-sm">{{ $registration->package->description }}</p>
                    </div>
                    <div class="mt-4 lg:mt-0 text-center lg:text-right">
                        <p class="text-sm text-blue-600">Total Biaya</p>
                        <p class="text-2xl font-bold text-blue-800">{{ $registration->formatted_total_biaya }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Documents Grid -->
        <div class="document-grid">
            <!-- Kartu Keluarga Card -->
            <div class="document-card">
                <div class="document-card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-users text-2xl"></i>
                            <div>
                                <h3 class="text-lg font-semibold">Kartu Keluarga</h3>
                                <p class="text-blue-100 text-sm">Fotokopi yang jelas</p>
                            </div>
                        </div>
                        <span class="status-badge {{ $registration && $registration->kartu_keluaga_path ? 'status-uploaded' : 'status-missing' }}">
                            <i class="fas {{ $registration && $registration->kartu_keluaga_path ? 'fa-check' : 'fa-times' }}"></i>
                            {{ $registration && $registration->kartu_keluaga_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                        </span>
                    </div>
                </div>

                <div class="document-card-body">
                    <div class="upload-area" id="kartu_keluargaUploadArea">
                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                        <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                        <p class="text-gray-500 text-sm">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                        <input type="file" id="kartu_keluargaFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
                    </div>

                    <div class="document-requirements">
                        <ul class="requirement-list">
                            <li><i class="fas fa-check-circle"></i> Foto jelas seluruh halaman</li>
                            <li><i class="fas fa-check-circle"></i> Terlihat nomor KK dan data lengkap</li>
                            <li><i class="fas fa-check-circle"></i> File tidak blur atau gelap</li>
                        </ul>
                    </div>

                    <div id="kartu_keluargaFileInfo" class="file-info {{ $registration && $registration->kartu_keluaga_path ? '' : 'hidden' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file-pdf text-green-600 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-800" id="kartu_keluargaFileName">
                                        @if($registration && $registration->kartu_keluaga_path)
                                            File telah diunggah
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                                </div>
                            </div>
                            @if($registration && $registration->kartu_keluaga_path)
                            <div class="file-actions">
                                <a href="{{ route('santri.documents.file', 'kartu_keluarga') }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <button onclick="deleteDocument('kartu_keluarga')" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ijazah Card -->
            <div class="document-card">
                <div class="document-card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-graduation-cap text-2xl"></i>
                            <div>
                                <h3 class="text-lg font-semibold">Ijazah</h3>
                                <p class="text-blue-100 text-sm">Fotokopi ijazah terakhir</p>
                            </div>
                        </div>
                        <span class="status-badge {{ $registration && $registration->ijazah_path ? 'status-uploaded' : 'status-missing' }}">
                            <i class="fas {{ $registration && $registration->ijazah_path ? 'fa-check' : 'fa-times' }}"></i>
                            {{ $registration && $registration->ijazah_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                        </span>
                    </div>
                </div>

                <div class="document-card-body">
                    <div class="upload-area" id="ijazahUploadArea">
                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                        <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                        <p class="text-gray-500 text-sm">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                        <input type="file" id="ijazahFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
                    </div>

                    <div class="document-requirements">
                        <ul class="requirement-list">
                            <li><i class="fas fa-check-circle"></i> Foto jelas seluruh halaman</li>
                            <li><i class="fas fa-check-circle"></i> Terlihat nilai dan stempel</li>
                            <li><i class="fas fa-check-circle"></i> Ijazah SD/SMP/SMA sesuai jenjang</li>
                        </ul>
                    </div>

                    <div id="ijazahFileInfo" class="file-info {{ $registration && $registration->ijazah_path ? '' : 'hidden' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file-pdf text-green-600 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-800" id="ijazahFileName">
                                        @if($registration && $registration->ijazah_path)
                                            File telah diunggah
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                                </div>
                            </div>
                            @if($registration && $registration->ijazah_path)
                            <div class="file-actions">
                                <a href="{{ route('santri.documents.file', 'ijazah') }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <button onclick="deleteDocument('ijazah')" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Akta Kelahiran Card -->
            <div class="document-card">
                <div class="document-card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-birthday-cake text-2xl"></i>
                            <div>
                                <h3 class="text-lg font-semibold">Akta Kelahiran</h3>
                                <p class="text-blue-100 text-sm">Fotokopi akta kelahiran</p>
                            </div>
                        </div>
                        <span class="status-badge {{ $registration && $registration->akta_kelahiran_path ? 'status-uploaded' : 'status-missing' }}">
                            <i class="fas {{ $registration && $registration->akta_kelahiran_path ? 'fa-check' : 'fa-times' }}"></i>
                            {{ $registration && $registration->akta_kelahiran_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                        </span>
                    </div>
                </div>

                <div class="document-card-body">
                    <div class="upload-area" id="akta_kelahiranUploadArea">
                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                        <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                        <p class="text-gray-500 text-sm">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                        <input type="file" id="akta_kelahiranFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
                    </div>

                    <div class="document-requirements">
                        <ul class="requirement-list">
                            <li><i class="fas fa-check-circle"></i> Foto jelas seluruh halaman</li>
                            <li><i class="fas fa-check-circle"></i> Terlihat nomor akta dan data lengkap</li>
                            <li><i class="fas fa-check-circle"></i> Diterbitkan oleh dinas catatan sipil</li>
                        </ul>
                    </div>

                    <div id="akta_kelahiranFileInfo" class="file-info {{ $registration && $registration->akta_kelahiran_path ? '' : 'hidden' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file-pdf text-green-600 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-800" id="akta_kelahiranFileName">
                                        @if($registration && $registration->akta_kelahiran_path)
                                            File telah diunggah
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                                </div>
                            </div>
                            @if($registration && $registration->akta_kelahiran_path)
                            <div class="file-actions">
                                <a href="{{ route('santri.documents.file', 'akta_kelahiran') }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <button onclick="deleteDocument('akta_kelahiran')" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pas Foto Card -->
            <div class="document-card">
                <div class="document-card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-camera text-2xl"></i>
                            <div>
                                <h3 class="text-lg font-semibold">Pas Foto</h3>
                                <p class="text-blue-100 text-sm">Foto terbaru latar merah</p>
                            </div>
                        </div>
                        <span class="status-badge {{ $registration && $registration->pas_foto_path ? 'status-uploaded' : 'status-missing' }}">
                            <i class="fas {{ $registration && $registration->pas_foto_path ? 'fa-check' : 'fa-times' }}"></i>
                            {{ $registration && $registration->pas_foto_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                        </span>
                    </div>
                </div>

                <div class="document-card-body">
                    <div class="upload-area" id="pas_fotoUploadArea">
                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                        <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                        <p class="text-gray-500 text-sm">Format: JPEG, PNG (Maks. 5MB)</p>
                        <input type="file" id="pas_fotoFile" accept=".jpeg,.jpg,.png" class="hidden">
                    </div>

                    <div class="document-requirements">
                        <ul class="requirement-list">
                            <li><i class="fas fa-check-circle"></i> Ukuran 3x4 atau 4x6</li>
                            <li><i class="fas fa-check-circle"></i> Latar belakang warna merah</li>
                            <li><i class="fas fa-check-circle"></i> Pakaian sopan dan rapi</li>
                            <li><i class="fas fa-check-circle"></i> Wajah terlihat jelas</li>
                        </ul>
                    </div>

                    <div id="pas_fotoFileInfo" class="file-info {{ $registration && $registration->pas_foto_path ? '' : 'hidden' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-image text-green-600 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-800" id="pas_fotoFileName">
                                        @if($registration && $registration->pas_foto_path)
                                            File telah diunggah
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                                </div>
                            </div>
                            @if($registration && $registration->pas_foto_path)
                            <div class="file-actions">
                                <a href="{{ route('santri.documents.file', 'pas_foto') }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <button onclick="deleteDocument('pas_foto')" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress & Actions Section -->
        <div class="progress-section">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Progress Pendaftaran</h2>
                <p class="text-gray-600">Lengkapi semua dokumen untuk menyelesaikan pendaftaran</p>
            </div>

            @if($registration)
            <div class="max-w-2xl mx-auto">
                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-3">
                        <span>Kelengkapan Dokumen</span>
                        <span>
                            @php
                                $uploadedCount = 0;
                                if ($registration->kartu_keluaga_path) $uploadedCount++;
                                if ($registration->ijazah_path) $uploadedCount++;
                                if ($registration->akta_kelahiran_path) $uploadedCount++;
                                if ($registration->pas_foto_path) $uploadedCount++;
                                $percentage = ($uploadedCount / 4) * 100;
                            @endphp
                            {{ $uploadedCount }}/4 Dokumen
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                <!-- Status Message -->
                <div class="text-center mb-8">
                    @if($percentage == 100)
                    <div class="inline-flex items-center space-x-2 bg-green-100 text-green-800 px-6 py-3 rounded-full">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span class="font-semibold">Semua dokumen telah lengkap! Anda bisa menyelesaikan pendaftaran.</span>
                    </div>
                    @else
                    <div class="inline-flex items-center space-x-2 bg-orange-100 text-orange-800 px-6 py-3 rounded-full">
                        <i class="fas fa-info-circle text-xl"></i>
                        <span class="font-semibold">Lengkapi {{ 4 - $uploadedCount }} dokumen lagi untuk menyelesaikan pendaftaran.</span>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('santri.biodata.index') }}" class="btn btn-primary flex-1 justify-center">
                        <i class="fas fa-arrow-left"></i> Kembali ke Biodata
                    </a>

                    @if($percentage == 100)
                    <button onclick="completeRegistration()" class="btn btn-success flex-1 justify-center">
                        <i class="fas fa-check-circle"></i> Selesaikan Pendaftaran
                    </button>
                    @else
                    <button onclick="showCompletionWarning()" class="btn bg-gray-400 text-white flex-1 justify-center cursor-not-allowed">
                        <i class="fas fa-lock"></i> Lengkapi Dokumen
                    </button>
                    @endif
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Biodata Belum Lengkap</h3>
                <p class="text-gray-600 mb-6">Silakan isi biodata terlebih dahulu sebelum mengunggah dokumen.</p>
                <a href="{{ route('santri.biodata.index') }}" class="btn btn-primary">
                    <i class="fas fa-user-edit"></i> Isi Biodata Sekarang
                </a>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Tunggu sampai DOM fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing document upload...');
        initializeDocumentUpload();
    });

    function initializeDocumentUpload() {
        const documentTypes = ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'];

        console.log('Initializing upload for document types:', documentTypes);

        // Initialize file upload for each document type yang ada
        documentTypes.forEach(type => {
            const uploadArea = document.getElementById(`${type}UploadArea`);
            const fileInput = document.getElementById(`${type}File`);

            console.log(`Checking elements for ${type}:`, {
                uploadArea: !!uploadArea,
                fileInput: !!fileInput
            });

            // Cek apakah elemen ada sebelum menambahkan event listener
            if (uploadArea && fileInput) {
                console.log(`Initializing ${type} upload...`);
                initFileUpload(type);
            } else {
                console.warn(`Element for ${type} not found`);
            }
        });
    }

    function initFileUpload(documentType) {
        const uploadArea = document.getElementById(`${documentType}UploadArea`);
        const fileInput = document.getElementById(`${documentType}File`);
        const fileInfo = document.getElementById(`${documentType}FileInfo`);

        console.log(`Setting up ${documentType}:`, { uploadArea, fileInput, fileInfo });

        // Pastikan elemen ada
        if (!uploadArea || !fileInput) {
            console.error(`Required elements for ${documentType} not found`);
            return;
        }

        // Click to select file
        uploadArea.addEventListener('click', () => {
            console.log(`Upload area clicked for ${documentType}`);
            fileInput.click();
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add('active');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.remove('active');
            }, false);
        });

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            console.log('File dropped');
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                console.log('Files dropped:', files);
                fileInput.files = files;
                handleFiles(files);
            }
        }

        fileInput.addEventListener('change', function() {
            console.log('File input changed:', this.files);
            if (this.files.length > 0) {
                handleFiles(this.files);
            }
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                console.log('Handling file:', file.name, file.type, file.size);
                if (validateFile(file, documentType)) {
                    uploadFile(file, documentType);
                }
            }
        }
    }

    function validateFile(file, documentType) {
        const allowedTypes = {
            'kartu_keluarga': ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'],
            'ijazah': ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'],
            'akta_kelahiran': ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'],
            'pas_foto': ['image/jpeg', 'image/jpg', 'image/png']
        };

        const maxSize = 5 * 1024 * 1024; // 5MB

        console.log('Validating file:', {
            type: file.type,
            size: file.size,
            allowed: allowedTypes[documentType]
        });

        // Validasi tipe file
        if (!allowedTypes[documentType].includes(file.type)) {
            let allowedFormats = documentType === 'pas_foto' ? 'JPEG, PNG' : 'PDF, JPEG, PNG';
            Swal.fire({
                icon: 'error',
                title: 'Format tidak valid',
                text: `Hanya file ${allowedFormats} yang diizinkan untuk ${documentType.replace(/_/g, ' ')}.`,
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Validasi ukuran file
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu besar',
                text: 'Ukuran file maksimal 5MB.',
                confirmButtonText: 'OK'
            });
            return false;
        }

        console.log('File validation passed');
        return true;
    }

    function uploadFile(file, documentType) {
        console.log('Uploading file:', file.name, 'for', documentType);

        const formData = new FormData();
        formData.append('file', file);

        Swal.fire({
            title: 'Mengunggah...',
            text: 'Sedang mengunggah file, harap tunggu.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/santri/documents/upload/${documentType}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Upload response:', data);
            Swal.close();

            if (data.success) {
                updateFileInfo(documentType, file.name);
                updateDocumentStatus(documentType, true);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    checkCompletionProgress();
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
            console.error('Upload error:', error);
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengunggah file.',
                confirmButtonText: 'OK'
            });
        });
    }

    function updateFileInfo(documentType, fileName) {
        const fileInfo = document.getElementById(`${documentType}FileInfo`);
        const fileNameElement = document.getElementById(`${documentType}FileName`);

        console.log('Updating file info:', { fileInfo, fileNameElement, fileName });

        if (fileInfo && fileNameElement) {
            fileNameElement.textContent = fileName;
            fileInfo.classList.remove('hidden');

            // Update action buttons
            const actionsHtml = `
                <div class="file-actions">
                    <a href="/santri/documents/file/${documentType}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Lihat
                    </a>
                    <button onclick="deleteDocument('${documentType}')" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            `;

            const existingActions = fileInfo.querySelector('.file-actions');
            if (existingActions) {
                existingActions.remove();
            }

            const container = fileInfo.querySelector('.flex.items-center.justify-between');
            if (container) {
                container.insertAdjacentHTML('beforeend', actionsHtml);
            }
        }
    }

    function updateDocumentStatus(documentType, isUploaded) {
        const statusElement = document.querySelector(`#${documentType}UploadArea`)?.closest('.document-card')?.querySelector('.status-badge');

        console.log('Updating status:', { documentType, isUploaded, statusElement });

        if (statusElement) {
            if (isUploaded) {
                statusElement.className = 'status-badge status-uploaded';
                statusElement.innerHTML = '<i class="fas fa-check"></i> Telah Diunggah';
            } else {
                statusElement.className = 'status-badge status-missing';
                statusElement.innerHTML = '<i class="fas fa-times"></i> Belum Diunggah';
            }
        }
    }

    function checkCompletionProgress() {
        console.log('Checking completion progress...');
        // Reload page to update progress bar setelah delay kecil
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }

    // Fungsi global untuk delete (harus accessible dari HTML)
    window.deleteDocument = function(documentType) {
        console.log('Deleting document:', documentType);

        Swal.fire({
            title: 'Hapus dokumen?',
            text: 'Dokumen yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/santri/documents/delete/${documentType}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Delete response:', data);
                    if (data.success) {
                        const fileInfo = document.getElementById(`${documentType}FileInfo`);
                        if (fileInfo) {
                            fileInfo.classList.add('hidden');
                        }
                        updateDocumentStatus(documentType, false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            checkCompletionProgress();
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
                    console.error('Delete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus file.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    // Fungsi global untuk completion
    window.showCompletionWarning = function() {
        Swal.fire({
            icon: 'warning',
            title: 'Dokumen Belum Lengkap',
            text: 'Harap lengkapi semua dokumen terlebih dahulu sebelum menyelesaikan pendaftaran.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#3b82f6'
        });
    }

    window.completeRegistration = function() {
        Swal.fire({
            title: 'Selesaikan Pendaftaran?',
            html: `
                <p>Pastikan semua data dan dokumen sudah lengkap dan benar.</p>
                <p class="text-sm text-gray-600 mt-2">Setelah menyelesaikan pendaftaran, data Anda akan dikirim untuk diverifikasi oleh admin.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Selesaikan',
            cancelButtonText: 'Periksa Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                // Update status to menunggu diverifikasi
                fetch(`{{ route('santri.biodata.store') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        complete_registration: true
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pendaftaran Selesai!',
                            html: `
                                <p>Pendaftaran Anda telah berhasil diselesaikan.</p>
                                <p class="text-sm text-gray-600 mt-2">Tim admin akan memverifikasi data Anda dan akan menghubungi melalui WhatsApp.</p>
                            `,
                            confirmButtonText: 'Kembali ke Dashboard',
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            window.location.href = '{{ route("santri.dashboard") }}';
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
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyelesaikan pendaftaran.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }
</script>
@endsection

@extends('layouts.app')

@section('title', 'Upload Dokumen - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="#profile" class="text-primary hover:text-secondary font-medium">Profil</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary font-medium">Dokumen</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">Logout</button>
                </form>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-primary focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-2 bg-white p-4 rounded-xl shadow-lg">
            <div class="flex flex-col space-y-2">
                <a href="{{ url('/') }}" class="text-primary">Beranda</a>
                <a href="#profile" class="text-primary">Profil</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary">Dokumen</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-primary text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header Hero -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Upload Dokumen Persyaratan</h1>
        <p class="text-secondary">Lengkapi dokumen-dokumen berikut untuk menyelesaikan proses pendaftaran Anda</p>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md mt-6">
            <div class="flex flex-col md:flex-row items-center md:items-start md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-primary">Status Pendaftaran</h2>
                    <p class="text-secondary mt-1">
                        @if($registration)
                            Status:
                            <span class="font-semibold {{ $registration->status_pendaftaran == 'ditolak' ? 'text-red-600' : ($registration->status_pendaftaran == 'diterima' ? 'text-green-600' : 'text-primary') }}">
                                {{ $registration->status_label }}
                            </span>
                        @else
                            <span class="text-yellow-600">Belum Mendaftar</span>
                        @endif
                    </p>
                    @if($registration)
                    <p class="text-sm text-gray-600 mt-1">ID Pendaftaran: <span class="font-mono font-bold">{{ $registration->id_pendaftaran }}</span></p>
                    @endif
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('santri.dashboard') }}" class="bg-secondary text-white px-4 py-1.5 rounded-full hover:bg-gray-600 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>

                    @if($registration && $registration->hasAllDocuments() && $registration->status_pendaftaran != 'menunggu_diverifikasi')
                        <button onclick="completeRegistration()" class="bg-green-600 text-white px-4 py-1.5 rounded-full hover:bg-green-700 transition duration-300 flex items-center justify-center">
                            <i class="fas fa-check-circle mr-2"></i> Selesaikan Pendaftaran
                        </button>
                    @endif
                </div>
            </div>

            <!-- Package Info -->
            @if($registration && $registration->package)
            <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h4 class="font-semibold text-blue-800 mb-2">Paket Yang Dipilih</h4>
                        <p class="text-blue-700 font-medium">{{ $registration->package->name }}</p>
                        <p class="text-blue-600 text-sm">{{ $registration->package->description }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 text-center md:text-right">
                        <p class="text-sm text-blue-600">Total Biaya</p>
                        <p class="text-2xl font-bold text-blue-800">{{ $registration->formatted_total_biaya }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
        <!-- Documents Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-6 mb-8">
            <!-- Kartu Keluarga Card -->
            <div class="bg-white rounded-xl shadow-md p-6 document-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-primary">Kartu Keluarga</h3>
                            <p class="text-secondary text-sm">Fotokopi yang jelas</p>
                        </div>
                    </div>
                    <span class="status-badge {{ $registration && $registration->kartu_keluaga_path ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $registration && $registration->kartu_keluaga_path ? 'fa-check' : 'fa-times' }} mr-1"></i>
                        {{ $registration && $registration->kartu_keluaga_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                    </span>
                </div>

                <div class="upload-area" id="kartu_keluargaUploadArea" data-type="kartu_keluarga">
                    <i class="fas fa-cloud-upload-alt upload-icon text-4xl text-primary mb-3"></i>
                    <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                    <p class="text-gray-500 text-sm">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                    <div class="upload-progress hidden mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="upload-progress-bar bg-primary h-2 rounded-full transition-all duration-300"
                                 id="kartu_keluargaProgressBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <input type="file" id="kartu_keluargaFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
                </div>

                <div class="document-requirements mt-4 p-3 bg-gray-50 rounded-lg">
                    <ul class="requirement-list space-y-1">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Foto jelas seluruh halaman</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Terlihat nomor KK dan data lengkap</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>File tidak blur atau gelap</span>
                        </li>
                    </ul>
                </div>

                <div id="kartu_keluargaFileInfo" class="file-info mt-4 p-4 bg-green-50 border border-green-200 rounded-lg {{ $registration && $registration->kartu_keluaga_path ? '' : 'hidden' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-pdf text-green-600 text-xl"></i>
                            <div>
                                <p class="font-medium text-gray-800" id="kartu_keluargaFileName">
                                    @if($registration && $registration->kartu_keluaga_path)
                                        {{ basename($registration->kartu_keluaga_path) }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                            </div>
                        </div>
                        @if($registration && $registration->kartu_keluaga_path)
                        <div class="file-actions flex gap-2">
                            <a href="{{ route('santri.documents.file', 'kartu_keluarga') }}" target="_blank" class="btn-view bg-primary text-white px-3 py-2 rounded-lg hover:bg-secondary transition duration-300 text-sm">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                            <button onclick="downloadDocument('kartu_keluarga')" class="btn-download bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition duration-300 text-sm">
                                <i class="fas fa-download mr-1"></i> Download
                            </button>
                            <button onclick="deleteDocument('kartu_keluarga')" class="btn-delete bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ijazah Card -->
            <div class="bg-white rounded-xl shadow-md p-6 document-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-primary">Ijazah</h3>
                            <p class="text-secondary text-sm">Fotokopi ijazah terakhir</p>
                        </div>
                    </div>
                    <span class="status-badge {{ $registration && $registration->ijazah_path ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $registration && $registration->ijazah_path ? 'fa-check' : 'fa-times' }} mr-1"></i>
                        {{ $registration && $registration->ijazah_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                    </span>
                </div>

                <div class="upload-area" id="ijazahUploadArea" data-type="ijazah">
                    <i class="fas fa-cloud-upload-alt upload-icon text-4xl text-primary mb-3"></i>
                    <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                    <p class="text-gray-500 text-sm">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                    <div class="upload-progress hidden mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="upload-progress-bar bg-primary h-2 rounded-full transition-all duration-300"
                                 id="ijazahProgressBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <input type="file" id="ijazahFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
                </div>

                <div class="document-requirements mt-4 p-3 bg-gray-50 rounded-lg">
                    <ul class="requirement-list space-y-1">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Foto jelas seluruh halaman</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Terlihat nilai dan stempel</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Ijazah SD/SMP/SMA sesuai jenjang</span>
                        </li>
                    </ul>
                </div>

                <div id="ijazahFileInfo" class="file-info mt-4 p-4 bg-green-50 border border-green-200 rounded-lg {{ $registration && $registration->ijazah_path ? '' : 'hidden' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-pdf text-green-600 text-xl"></i>
                            <div>
                                <p class="font-medium text-gray-800" id="ijazahFileName">
                                    @if($registration && $registration->ijazah_path)
                                        {{ basename($registration->ijazah_path) }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                            </div>
                        </div>
                        @if($registration && $registration->ijazah_path)
                        <div class="file-actions flex gap-2">
                            <a href="{{ route('santri.documents.file', 'ijazah') }}" target="_blank" class="btn-view bg-primary text-white px-3 py-2 rounded-lg hover:bg-secondary transition duration-300 text-sm">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                            <button onclick="downloadDocument('ijazah')" class="btn-download bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition duration-300 text-sm">
                                <i class="fas fa-download mr-1"></i> Download
                            </button>
                            <button onclick="deleteDocument('ijazah')" class="btn-delete bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Akta Kelahiran Card -->
            <div class="bg-white rounded-xl shadow-md p-6 document-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-birthday-cake text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-primary">Akta Kelahiran</h3>
                            <p class="text-secondary text-sm">Fotokopi akta kelahiran</p>
                        </div>
                    </div>
                    <span class="status-badge {{ $registration && $registration->akta_kelahiran_path ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $registration && $registration->akta_kelahiran_path ? 'fa-check' : 'fa-times' }} mr-1"></i>
                        {{ $registration && $registration->akta_kelahiran_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                    </span>
                </div>

                <div class="upload-area" id="akta_kelahiranUploadArea" data-type="akta_kelahiran">
                    <i class="fas fa-cloud-upload-alt upload-icon text-4xl text-primary mb-3"></i>
                    <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                    <p class="text-gray-500 text-sm">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                    <div class="upload-progress hidden mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="upload-progress-bar bg-primary h-2 rounded-full transition-all duration-300"
                                 id="akta_kelahiranProgressBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <input type="file" id="akta_kelahiranFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
                </div>

                <div class="document-requirements mt-4 p-3 bg-gray-50 rounded-lg">
                    <ul class="requirement-list space-y-1">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Foto jelas seluruh halaman</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Terlihat nomor akta dan data lengkap</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Diterbitkan oleh dinas catatan sipil</span>
                        </li>
                    </ul>
                </div>

                <div id="akta_kelahiranFileInfo" class="file-info mt-4 p-4 bg-green-50 border border-green-200 rounded-lg {{ $registration && $registration->akta_kelahiran_path ? '' : 'hidden' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-pdf text-green-600 text-xl"></i>
                            <div>
                                <p class="font-medium text-gray-800" id="akta_kelahiranFileName">
                                    @if($registration && $registration->akta_kelahiran_path)
                                        {{ basename($registration->akta_kelahiran_path) }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                            </div>
                        </div>
                        @if($registration && $registration->akta_kelahiran_path)
                        <div class="file-actions flex gap-2">
                            <a href="{{ route('santri.documents.file', 'akta_kelahiran') }}" target="_blank" class="btn-view bg-primary text-white px-3 py-2 rounded-lg hover:bg-secondary transition duration-300 text-sm">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                            <button onclick="downloadDocument('akta_kelahiran')" class="btn-download bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition duration-300 text-sm">
                                <i class="fas fa-download mr-1"></i> Download
                            </button>
                            <button onclick="deleteDocument('akta_kelahiran')" class="btn-delete bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pas Foto Card -->
            <div class="bg-white rounded-xl shadow-md p-6 document-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-camera text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-primary">Pas Foto</h3>
                            <p class="text-secondary text-sm">Foto terbaru latar merah</p>
                        </div>
                    </div>
                    <span class="status-badge {{ $registration && $registration->pas_foto_path ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $registration && $registration->pas_foto_path ? 'fa-check' : 'fa-times' }} mr-1"></i>
                        {{ $registration && $registration->pas_foto_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                    </span>
                </div>

                <div class="upload-area" id="pas_fotoUploadArea" data-type="pas_foto">
                    <i class="fas fa-cloud-upload-alt upload-icon text-4xl text-primary mb-3"></i>
                    <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                    <p class="text-gray-500 text-sm">Format: JPEG, PNG (Maks. 5MB)</p>
                    <div class="upload-progress hidden mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="upload-progress-bar bg-primary h-2 rounded-full transition-all duration-300"
                                 id="pas_fotoProgressBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <input type="file" id="pas_fotoFile" accept=".jpeg,.jpg,.png" class="hidden">
                </div>

                <div class="document-requirements mt-4 p-3 bg-gray-50 rounded-lg">
                    <ul class="requirement-list space-y-1">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Ukuran 3x4 atau 4x6</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Latar belakang warna merah</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Pakaian sopan dan rapi</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Wajah terlihat jelas</span>
                        </li>
                    </ul>
                </div>

                <div id="pas_fotoFileInfo" class="file-info mt-4 p-4 bg-green-50 border border-green-200 rounded-lg {{ $registration && $registration->pas_foto_path ? '' : 'hidden' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-image text-green-600 text-xl"></i>
                            <div>
                                <p class="font-medium text-gray-800" id="pas_fotoFileName">
                                    @if($registration && $registration->pas_foto_path)
                                        {{ basename($registration->pas_foto_path) }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                            </div>
                        </div>
                        @if($registration && $registration->pas_foto_path)
                        <div class="file-actions flex gap-2">
                            <a href="{{ route('santri.documents.file', 'pas_foto') }}" target="_blank" class="btn-view bg-primary text-white px-3 py-2 rounded-lg hover:bg-secondary transition duration-300 text-sm">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                            <button onclick="downloadDocument('pas_foto')" class="btn-download bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition duration-300 text-sm">
                                <i class="fas fa-download mr-1"></i> Download
                            </button>
                            <button onclick="deleteDocument('pas_foto')" class="btn-delete bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress & Actions Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mt-6">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-primary mb-2">Progress Pendaftaran</h2>
                <p class="text-secondary">Lengkapi semua dokumen untuk menyelesaikan pendaftaran</p>
            </div>

            @if($registration)
            <div class="max-w-2xl mx-auto">
                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-3">
                        <span>Kelengkapan Dokumen</span>
                        <span id="progressText">
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
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-primary h-3 rounded-full transition-all duration-300"
                             id="overallProgressBar" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                <!-- Status Message -->
                <div class="text-center mb-6">
                    @if($percentage == 100)
                        @if($registration->status_pendaftaran == 'menunggu_diverifikasi')
                        <div class="inline-flex items-center space-x-2 bg-blue-100 text-blue-800 px-6 py-3 rounded-full">
                            <i class="fas fa-clock text-xl"></i>
                            <span class="font-semibold">Pendaftaran sedang diverifikasi oleh admin</span>
                        </div>
                        @else
                        <div class="inline-flex items-center space-x-2 bg-green-100 text-green-800 px-6 py-3 rounded-full">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span class="font-semibold">Semua dokumen telah lengkap! Anda bisa menyelesaikan pendaftaran.</span>
                        </div>
                        @endif
                    @else
                    <div class="inline-flex items-center space-x-2 bg-orange-100 text-orange-800 px-6 py-3 rounded-full">
                        <i class="fas fa-info-circle text-xl"></i>
                        <span class="font-semibold">Lengkapi {{ 4 - $uploadedCount }} dokumen lagi untuk menyelesaikan pendaftaran.</span>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('santri.biodata.index') }}" class="bg-secondary text-white px-6 py-3 rounded-full hover:bg-gray-600 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Biodata
                    </a>

                    @if($percentage == 100 && $registration->status_pendaftaran != 'menunggu_diverifikasi')
                    <button onclick="completeRegistration()" class="bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i> Selesaikan Pendaftaran
                    </button>
                    @elseif($registration->status_pendaftaran == 'menunggu_diverifikasi')
                    <button class="bg-blue-500 text-white px-6 py-3 rounded-full cursor-not-allowed flex items-center justify-center" disabled>
                        <i class="fas fa-clock mr-2"></i> Menunggu Verifikasi Admin
                    </button>
                    @else
                    <button onclick="showCompletionWarning()" class="bg-gray-400 text-white px-6 py-3 rounded-full cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i> Lengkapi Dokumen
                    </button>
                    @endif
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Biodata Belum Lengkap</h3>
                <p class="text-gray-600 mb-6">Silakan isi biodata terlebih dahulu sebelum mengunggah dokumen.</p>
                <a href="{{ route('santri.biodata.index') }}" class="bg-primary text-white px-6 py-3 rounded-full hover:bg-secondary transition duration-300 inline-flex items-center">
                    <i class="fas fa-user-edit mr-2"></i> Isi Biodata Sekarang
                </a>
            </div>
            @endif
        </div>

        <!-- Expiry Notice -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-6">
            <div class="flex items-start space-x-3">
                <i class="fas fa-clock text-yellow-500 text-lg mt-1"></i>
                <div>
                    <h4 class="font-semibold text-yellow-800">Perhatian Masa Penyimpanan Dokumen</h4>
                    <p class="text-yellow-700 text-sm mt-1">Dokumen yang diunggah akan disimpan selama maksimal 4 tahun dan akan dihapus secara otomatis oleh sistem setelah melewati batas waktu tersebut untuk menjaga privasi dan keamanan data.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 px-4 mt-8">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2025 PPDB Pesantren AI-Our'an Bani Syahid</p>
        </div>
    </footer>

    <style>
        .status-badge {
            @apply px-3 py-1 rounded-full text-sm font-medium;
        }

        .upload-area {
            @apply border-2 border-dashed border-gray-300 rounded-xl p-6 text-center transition duration-300 cursor-pointer bg-gray-50 hover:bg-gray-100;
        }

        .upload-area.dragover {
            @apply border-primary bg-blue-50;
        }

        .document-card {
            @apply transition duration-300 hover:shadow-lg;
        }

        .file-actions .btn-view,
        .file-actions .btn-download,
        .file-actions .btn-delete {
            @apply transition duration-300;
        }

        .upload-progress-bar {
            transition: width 0.3s ease;
        }

        .hidden {
            display: none !important;
        }
    </style>
</div>
@endsection

@section('scripts')
<script>
    // Global variables
    const documentTypes = ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'];
    let uploadInProgress = false;

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing document upload system...');
        initializeDocumentUpload();
        initializeDragAndDrop();
    });

    function initializeDocumentUpload() {
        documentTypes.forEach(type => {
            const uploadArea = document.getElementById(`${type}UploadArea`);
            const fileInput = document.getElementById(`${type}File`);

            if (uploadArea && fileInput) {
                initFileUpload(type);
            } else {
                console.warn(`Elements for ${type} not found`);
            }
        });
    }

    function initializeDragAndDrop() {
        // Global drag and drop handlers
        document.addEventListener('dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        document.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        document.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        document.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    }

    function initFileUpload(documentType) {
        const uploadArea = document.getElementById(`${documentType}UploadArea`);
        const fileInput = document.getElementById(`${documentType}File`);
        const progressBar = document.getElementById(`${documentType}ProgressBar`);
        const uploadProgress = uploadArea.querySelector('.upload-progress');

        if (!uploadArea || !fileInput) {
            console.error(`Required elements for ${documentType} not found`);
            return;
        }

        // Click to select file
        uploadArea.addEventListener('click', (e) => {
            if (!uploadInProgress) {
                fileInput.click();
            }
        });

        // File input change
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0 && !uploadInProgress) {
                handleFiles(this.files, documentType);
            }
        });

        // Drag and drop events
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (!uploadInProgress) {
                    uploadArea.classList.add('dragover');
                }
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadArea.classList.remove('dragover');
            });
        });

        uploadArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0 && !uploadInProgress) {
                handleFiles(files, documentType);
            }
        });
    }

    function handleFiles(files, documentType) {
        if (files.length > 0) {
            const file = files[0];
            console.log(`Processing file for ${documentType}:`, file.name, file.type, file.size);

            if (validateFile(file, documentType)) {
                uploadFile(file, documentType);
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

        // Validasi tipe file
        if (!allowedTypes[documentType].includes(file.type)) {
            let allowedFormats = documentType === 'pas_foto' ? 'JPEG, JPG, PNG' : 'PDF, JPEG, JPG, PNG';
            Swal.fire({
                icon: 'error',
                title: 'Format File Tidak Valid',
                text: `File ${documentType.replace(/_/g, ' ')} harus dalam format: ${allowedFormats}`,
                confirmButtonText: 'Mengerti'
            });
            return false;
        }

        // Validasi ukuran file
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal 5MB. Silakan kompres file Anda atau pilih file yang lebih kecil.',
                confirmButtonText: 'Mengerti'
            });
            return false;
        }

        return true;
    }

    function uploadFile(file, documentType) {
        if (uploadInProgress) {
            Swal.fire({
                icon: 'warning',
                title: 'Upload Sedang Berlangsung',
                text: 'Tunggu hingga upload selesai sebelum mengupload file lain.',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        uploadInProgress = true;

        const formData = new FormData();
        formData.append('file', file);

        const uploadArea = document.getElementById(`${documentType}UploadArea`);
        const progressBar = document.getElementById(`${documentType}ProgressBar`);
        const uploadProgress = uploadArea.querySelector('.upload-progress');

        // Show progress
        uploadProgress.classList.remove('hidden');
        progressBar.style.width = '0%';

        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress > 90) {
                progress = 90;
                clearInterval(progressInterval);
            }
            progressBar.style.width = `${progress}%`;
        }, 200);

        Swal.fire({
            title: 'Mengunggah File...',
            html: `Sedang mengupload <strong>${file.name}</strong> untuk ${documentType.replace(/_/g, ' ')}`,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/santri/documents/upload/${documentType}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            clearInterval(progressInterval);
            progressBar.style.width = '100%';

            // Cek content type untuk memastikan response JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error('Server mengembalikan response yang tidak valid');
            }

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || `Error ${response.status}: ${response.statusText}`);
            }
            return data;
        })
        .then(data => {
            setTimeout(() => {
                Swal.close();
                uploadProgress.classList.add('hidden');
                uploadInProgress = false;

                if (data.success) {
                    updateFileInfo(documentType, data.file_name);
                    updateDocumentStatus(documentType, true);
                    updateOverallProgress();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                } else {
                    throw new Error(data.message);
                }
            }, 500);
        })
        .catch(error => {
            clearInterval(progressInterval);
            uploadProgress.classList.add('hidden');
            uploadInProgress = false;
            Swal.close();

            console.error('Upload error:', error);

            let errorMessage = 'Terjadi kesalahan saat mengunggah file. Silakan coba lagi.';
            if (error.message.includes('Server mengembalikan response yang tidak valid')) {
                errorMessage = 'Terjadi kesalahan server. Silakan refresh halaman dan coba lagi.';
            } else if (error.message) {
                errorMessage = error.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Upload Gagal',
                text: errorMessage,
                confirmButtonText: 'Mengerti'
            });
        });
    }

    function updateFileInfo(documentType, fileName) {
        const fileInfo = document.getElementById(`${documentType}FileInfo`);
        const fileNameElement = document.getElementById(`${documentType}FileName`);

        if (fileInfo && fileNameElement) {
            // Shorten filename if too long
            const displayName = fileName.length > 30
                ? fileName.substring(0, 27) + '...'
                : fileName;

            fileNameElement.textContent = displayName;
            fileInfo.classList.remove('hidden');

            // Update action buttons
            const actionsHtml = `
                <div class="file-actions flex gap-2">
                    <a href="/santri/documents/file/${documentType}" target="_blank" class="btn-view bg-primary text-white px-3 py-2 rounded-lg hover:bg-secondary transition duration-300 text-sm">
                        <i class="fas fa-eye mr-1"></i> Lihat
                    </a>
                    <button onclick="downloadDocument('${documentType}')" class="btn-download bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition duration-300 text-sm">
                        <i class="fas fa-download mr-1"></i> Download
                    </button>
                    <button onclick="deleteDocument('${documentType}')" class="btn-delete bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                        <i class="fas fa-trash mr-1"></i> Hapus
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

        if (statusElement) {
            if (isUploaded) {
                statusElement.className = 'status-badge bg-green-100 text-green-800';
                statusElement.innerHTML = '<i class="fas fa-check mr-1"></i> Telah Diunggah';
            } else {
                statusElement.className = 'status-badge bg-red-100 text-red-800';
                statusElement.innerHTML = '<i class="fas fa-times mr-1"></i> Belum Diunggah';
            }
        }
    }

    async function updateOverallProgress() {
        try {
            const response = await fetch('/santri/documents/progress', {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                const progress = data.progress;
                const progressBar = document.getElementById('overallProgressBar');
                const progressText = document.getElementById('progressText');

                if (progressBar && progressText) {
                    progressBar.style.width = `${progress.percentage}%`;
                    progressText.textContent = `${progress.uploaded}/${progress.total} Dokumen`;
                }
            }
        } catch (error) {
            console.error('Error fetching progress:', error);
        }
    }

    // Download document function
    window.downloadDocument = function(documentType) {
        // Show loading
        Swal.fire({
            title: 'Mendownload...',
            text: 'Sedang mempersiapkan file untuk download',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Create a temporary link to trigger download
        const link = document.createElement('a');
        link.href = `/santri/documents/download/${documentType}`;
        link.target = '_blank';

        // Coba download dengan fetch untuk error handling
        fetch(`/santri/documents/download/${documentType}`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            Swal.close();

            if (!response.ok) {
                // Jika response tidak ok, coba parse error message
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Download gagal');
                });
            }

            // Jika response ok, trigger download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                icon: 'success',
                title: 'Download Berhasil',
                text: 'File berhasil didownload',
                confirmButtonText: 'OK'
            });
        })
        .catch(error => {
            Swal.close();
            console.error('Download error:', error);

            Swal.fire({
                icon: 'error',
                title: 'Download Gagal',
                text: error.message || 'Terjadi kesalahan saat mendownload file',
                confirmButtonText: 'Mengerti'
            });
        });
    };

    // Global functions accessible from HTML
    window.deleteDocument = function(documentType) {
        if (uploadInProgress) {
            Swal.fire({
                icon: 'warning',
                title: 'Tunggu Sebentar',
                text: 'Tunggu hingga proses upload selesai sebelum menghapus file.',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Dokumen?',
            html: `Anda akan menghapus file <strong>${documentType.replace(/_/g, ' ')}</strong>. Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/santri/documents/delete/${documentType}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Network response was not ok');
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        const fileInfo = document.getElementById(`${documentType}FileInfo`);
                        if (fileInfo) {
                            fileInfo.classList.add('hidden');
                        }
                        updateDocumentStatus(documentType, false);
                        updateOverallProgress();

                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghapus',
                        text: error.message || 'Terjadi kesalahan saat menghapus file.',
                        confirmButtonText: 'Mengerti'
                    });
                });
            }
        });
    };

    window.showCompletionWarning = function() {
        Swal.fire({
            icon: 'warning',
            title: 'Dokumen Belum Lengkap',
            html: `Harap lengkapi semua dokumen terlebih dahulu sebelum menyelesaikan pendaftaran.<br><br>
                  <small class="text-gray-600">Periksa kembali bahwa semua dokumen telah diunggah dengan benar.</small>`,
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#3b82f6'
        });
    };

    window.completeRegistration = function() {
        if (uploadInProgress) {
            Swal.fire({
                icon: 'warning',
                title: 'Tunggu Sebentar',
                text: 'Tunggu hingga proses upload selesai sebelum menyelesaikan pendaftaran.',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: 'Selesaikan Pendaftaran?',
            html: `
                <p>Pastikan semua data dan dokumen sudah lengkap dan benar sebelum melanjutkan.</p>
                <div class="text-left mt-4 p-3 bg-yellow-50 rounded-lg">
                    <p class="text-sm font-semibold text-yellow-800">Perhatian:</p>
                    <ul class="text-sm text-yellow-700 mt-2 list-disc list-inside">
                        <li>Data tidak dapat diubah setelah pendaftaran diselesaikan</li>
                        <li>Tim admin akan memverifikasi data Anda</li>
                        <li>Status pendaftaran akan berubah menjadi "Menunggu Verifikasi"</li>
                    </ul>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Selesaikan Pendaftaran',
            cancelButtonText: 'Periksa Kembali',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyelesaikan Pendaftaran...',
                    text: 'Sedang mengirim data untuk verifikasi',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`{{ route('santri.documents.complete') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Network response was not ok');
                    }
                    return data;
                })
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pendaftaran Berhasil!',
                            html: `
                                <p>${data.message}</p>
                                <div class="mt-4 p-3 bg-green-50 rounded-lg">
                                    <p class="text-sm text-green-700">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Tim admin akan menghubungi Anda melalui WhatsApp untuk informasi selanjutnya.
                                    </p>
                                </div>
                            `,
                            confirmButtonText: 'Kembali ke Dashboard',
                            confirmButtonColor: '#10b981',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            window.location.href = '{{ route("santri.dashboard") }}';
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Complete registration error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyelesaikan Pendaftaran',
                        text: error.message || 'Terjadi kesalahan saat menyelesaikan pendaftaran. Silakan coba lagi.',
                        confirmButtonText: 'Mengerti'
                    });
                });
            }
        });
    };

    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + Enter to complete registration
        if (e.ctrlKey && e.key === 'Enter') {
            const progressBar = document.getElementById('overallProgressBar');
            if (progressBar && progressBar.style.width === '100%') {
                completeRegistration();
            }
        }
    });
</script>
@endsection

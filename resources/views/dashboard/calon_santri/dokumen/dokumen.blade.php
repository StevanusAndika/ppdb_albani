@extends('layouts.app')

@section('title', 'Upload Dokumen - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .upload-area {
        @apply border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition duration-300 cursor-pointer;
    }
    .upload-area.active {
        @apply border-primary bg-primary/5;
    }
    .document-card {
        @apply bg-white rounded-xl shadow-md p-6 mb-6;
    }
    .file-info {
        @apply flex items-center space-x-3 mt-3 p-3 bg-green-50 rounded-lg;
    }
    .document-status {
        @apply inline-flex items-center px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-uploaded {
        @apply bg-green-100 text-green-800;
    }
    .status-missing {
        @apply bg-red-100 text-red-800;
    }

    .package-info {
        @apply bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6;
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
                <a href="{{ route('santri.dashboard') }}" class="text-primary hover:text-secondary font-medium">Dashboard</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary hover:text-secondary font-medium">Biodata</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary font-medium">Dokumen</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded-full hover:bg-secondary transition duration-300">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-6 px-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-primary mb-2">Upload Dokumen</h1>
            <p class="text-secondary">Unggah dokumen persyaratan pendaftaran</p>

            <!-- Status Pendaftaran -->
            @if($registration)
            <div class="mt-4 p-4 bg-white rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-700">Status Pendaftaran:</p>
                        <p class="text-lg font-bold {{ $registration->status_pendaftaran == 'ditolak' ? 'text-red-600' : 'text-primary' }}">
                            {{ $registration->status_label }}
                        </p>
                        @if($registration->status_pendaftaran == 'ditolak' && $registration->catatan_admin)
                        <p class="text-sm text-red-600 mt-1">{{ $registration->catatan_admin }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">ID Pendaftaran</p>
                        <p class="font-mono font-bold text-primary">{{ $registration->id_pendaftaran }}</p>
                    </div>
                </div>
            </div>

            <!-- Info Paket yang Dipilih -->
            @if($registration->package)
            <div class="package-info">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-blue-800">Paket Dipilih:</p>
                        <p class="text-blue-700">{{ $registration->package->name }}</p>
                        <p class="text-sm text-blue-600">{{ $registration->package->description }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-600">Total Biaya</p>
                        <p class="font-bold text-blue-800">{{ $registration->formatted_total_biaya }}</p>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>

        <!-- Kartu Keluarga -->
        <div class="document-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-primary">Fotokopi Kartu Keluarga</h2>
                <span class="document-status {{ $registration && $registration->kartu_keluaga_path ? 'status-uploaded' : 'status-missing' }}">
                    <i class="fas {{ $registration && $registration->kartu_keluaga_path ? 'fa-check' : 'fa-times' }} mr-1"></i>
                    {{ $registration && $registration->kartu_keluaga_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                </span>
            </div>
            <div class="upload-area" id="kkUploadArea">
                <i class="fas fa-file-pdf text-4xl text-primary mb-3"></i>
                <p class="text-gray-600 mb-2">Seret file ke sini atau klik untuk memilih</p>
                <p class="text-sm text-gray-500">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                <input type="file" id="kkFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
            </div>
            <div id="kkFileInfo" class="file-info {{ $registration && $registration->kartu_keluaga_path ? '' : 'hidden' }}">
                <i class="fas fa-file-pdf text-green-500 text-xl"></i>
                <div class="flex-1">
                    <p class="font-medium text-gray-800" id="kkFileName">
                        @if($registration && $registration->kartu_keluaga_path)
                            File telah diunggah
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                </div>
                @if($registration && $registration->kartu_keluaga_path)
                <div class="flex space-x-2">
                    <a href="{{ route('santri.documents.file', 'kartu_keluarga') }}" target="_blank"
                       class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300 text-sm">
                        <i class="fas fa-eye mr-1"></i> Lihat
                    </a>
                    <button onclick="deleteDocument('kartu_keluarga')"
                            class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Ijazah -->
        <div class="document-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-primary">Fotokopi Ijazah</h2>
                <span class="document-status {{ $registration && $registration->ijazah_path ? 'status-uploaded' : 'status-missing' }}">
                    <i class="fas {{ $registration && $registration->ijazah_path ? 'fa-check' : 'fa-times' }} mr-1"></i>
                    {{ $registration && $registration->ijazah_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                </span>
            </div>
            <div class="upload-area" id="ijazahUploadArea">
                <i class="fas fa-file-pdf text-4xl text-primary mb-3"></i>
                <p class="text-gray-600 mb-2">Seret file ke sini atau klik untuk memilih</p>
                <p class="text-sm text-gray-500">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                <input type="file" id="ijazahFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
            </div>
            <div id="ijazahFileInfo" class="file-info {{ $registration && $registration->ijazah_path ? '' : 'hidden' }}">
                <i class="fas fa-file-pdf text-green-500 text-xl"></i>
                <div class="flex-1">
                    <p class="font-medium text-gray-800" id="ijazahFileName">
                        @if($registration && $registration->ijazah_path)
                            File telah diunggah
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                </div>
                @if($registration && $registration->ijazah_path)
                <div class="flex space-x-2">
                    <a href="{{ route('santri.documents.file', 'ijazah') }}" target="_blank"
                       class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300 text-sm">
                        <i class="fas fa-eye mr-1"></i> Lihat
                    </a>
                    <button onclick="deleteDocument('ijazah')"
                            class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Akta Kelahiran -->
        <div class="document-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-primary">Fotokopi Akta Kelahiran</h2>
                <span class="document-status {{ $registration && $registration->akta_kelahiran_path ? 'status-uploaded' : 'status-missing' }}">
                    <i class="fas {{ $registration && $registration->akta_kelahiran_path ? 'fa-check' : 'fa-times' }} mr-1"></i>
                    {{ $registration && $registration->akta_kelahiran_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                </span>
            </div>
            <div class="upload-area" id="aktaUploadArea">
                <i class="fas fa-file-pdf text-4xl text-primary mb-3"></i>
                <p class="text-gray-600 mb-2">Seret file ke sini atau klik untuk memilih</p>
                <p class="text-sm text-gray-500">Format: PDF, JPEG, PNG (Maks. 5MB)</p>
                <input type="file" id="aktaFile" accept=".pdf,.jpeg,.jpg,.png" class="hidden">
            </div>
            <div id="aktaFileInfo" class="file-info {{ $registration && $registration->akta_kelahiran_path ? '' : 'hidden' }}">
                <i class="fas fa-file-pdf text-green-500 text-xl"></i>
                <div class="flex-1">
                    <p class="font-medium text-gray-800" id="aktaFileName">
                        @if($registration && $registration->akta_kelahiran_path)
                            File telah diunggah
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                </div>
                @if($registration && $registration->akta_kelahiran_path)
                <div class="flex space-x-2">
                    <a href="{{ route('santri.documents.file', 'akta_kelahiran') }}" target="_blank"
                       class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300 text-sm">
                        <i class="fas fa-eye mr-1"></i> Lihat
                    </a>
                    <button onclick="deleteDocument('akta_kelahiran')"
                            class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Pas Foto -->
        <div class="document-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-primary">Pas Foto</h2>
                <span class="document-status {{ $registration && $registration->pas_foto_path ? 'status-uploaded' : 'status-missing' }}">
                    <i class="fas {{ $registration && $registration->pas_foto_path ? 'fa-check' : 'fa-times' }} mr-1"></i>
                    {{ $registration && $registration->pas_foto_path ? 'Telah Diunggah' : 'Belum Diunggah' }}
                </span>
            </div>
            <div class="upload-area" id="fotoUploadArea">
                <i class="fas fa-camera text-4xl text-primary mb-3"></i>
                <p class="text-gray-600 mb-2">Seret file ke sini atau klik untuk memilih</p>
                <p class="text-sm text-gray-500">Format: JPEG, PNG (Maks. 5MB)</p>
                <input type="file" id="fotoFile" accept=".jpeg,.jpg,.png" class="hidden">
            </div>
            <div id="fotoFileInfo" class="file-info {{ $registration && $registration->pas_foto_path ? '' : 'hidden' }}">
                <i class="fas fa-image text-green-500 text-xl"></i>
                <div class="flex-1">
                    <p class="font-medium text-gray-800" id="fotoFileName">
                        @if($registration && $registration->pas_foto_path)
                            File telah diunggah
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                </div>
                @if($registration && $registration->pas_foto_path)
                <div class="flex space-x-2">
                    <a href="{{ route('santri.documents.file', 'pas_foto') }}" target="_blank"
                       class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300 text-sm">
                        <i class="fas fa-eye mr-1"></i> Lihat
                    </a>
                    <button onclick="deleteDocument('pas_foto')"
                            class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Progress & Actions -->
        <div class="document-card">
            <h2 class="text-xl font-bold text-primary mb-4">Progress Pendaftaran</h2>

            @if($registration)
            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
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
                        {{ $uploadedCount }}/4
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full transition-all duration-300"
                         style="width: {{ $percentage }}%"></div>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    @if($percentage == 100)
                    <p class="text-green-600 font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>Semua dokumen telah diunggah
                    </p>
                    @else
                    <p class="text-orange-600">
                        <i class="fas fa-info-circle mr-2"></i>Lengkapi semua dokumen untuk menyelesaikan pendaftaran
                    </p>
                    @endif
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('santri.biodata.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300">
                        Kembali ke Biodata
                    </a>

                    @if($percentage == 100)
                    <button onclick="completeRegistration()"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300">
                        Selesaikan Pendaftaran
                    </button>
                    @else
                    <button onclick="showCompletionWarning()"
                        class="px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                        Lengkapi Dokumen
                    </button>
                    @endif
                </div>
            </div>
            @else
            <div class="text-center py-6">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-3"></i>
                <p class="text-gray-600 mb-4">Anda belum mengisi biodata. Silakan isi biodata terlebih dahulu sebelum mengunggah dokumen.</p>
                <a href="{{ route('santri.biodata.index') }}"
                   class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition duration-300">
                    Isi Biodata Sekarang
                </a>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    const documentTypes = ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'];

    // Initialize file upload for each document type
    documentTypes.forEach(type => {
        initFileUpload(type);
    });

    function initFileUpload(documentType) {
        const uploadArea = document.getElementById(`${documentType}UploadArea`);
        const fileInput = document.getElementById(`${documentType}File`);
        const fileInfo = document.getElementById(`${documentType}FileInfo`);

        // Click to select file
        uploadArea.addEventListener('click', () => fileInput.click());

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => uploadArea.classList.add('active'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => uploadArea.classList.remove('active'), false);
        });

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFiles(files);
        }

        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
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

        if (!allowedTypes[documentType].includes(file.type)) {
            let allowedFormats = documentType === 'pas_foto' ? 'JPEG, PNG' : 'PDF, JPEG, PNG';
            Swal.fire({
                icon: 'error',
                title: 'Format tidak valid',
                text: `Hanya file ${allowedFormats} yang diizinkan untuk ${documentType.replace('_', ' ')}.`,
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu besar',
                text: 'Ukuran file maksimal 5MB.',
                confirmButtonText: 'OK'
            });
            return false;
        }

        return true;
    }

    function uploadFile(file, documentType) {
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

        // FIXED: Use absolute path instead of route helper
        fetch(`/santri/documents/upload/${documentType}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();

            if (data.success) {
                updateFileInfo(documentType, file.name);
                updateDocumentStatus(documentType, true);
                checkCompletionProgress();

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

        fileNameElement.textContent = fileName;
        fileInfo.classList.remove('hidden');

        // Update action buttons
        const actionsHtml = `
            <div class="flex space-x-2">
                <a href="/santri/documents/file/${documentType}" target="_blank"
                   class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300 text-sm">
                    <i class="fas fa-eye mr-1"></i> Lihat
                </a>
                <button onclick="deleteDocument('${documentType}')"
                        class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
        `;

        const existingActions = fileInfo.querySelector('.flex.space-x-2');
        if (existingActions) {
            existingActions.remove();
        }
        fileInfo.insertAdjacentHTML('beforeend', actionsHtml);
    }

    function updateDocumentStatus(documentType, isUploaded) {
        const statusElement = document.querySelector(`#${documentType}UploadArea`).parentElement.querySelector('.document-status');
        if (statusElement) {
            if (isUploaded) {
                statusElement.className = 'document-status status-uploaded';
                statusElement.innerHTML = '<i class="fas fa-check mr-1"></i> Telah Diunggah';
            } else {
                statusElement.className = 'document-status status-missing';
                statusElement.innerHTML = '<i class="fas fa-times mr-1"></i> Belum Diunggah';
            }
        }
    }

    function checkCompletionProgress() {
        // Reload page to update progress bar
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    function deleteDocument(documentType) {
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
                // FIXED: Use absolute path instead of route helper
                fetch(`/santri/documents/delete/${documentType}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const fileInfo = document.getElementById(`${documentType}FileInfo`);
                        fileInfo.classList.add('hidden');
                        updateDocumentStatus(documentType, false);
                        checkCompletionProgress();

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
                });
            }
        });
    }

    function showCompletionWarning() {
        Swal.fire({
            icon: 'warning',
            title: 'Dokumen Belum Lengkap',
            text: 'Harap lengkapi semua dokumen terlebih dahulu sebelum menyelesaikan pendaftaran.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#3b82f6'
        });
    }

    function completeRegistration() {
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
                .then(response => response.json())
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

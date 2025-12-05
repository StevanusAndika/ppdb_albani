@extends('layouts.app')

@section('title', 'Upload Dokumen - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar Calon Santri -->
    @include('layouts.components.calon_santri.navbar')

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
                    <!-- Tombol Selesaikan Pendaftaran akan muncul otomatis via JavaScript -->
                    <div id="completeRegistrationButtonContainer"></div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
        <!-- Auto Refresh Notice -->
        <div id="autoRefreshNotice" class="hidden mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-center">
            <div class="flex items-center justify-center space-x-2">
                <i class="fas fa-sync-alt animate-spin"></i>
                <span class="font-semibold">Semua dokumen telah lengkap! Halaman akan direfresh otomatis dalam <span id="countdown">3</span> detik...</span>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg text-center">
            <div class="flex items-center justify-center space-x-2">
                <i class="fas fa-spinner animate-spin"></i>
                <span class="font-semibold">Memuat data dokumen...</span>
            </div>
        </div>

        <!-- Success Upload Notification -->
        <div id="successNotification" class="hidden mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-semibold" id="successMessage"></span>
                </div>
                <button onclick="hideSuccessNotification()" class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Documents Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-6 mb-8" id="documentsGrid">
            @foreach(['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'] as $documentType)
            @php
                $documentConfig = [
                    'kartu_keluarga' => [
                        'title' => 'Kartu Keluarga',
                        'description' => 'Fotokopi yang jelas',
                        'icon' => 'fa-users',
                        'color' => 'blue',
                        'requirements' => [
                            'Foto jelas seluruh halaman',
                            'Terlihat nomor KK dan data lengkap',
                            'File tidak blur atau gelap'
                        ]
                    ],
                    'ijazah' => [
                        'title' => 'Ijazah',
                        'description' => 'Fotokopi ijazah terakhir',
                        'icon' => 'fa-graduation-cap',
                        'color' => 'green',
                        'requirements' => [
                            'Foto jelas seluruh halaman',
                            'Terlihat nilai dan stempel',
                            'SKL atau ijazah SD/Sederajat(SMP)',
                           'SKL atau ijazah SMP /Sederajat (SMA)',
                           'Rapor terakhir bagi yang belum lulus ',
                        

                        ]
                    ],
                    'akta_kelahiran' => [
                        'title' => 'Akta Kelahiran',
                        'description' => 'Fotokopi akta kelahiran',
                        'icon' => 'fa-birthday-cake',
                        'color' => 'purple',
                        'requirements' => [
                            'Foto jelas seluruh halaman',
                            'Terlihat nomor akta dan data lengkap',
                            'Dinas Kependudukan dan Pencatatan Sipil'
                        ]
                    ],
                    'pas_foto' => [
                        'title' => 'Pas Foto',
                        'description' => 'Foto terbaru latar merah',
                        'icon' => 'fa-camera',
                        'color' => 'orange',
                        'requirements' => [
                            'Ukuran 3x4',

                            'Kemeja atau koko putih untuk laki-laki',
                            'Gamis atau kemeja putih dan hijab ',
                            'Wajah terlihat jelas'
                        ]
                    ]
                ];

                $config = $documentConfig[$documentType];
                $filePath = $registration ? $registration->{$documentType . '_path'} : null;
                $isUploaded = !empty($filePath);
                $fileExtension = $isUploaded ? pathinfo($filePath, PATHINFO_EXTENSION) : '';
                $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png']);
                $fileIcon = $isImage ? 'fa-image text-green-600' : 'fa-file-pdf text-red-600';
            @endphp

            <div class="bg-white rounded-xl shadow-md p-6 document-card" id="{{ $documentType }}Card" data-document-type="{{ $documentType }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-{{ $config['color'] }}-100 rounded-full flex items-center justify-center">
                            <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }}-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-primary">{{ $config['title'] }}</h3>
                            <p class="text-secondary text-sm">{{ $config['description'] }}</p>
                        </div>
                    </div>
                    <span class="status-badge {{ $isUploaded ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" id="{{ $documentType }}Status">
                        <i class="fas {{ $isUploaded ? 'fa-check' : 'fa-times' }} mr-1"></i>
                        <span class="status-text">{{ $isUploaded ? 'Telah Diunggah' : 'Belum Diunggah' }}</span>
                    </span>
                </div>

                <div class="upload-area" id="{{ $documentType }}UploadArea" data-type="{{ $documentType }}">
                    <i class="fas fa-cloud-upload-alt upload-icon text-4xl text-primary mb-3"></i>
                    <p class="text-gray-700 font-medium mb-2">Klik atau seret file ke sini</p>
                    <p class="text-gray-500 text-sm">
                        Format: {{ $documentType == 'pas_foto' ? 'JPEG, PNG' : 'PDF, JPEG, PNG' }} (Maks. 5MB)
                    </p>
                    <div class="upload-progress hidden mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="upload-progress-bar bg-primary h-2 rounded-full transition-all duration-300"
                                 id="{{ $documentType }}ProgressBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <input type="file" id="{{ $documentType }}File"
                           accept="{{ $documentType == 'pas_foto' ? '.jpeg,.jpg,.png' : '.pdf,.jpeg,.jpg,.png' }}"
                           class="hidden">
                </div>

                <div class="document-requirements mt-4 p-3 bg-gray-50 rounded-lg">
                    <ul class="requirement-list space-y-1">
                        @foreach($config['requirements'] as $requirement)
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>{{ $requirement }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div id="{{ $documentType }}FileInfo" class="file-info mt-4 p-4 bg-green-50 border border-green-200 rounded-lg {{ $isUploaded ? '' : 'hidden' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas {{ $fileIcon }} text-xl"></i>
                            <div>
                                <p class="font-medium text-gray-800" id="{{ $documentType }}FileName">
                                    @if($isUploaded)
                                        {{ basename($filePath) }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                            </div>
                        </div>
                        @if($isUploaded)
                        <div class="file-actions flex gap-2">
                            <a href="{{ route('santri.documents.file', $documentType) }}" target="_blank" class="btn-view bg-primary text-white px-3 py-2 rounded-lg hover:bg-secondary transition duration-300 text-sm">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                            <button onclick="downloadDocument('{{ $documentType }}')" class="btn-download bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition duration-300 text-sm">
                                <i class="fas fa-download mr-1"></i> Download
                            </button>
                            <button onclick="deleteDocument('{{ $documentType }}')" class="btn-delete bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition duration-300 text-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
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
                                $remaining = 4 - $uploadedCount;
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
                    <div id="statusMessage">
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
                            <span class="font-semibold">Lengkapi {{ $remaining }} dokumen lagi untuk menyelesaikan pendaftaran.</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center" id="actionButtons">
                    <a href="{{ route('santri.biodata.index') }}" class="bg-secondary text-white px-6 py-3 rounded-full hover:bg-gray-600 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Biodata
                    </a>

                    <!-- Tombol akan diupdate via JavaScript -->
                    <div id="dynamicActionButton"></div>

                    <!-- Tombol Hapus Semua Dokumen -->
                    <div id="deleteAllButtonContainer"></div>
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

    <!-- Footer Calon Santri -->
    @include('layouts.components.calon_santri.footer')

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

        /* Auto refresh animation */
        .auto-refresh-notice {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Success notification animation */
        .slide-down {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>
@endsection

@section('scripts')
<script>
    // Global variables
    const documentTypes = ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'];
    let uploadInProgress = false;
    let autoRefreshTimer = null;

    // Initialize uploadedDocuments from server data
    let uploadedDocuments = {
        'kartu_keluarga': {{ !empty($registration->kartu_keluaga_path) ? 'true' : 'false' }},
        'ijazah': {{ !empty($registration->ijazah_path) ? 'true' : 'false' }},
        'akta_kelahiran': {{ !empty($registration->akta_kelahiran_path) ? 'true' : 'false' }},
        'pas_foto': {{ !empty($registration->pas_foto_path) ? 'true' : 'false' }}
    };

    // Save state to localStorage
    function saveDocumentState() {
        localStorage.setItem('uploadedDocuments', JSON.stringify(uploadedDocuments));
    }

    // Load state from localStorage
    function loadDocumentState() {
        const saved = localStorage.getItem('uploadedDocuments');
        if (saved) {
            const parsed = JSON.parse(saved);
            uploadedDocuments = { ...parsed, ...uploadedDocuments };
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing document upload system...');
        loadDocumentState();
        initializeDocumentUpload();
        initializeDragAndDrop();
        updateProgress();
        updateAllDocumentUI();
        updateActionButtons();
        updateHeaderButton();
        checkQuotaForDeleteAll();
    });

    // Fungsi untuk memeriksa kuota dan menampilkan tombol hapus semua
    async function checkQuotaForDeleteAll() {
        try {
            const response = await fetch('/santri/documents/check-quota-delete-all', {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                const deleteAllContainer = document.getElementById('deleteAllButtonContainer');

                if (deleteAllContainer && data.show_delete_all) {
                    deleteAllContainer.innerHTML = `
                        <button onclick="confirmDeleteAllDocuments()" class="bg-red-600 text-white px-6 py-3 rounded-full hover:bg-red-700 transition duration-300 flex items-center justify-center">
                            <i class="fas fa-trash-alt mr-2"></i> Hapus Semua Dokumen
                        </button>
                    `;
                } else if (deleteAllContainer) {
                    deleteAllContainer.innerHTML = '';
                }
            }
        } catch (error) {
            console.error('Failed to check quota for delete all:', error);
        }
    }

    // Konfirmasi hapus semua dokumen
    window.confirmDeleteAllDocuments = function() {
        Swal.fire({
            title: 'Hapus Semua Dokumen?',
            html: `
                <div class="text-left">
                    <p class="mb-4">Anda akan menghapus <strong>semua dokumen</strong> yang telah diupload. Tindakan ini:</p>
                    <ul class="list-disc list-inside space-y-2 text-sm text-gray-700 mb-4">
                        <li>Menghapus semua file dari sistem</li>
                        <li>Mengosongkan data dokumen di database</li>
                        <li>Tidak dapat dibatalkan</li>
                        <li>Anda harus mengupload ulang semua dokumen</li>
                    </ul>
                    <p class="text-red-600 font-semibold">Pastikan Anda benar-benar ingin melanjutkan!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                deleteAllDocuments();
            }
        });
    };

    // Fungsi hapus semua dokumen
    async function deleteAllDocuments() {
        if (uploadInProgress) {
            Swal.fire({
                icon: 'warning',
                title: 'Tunggu Sebentar',
                text: 'Tunggu hingga proses upload selesai sebelum menghapus dokumen.',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: 'Menghapus Semua Dokumen...',
            text: 'Sedang menghapus semua file dokumen',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('/santri/documents/delete-all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Reset semua state dokumen
                documentTypes.forEach(type => {
                    uploadedDocuments[type] = false;
                });
                saveDocumentState();

                // Update UI untuk semua dokumen
                updateAllDocumentUIAfterDeleteAll();
                updateProgress();
                updateActionButtons(0);
                updateHeaderButton(0);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `
                        <p>${data.message}</p>
                        <p class="text-sm text-gray-600 mt-2">${data.deleted_count} dokumen telah dihapus.</p>
                    `,
                    confirmButtonText: 'OK'
                });

                // Sembunyikan tombol hapus semua setelah berhasil
                const deleteAllContainer = document.getElementById('deleteAllButtonContainer');
                if (deleteAllContainer) {
                    deleteAllContainer.innerHTML = '';
                }

            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Delete all documents error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menghapus',
                text: error.message || 'Terjadi kesalahan saat menghapus semua dokumen.',
                confirmButtonText: 'Mengerti'
            });
        }
    }

    // Update UI khusus setelah hapus semua
    function updateAllDocumentUIAfterDeleteAll() {
        documentTypes.forEach(type => {
            const statusElement = document.getElementById(`${type}Status`);
            const fileInfo = document.getElementById(`${type}FileInfo`);
            const fileNameElement = document.getElementById(`${type}FileName`);

            if (statusElement) {
                statusElement.className = 'status-badge bg-red-100 text-red-800';
                statusElement.innerHTML = '<i class="fas fa-times mr-1"></i><span class="status-text">Belum Diunggah</span>';
            }

            if (fileInfo) {
                fileInfo.classList.add('hidden');
                const existingActions = fileInfo.querySelector('.file-actions');
                if (existingActions) {
                    existingActions.remove();
                }
            }

            if (fileNameElement) {
                fileNameElement.textContent = '';
            }
        });
    }

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

    // FUNGSI UPLOAD YANG DIPERBAIKI - TIDAK ADA REFRESH OTOMATIS
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

        const uploadUrl = `/santri/documents/upload/${documentType}`;

        fetch(uploadUrl, {
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

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                const text = await response.text();
                throw new Error(`Server returned non-JSON response`);
            }
        })
        .then(data => {
            setTimeout(() => {
                Swal.close();
                uploadProgress.classList.add('hidden');
                uploadInProgress = false;

                if (data.success) {
                    // Update state and UI
                    uploadedDocuments[documentType] = true;
                    saveDocumentState();

                    // Update UI dengan data dari server
                    updateDocumentUIFromServer(documentType, data);

                    // Update progress
                    updateProgressFromUpload(data);

                    // Cek kuota untuk tombol hapus semua
                    checkQuotaForDeleteAll();

                    // Tampilkan notifikasi sukses
                    showSuccessNotification('Dokumen berhasil diunggah!');

                    // Tampilkan notifikasi khusus jika semua dokumen lengkap
                    if (data.all_documents_complete) {
                        showAllCompleteNotification();
                    }

                } else {
                    throw new Error(data.message || 'Upload gagal tanpa pesan error');
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
            if (error.message.includes('non-JSON response')) {
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

    // Update UI dengan data dari server
    function updateDocumentUIFromServer(documentType, data) {
        const statusElement = document.getElementById(`${documentType}Status`);
        const fileInfo = document.getElementById(`${documentType}FileInfo`);
        const fileNameElement = document.getElementById(`${documentType}FileName`);

        if (statusElement) {
            statusElement.className = 'status-badge bg-green-100 text-green-800';
            statusElement.innerHTML = '<i class="fas fa-check mr-1"></i><span class="status-text">Telah Diunggah</span>';
        }

        if (fileInfo) {
            if (fileNameElement) {
                const displayName = data.file_name.length > 30
                    ? data.file_name.substring(0, 27) + '...'
                    : data.file_name;
                fileNameElement.textContent = displayName;
            }

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

            fileInfo.classList.remove('hidden');
        }
    }

    // Update progress dari response upload (TANPA REFRESH)
    function updateProgressFromUpload(data) {
        const progressText = document.getElementById('progressText');
        const progressBar = document.getElementById('overallProgressBar');
        const statusMessage = document.getElementById('statusMessage');

        if (progressText) {
            progressText.textContent = `${data.uploaded_count}/4 Dokumen`;
        }

        if (progressBar) {
            const percentage = (data.uploaded_count / 4) * 100;
            progressBar.style.width = `${percentage}%`;
        }

        if (statusMessage) {
            if (data.all_documents_complete) {
                statusMessage.innerHTML = `
                    <div class="inline-flex items-center space-x-2 bg-green-100 text-green-800 px-6 py-3 rounded-full">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span class="font-semibold">Semua dokumen telah lengkap! Anda bisa menyelesaikan pendaftaran.</span>
                    </div>
                `;
            } else {
                const remaining = 4 - data.uploaded_count;
                statusMessage.innerHTML = `
                    <div class="inline-flex items-center space-x-2 bg-orange-100 text-orange-800 px-6 py-3 rounded-full">
                        <i class="fas fa-info-circle text-xl"></i>
                        <span class="font-semibold">Lengkapi ${remaining} dokumen lagi untuk menyelesaikan pendaftaran.</span>
                    </div>
                `;
            }
        }

        // Update action buttons
        updateActionButtons(data.uploaded_count);
        updateHeaderButton(data.uploaded_count);
    }

    function updateAllDocumentUI() {
        documentTypes.forEach(type => {
            updateDocumentUI(type, uploadedDocuments[type]);
        });
    }

    function updateDocumentUI(documentType, isUploaded, fileName = null, filePath = null) {
        const statusElement = document.getElementById(`${documentType}Status`);
        const fileInfo = document.getElementById(`${documentType}FileInfo`);
        const fileNameElement = document.getElementById(`${documentType}FileName`);

        if (statusElement) {
            const statusText = statusElement.querySelector('.status-text');
            if (isUploaded) {
                statusElement.className = 'status-badge bg-green-100 text-green-800';
                statusElement.innerHTML = '<i class="fas fa-check mr-1"></i><span class="status-text">Telah Diunggah</span>';
            } else {
                statusElement.className = 'status-badge bg-red-100 text-red-800';
                statusElement.innerHTML = '<i class="fas fa-times mr-1"></i><span class="status-text">Belum Diunggah</span>';
            }
        }

        if (fileInfo) {
            if (isUploaded) {
                if (fileName && fileNameElement) {
                    const displayName = fileName.length > 30
                        ? fileName.substring(0, 27) + '...'
                        : fileName;
                    fileNameElement.textContent = displayName;
                }

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

                fileInfo.classList.remove('hidden');
            } else {
                fileInfo.classList.add('hidden');
                const existingActions = fileInfo.querySelector('.file-actions');
                if (existingActions) {
                    existingActions.remove();
                }
            }
        }
    }

    function updateProgress() {
        const uploadedCount = Object.values(uploadedDocuments).filter(Boolean).length;
        const percentage = (uploadedCount / 4) * 100;
        const remaining = 4 - uploadedCount;

        // Update progress text
        const progressText = document.getElementById('progressText');
        if (progressText) {
            progressText.textContent = `${uploadedCount}/4 Dokumen`;
        }

        // Update progress bar
        const progressBar = document.getElementById('overallProgressBar');
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
        }

        // Update status message
        const statusMessage = document.getElementById('statusMessage');
        if (statusMessage) {
            if (percentage === 100) {
                statusMessage.innerHTML = `
                    <div class="inline-flex items-center space-x-2 bg-green-100 text-green-800 px-6 py-3 rounded-full">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span class="font-semibold">Semua dokumen telah lengkap! Anda bisa menyelesaikan pendaftaran.</span>
                    </div>
                `;
            } else {
                statusMessage.innerHTML = `
                    <div class="inline-flex items-center space-x-2 bg-orange-100 text-orange-800 px-6 py-3 rounded-full">
                        <i class="fas fa-info-circle text-xl"></i>
                        <span class="font-semibold">Lengkapi ${remaining} dokumen lagi untuk menyelesaikan pendaftaran.</span>
                    </div>
                `;
            }
        }
    }

    // Update tombol action di section progress
    function updateActionButtons(uploadedCount = null) {
        if (uploadedCount === null) {
            uploadedCount = Object.values(uploadedDocuments).filter(Boolean).length;
        }
        const percentage = (uploadedCount / 4) * 100;
        const actionButtons = document.getElementById('dynamicActionButton');

        if (actionButtons) {
            if (percentage === 100) {
                actionButtons.innerHTML = `
                    <button onclick="confirmCompleteRegistration()" class="bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i> Selesaikan Pendaftaran
                    </button>
                `;
            } else {
                actionButtons.innerHTML = `
                    <button onclick="showCompletionWarning()" class="bg-gray-400 text-white px-6 py-3 rounded-full cursor-not-allowed flex items-center justify-center" disabled>
                        <i class="fas fa-lock mr-2"></i> Lengkapi Dokumen
                    </button>
                `;
            }
        }
    }

    // Update tombol di header
    function updateHeaderButton(uploadedCount = null) {
        if (uploadedCount === null) {
            uploadedCount = Object.values(uploadedDocuments).filter(Boolean).length;
        }
        const percentage = (uploadedCount / 4) * 100;
        const headerButtonContainer = document.getElementById('completeRegistrationButtonContainer');

        if (headerButtonContainer && percentage === 100) {
            headerButtonContainer.innerHTML = `
                <button onclick="confirmCompleteRegistration()" class="bg-green-600 text-white px-4 py-1.5 rounded-full hover:bg-green-700 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-check-circle mr-2"></i> Selesaikan Pendaftaran
                </button>
            `;
        } else if (headerButtonContainer) {
            headerButtonContainer.innerHTML = '';
        }
    }

    // Show success notification
    function showSuccessNotification(message) {
        const notification = document.getElementById('successNotification');
        const messageElement = document.getElementById('successMessage');

        if (notification && messageElement) {
            messageElement.textContent = message;
            notification.classList.remove('hidden');
            notification.classList.add('slide-down');

            // Auto hide setelah 5 detik
            setTimeout(() => {
                hideSuccessNotification();
            }, 5000);
        }
    }

    // Hide success notification
    function hideSuccessNotification() {
        const notification = document.getElementById('successNotification');
        if (notification) {
            notification.classList.add('hidden');
            notification.classList.remove('slide-down');
        }
    }

    // Fungsi untuk menampilkan notifikasi ketika semua dokumen lengkap (TANPA REFRESH)
    function showAllCompleteNotification() {
        // Hanya tampilkan sweet alert tanpa refresh
        Swal.fire({
            icon: 'success',
            title: 'Semua Dokumen Lengkap!',
            html: `
                <div class="text-left">
                    <p class="mb-3">Selamat! Anda telah berhasil mengunggah semua dokumen yang diperlukan.</p>
                    <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                        <p class="text-sm text-green-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Klik tombol <strong>"Selesaikan Pendaftaran"</strong> untuk melanjutkan proses verifikasi.
                        </p>
                    </div>
                </div>
            `,
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#10b981'
        });

        // Sembunyikan auto refresh notice jika ada
        const notice = document.getElementById('autoRefreshNotice');
        if (notice) {
            notice.classList.add('hidden');
        }
    }

    // Konfirmasi sebelum menyelesaikan pendaftaran
    window.confirmCompleteRegistration = function() {
        Swal.fire({
            title: 'Pastikan Data Sesuai',
            html: `
                <div class="text-left">
                    <p class="mb-4">Sebelum menyelesaikan pendaftaran, pastikan:</p>
                    <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                        <li>Semua dokumen telah diupload dengan benar</li>
                        <li>Data biodata sudah sesuai dan lengkap</li>
                        <li>File yang diupload jelas dan terbaca</li>
                        <li>Anda Masih Bisa Mengubah Data Selama Status Data Belum Diterima</li>
                    </ul>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Data Sudah Sesuai',
            cancelButtonText: 'Periksa Kembali',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                completeRegistration();
            }
        });
    };

    // Download document function
    window.downloadDocument = function(documentType) {
        Swal.fire({
            title: 'Mendownload...',
            text: 'Sedang mempersiapkan file',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/santri/documents/download/${documentType}`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Download failed');
            }
            return response.blob();
        })
        .then(blob => {
            Swal.close();

            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;

            const documentNames = {
                'kartu_keluarga': 'Kartu-Keluarga',
                'ijazah': 'Ijazah',
                'akta_kelahiran': 'Akta-Kelahiran',
                'pas_foto': 'Pas-Foto'
            };

            const baseName = documentNames[documentType] || documentType;
            const user = '{{ Auth::user()->name }}';
            const extension = blob.type.includes('pdf') ? 'pdf' :
                            blob.type.includes('jpeg') ? 'jpg' :
                            blob.type.includes('png') ? 'png' : 'file';

            a.download = `${baseName}_${user}.${extension}`;

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            Swal.fire({
                icon: 'success',
                title: 'Download Berhasil',
                text: 'File berhasil didownload',
                timer: 2000,
                showConfirmButton: false
            });
        })
        .catch(error => {
            Swal.close();

            Swal.fire({
                icon: 'error',
                title: 'Download Gagal',
                text: 'File tidak ditemukan atau belum diupload. Silakan upload file terlebih dahulu.',
                confirmButtonText: 'Mengerti'
            });
        });
    };

    // Delete document function
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
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        throw new Error('Server returned non-JSON response');
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Update state and UI
                        uploadedDocuments[documentType] = false;
                        saveDocumentState();
                        updateDocumentUI(documentType, false);

                        // Update progress dari response
                        updateProgressFromUpload(data);

                        checkQuotaForDeleteAll();

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
        const uploadedCount = Object.values(uploadedDocuments).filter(Boolean).length;
        const remaining = 4 - uploadedCount;

        Swal.fire({
            icon: 'warning',
            title: 'Dokumen Belum Lengkap',
            html: `Harap lengkapi <strong>${remaining} dokumen</strong> lagi sebelum menyelesaikan pendaftaran.<br><br>
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
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Server returned non-JSON response');
            }
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
    };
</script>
@endsection

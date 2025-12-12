@extends('layouts.app')

@section('title', 'Upload Dokumen - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    @include('layouts.components.calon_santri.navbar')

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
                    <div id="completeRegistrationButtonContainer"></div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
        <div id="autoRefreshNotice" class="hidden mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-center">
            <div class="flex items-center justify-center space-x-2">
                <i class="fas fa-sync-alt animate-spin"></i>
                <span class="font-semibold">Semua dokumen telah lengkap! Halaman akan direfresh otomatis dalam <span id="countdown">3</span> detik...</span>
            </div>
        </div>

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

        <div class="max-w-4xl mx-auto mb-8">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-gray-800">Progress Dokumen</h3>
                <span id="progressText" class="text-sm font-semibold text-gray-600">
                    {{ $uploadedCount }}/{{ $requiredCount }} dokumen
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div id="progressBar" class="bg-primary h-2.5 rounded-full transition-all" 
                     style="width: {{ $requiredCount > 0 ? ($uploadedCount / $requiredCount) * 100 : 0 }}%">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-8 w-full max-w-3xl mx-auto">
            @forelse($requiredDocuments as $docType)
                @php
                    $documentLabel = $documentLabels[$docType] ?? ucwords(str_replace('_', ' ', $docType));
                    $uploaded = isset($uploadedDocuments[$docType]);
                @endphp
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="h-2 bg-primary"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file text-primary-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $documentLabel }}</h3>
                                    <p class="text-xs text-gray-500">Format: PDF, JPG, PNG (Max 5MB)</p>
                                </div>
                            </div>
                            @if($uploaded)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check mr-1"></i>Selesai
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                    Belum
                                </span>
                            @endif
                        </div>

                    <div class="relative">
                        {{-- FIX: Input diletakkan DI LUAR div dropzone untuk menghindari conflict event --}}
                        <input type="file" 
                               class="file-input-{{ $docType }}" 
                               style="display: none;" 
                               accept=".pdf,.jpg,.jpeg,.png"
                               data-document-type="{{ $docType }}">

                        <div class="dropzone-{{ $docType }} dropzone rounded-lg border-2 border-dashed border-gray-300 p-6 text-center cursor-pointer hover:border-primary hover:bg-primary-50 transition"
                             data-document-type="{{ $docType }}"
                             style="pointer-events: auto;">
                            
                            @if($uploaded)
                                @php
                                    $uploadedDoc = $uploadedDocuments[$docType] ?? null;
                                @endphp
                                <div class="space-y-2" style="pointer-events: none; user-select: none;">
                                    <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                                    <p class="font-semibold text-green-600">Dokumen Tersimpan</p>
                                    @if($uploadedDoc)
                                        <p class="text-xs text-gray-600">{{ $uploadedDoc->file_path }}</p>
                                    @endif
                                </div>
                            @else
                                <div class="space-y-2" style="pointer-events: none; user-select: none;">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                    <p class="font-semibold text-gray-700">Drag & Drop atau Klik untuk Upload</p>
                                    <p class="text-xs text-gray-500">Pilih file dokumen dari komputer Anda</p>
                                </div>
                            @endif
                        </div>

                        <div class="upload-progress-{{ $docType }} hidden mt-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Uploading...</span>
                                <span class="upload-percentage-{{ $docType }} text-sm font-semibold text-gray-600">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="upload-bar-{{ $docType }} bg-primary h-2 rounded-full transition-all" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        @if($uploaded)
                            <button type="button" 
                                    onclick="downloadDocument('{{ $docType }}')"
                                    class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-semibold">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                            <button type="button" 
                                    onclick="deleteDocument('{{ $docType }}')"
                                    class="flex-1 px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-semibold">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        @else
                            <button type="button" 
                                    onclick="event.preventDefault(); selectFile('{{ $docType }}');"
                                    class="w-full px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition text-sm font-semibold">
                                <i class="fas fa-upload mr-1"></i>Pilih File
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i class="fas fa-info-circle text-yellow-600 text-3xl mb-2"></i>
                    <p class="text-yellow-800 font-semibold">Tidak Ada Dokumen yang Diperlukan</p>
                    <p class="text-yellow-700 text-sm mt-1">Silakan selesaikan biodata terlebih dahulu atau pilih program pendidikan</p>
                </div>
            @endforelse
        </div>

        {{-- Missing Documents Info --}}
        @if(count($missingDocuments) > 0)
            <div class="max-w-4xl mx-auto bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h4 class="font-bold text-yellow-900 mb-2">Dokumen yang Belum Diunggah:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($missingDocuments as $missing)
                        <span class="px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-sm">
                            {{ $missingDocumentLabels[$missing] ?? $missing }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

    </main>
    <div class="max-w-4xl mx-auto flex gap-3 mb-8">
        <a href="{{ route('santri.biodata.index') }}" 
           class="flex-1 px-4 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold text-center">
            Kembali ke Biodata
        </a>
        <button type="button" 
                id="downloadAllBtn"
                onclick="downloadAllDocuments()"
                class="flex-1 px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-semibold">
            <i class="fas fa-download mr-2"></i>Download Semua
        </button>
        <button type="button" 
                id="completeBtn"
                onclick="completeRegistration()"
                class="flex-1 px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-semibold"
                disabled>
            <i class="fas fa-check mr-2"></i>Selesaikan Pendaftaran
        </button>
    </div>

    @include('layouts.components.calon_santri.footer')
</div>

<script>
const requiredCount = {{ $requiredCount }};
const requiredDocuments = @json($requiredDocuments);
const uploadedDocuments = @json($uploadedDocuments ? $uploadedDocuments->keys()->toArray() : []);

// Base URLs for routes
const uploadUrl = '{{ url("/santri/documents/upload") }}';
const deleteUrl = '{{ url("/santri/documents/delete") }}';
const downloadUrl = '{{ url("/santri/documents/download") }}';

// Initialize dropzones
document.addEventListener('DOMContentLoaded', function() {
        requiredDocuments.forEach(docType => {
        // docType should be snake_case like 'ijazah', 'kartu_keluarga'
        // Use CSS.escape to safely escape any special characters in the selector
        const escapedDocType = CSS.escape(docType);
        
        const dropzone = document.querySelector(`.dropzone-${escapedDocType}`);
        const fileInput = document.querySelector(`.file-input-${escapedDocType}`);
        
        if (!dropzone || !fileInput) {
            console.warn(`Dropzone or file input not found for: ${docType}`);
            return;
        }

        // Drag and drop
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('border-primary', 'bg-primary-50');
        });

        dropzone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('border-primary', 'bg-primary-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('border-primary', 'bg-primary-50');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                uploadDocument(docType, files[0]);
            }
        });

        // Click to select
        dropzone.addEventListener('click', function(e) {
            // Skip jika klik pada child element yang bisa diklik (button, link)
            if (e.target.closest('button') || e.target.closest('a')) {
                return;
            }
            // Skip jika dokumen sudah diunggah (ada icon check)
            if (dropzone.querySelector('.fa-check-circle')) {
                return;
            }
            
            // Prevent default behaviour
            e.preventDefault();
            
            // Trigger file input directly (Safe now because input is outside)
            if (fileInput) {
                fileInput.click();
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files.length > 0) {
                uploadDocument(docType, e.target.files[0]);
            }
        });
    });

    updateCompleteButton();
    checkAllDocuments();
});

// Helper function untuk memilih file via tombol button
function selectFile(docType) {
    // Try multiple selector approaches
    let fileInput = null;
    
    // First try: CSS.escape with class selector
    if (typeof CSS !== 'undefined' && CSS.escape) {
        const escapedDocType = CSS.escape(docType);
        fileInput = document.querySelector(`input.file-input-${escapedDocType}`);
    }
    
    // Second try: without escaping
    if (!fileInput) {
        fileInput = document.querySelector(`input.file-input-${docType}`);
    }
    
    // Third try: with data attribute (most reliable)
    if (!fileInput) {
        fileInput = document.querySelector(`input[data-document-type="${docType}"]`);
    }
    
    if (fileInput) {
        fileInput.click();
    } else {
        console.error(`File input not found for: ${docType}`);
    }
}

function uploadDocument(docType, file) {
    const formData = new FormData();
    formData.append('file', file);

    const progressContainer = document.querySelector(`.upload-progress-${docType}`);
    const progressBar = document.querySelector(`.upload-bar-${docType}`);
    const progressPercentage = document.querySelector(`.upload-percentage-${docType}`);
    const dropzone = document.querySelector(`.dropzone-${docType}`);

    progressContainer.classList.remove('hidden');
    dropzone.classList.add('pointer-events-none', 'opacity-50');

    fetch(`${uploadUrl}/${docType}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        progressContainer.classList.add('hidden');
        dropzone.classList.remove('pointer-events-none', 'opacity-50');

        if (data.success) {
            showSuccessMessage(`${data.document_label} berhasil diunggah`);
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        progressContainer.classList.add('hidden');
        dropzone.classList.remove('pointer-events-none', 'opacity-50');
        console.error('Upload error:', error);
        alert('Upload error: ' + (error.message || 'Terjadi kesalahan saat mengunggah file'));
    });
}

function deleteDocument(docType) {
    if (!confirm('Yakin ingin menghapus dokumen ini?')) return;

    fetch(`${deleteUrl}/${docType}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage('Dokumen berhasil dihapus');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => alert('Delete error: ' + error.message));
}

function downloadDocument(docType) {
    window.location.href = `${downloadUrl}/${docType}`;
}

function downloadAllDocuments() {
    window.location.href = '{{ route('santri.documents.download-all') }}';
}

function completeRegistration() {
    if (!confirm('Yakin data dan dokumen sudah lengkap? Anda tidak bisa mengubah lagi setelahnya.')) return;

    fetch('{{ route('santri.documents.complete') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            setTimeout(() => {
                window.location.href = '{{ route('santri.dashboard') }}';
            }, 2000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => alert('Error: ' + error.message));
}

function checkAllDocuments() {
    fetch('{{ route('santri.documents.check-complete') }}')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateProgressBar(data.uploaded_count, data.required_count);
            updateCompleteButton();
        }
    });
}

function updateProgressBar(uploaded, required) {
    const percentage = required > 0 ? (uploaded / required) * 100 : 0;
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    progressBar.style.width = percentage + '%';
    progressText.textContent = `${uploaded}/${required} dokumen`;
}

function updateCompleteButton() {
    const completeBtn = document.getElementById('completeBtn');
    const allUploaded = uploadedDocuments.length === requiredCount && requiredCount > 0;
    completeBtn.disabled = !allUploaded;
}

function showSuccessMessage(message) {
    const notification = document.getElementById('successNotification');
    document.getElementById('successMessage').textContent = message;
    notification.classList.remove('hidden');
    
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 3000);
}

function hideSuccessNotification() {
    document.getElementById('successNotification').classList.add('hidden');
}

// Auto-check documents every 5 seconds
setInterval(checkAllDocuments, 5000);
</script>
@endsection
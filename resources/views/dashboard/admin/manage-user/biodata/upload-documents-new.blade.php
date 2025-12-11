@extends('layouts.app')

@section('title', 'Upload Dokumen - ' . $user->name)

@section('content')
<div class="min-h-screen bg-gray-50 font-sans w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between max-w-6xl mx-auto">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Upload Dokumen Registrasi</h1>
                <p class="text-secondary">Upload dokumen pendaftaran untuk {{ $user->name }}</p>
            </div>
            <a href="{{ route('admin.manage-users.biodata.show', $user) }}" 
               class="mt-4 md:mt-0 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition duration-200 flex items-center gap-2 justify-center md:justify-start">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto py-6 px-4">
        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-blue-900 mb-2">Panduan Upload Dokumen</h3>
                    <ul class="text-blue-800 text-sm space-y-1">
                        <li><i class="fas fa-check mr-2"></i>Format file: PDF, JPG, JPEG, PNG</li>
                        <li><i class="fas fa-check mr-2"></i>Ukuran file maksimal: 10 MB</li>
                        <li><i class="fas fa-check mr-2"></i>File yang diupload akan menggantikan file lama (jika ada)</li>
                        <li><i class="fas fa-check mr-2"></i>Dokumen diperlukan: Kartu Keluarga, Ijazah, Akta Kelahiran, Pas Foto</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Documents Grid -->
        @php
            $docTypes = [
                'kartu_keluaga_path' => [
                    'name' => 'Kartu Keluarga',
                    'description' => 'Fotokopi kartu keluarga yang jelas',
                    'icon' => 'fa-users',
                    'color' => 'blue'
                ],
                'ijazah_path' => [
                    'name' => 'Ijazah',
                    'description' => 'Fotokopi ijazah terakhir',
                    'icon' => 'fa-graduation-cap',
                    'color' => 'green'
                ],
                'akta_kelahiran_path' => [
                    'name' => 'Akta Kelahiran',
                    'description' => 'Fotokopi akta kelahiran asli',
                    'icon' => 'fa-birthday-cake',
                    'color' => 'purple'
                ],
                'pas_foto_path' => [
                    'name' => 'Pas Foto',
                    'description' => 'Pas foto terbaru ukuran 3x4',
                    'icon' => 'fa-camera',
                    'color' => 'orange'
                ]
            ];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            @foreach($docTypes as $fieldName => $docInfo)
                @php
                    $isUploaded = !empty($registration->{$fieldName});
                @endphp
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-{{ $docInfo['color'] }}-100 rounded-full flex items-center justify-center">
                            <i class="fas {{ $docInfo['icon'] }} text-{{ $docInfo['color'] }}-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-primary">{{ $docInfo['name'] }}</h3>
                            <p class="text-sm text-secondary">{{ $docInfo['description'] }}</p>
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition duration-200 mb-4" 
                         onclick="document.getElementById('{{ $fieldName }}File').click()">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-700 font-medium mb-1">Klik atau seret file ke sini</p>
                        <p class="text-sm text-gray-500">Format: PDF, JPG, PNG (Max 10MB)</p>
                        <input type="file" 
                               id="{{ $fieldName }}File"
                               data-document-type="{{ $fieldName }}"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="hidden document-input"
                               onchange="handleFileSelect(event, '{{ $fieldName }}')">
                    </div>

                    <!-- File Preview -->
                    @if($isUploaded)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                                    <div>
                                        <p class="font-medium text-gray-900">File Sudah Diupload</p>
                                        <p class="text-sm text-gray-600">Klik area upload untuk mengganti file</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.manage-users.biodata.download-document', [$user, $fieldName]) }}" 
                               class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 py-2 px-3 rounded-lg text-center font-medium transition duration-200">
                                <i class="fas fa-download mr-2"></i>Download
                            </a>
                            <button type="button"
                                    onclick="deleteDocument('{{ $fieldName }}')"
                                    class="flex-1 bg-red-100 hover:bg-red-200 text-red-800 py-2 px-3 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </div>
                    @endif

                    <!-- Progress Bar -->
                    <div id="{{ $fieldName }}Progress" class="hidden mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Sedang Upload...</span>
                            <span id="{{ $fieldName }}ProgressPercent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="{{ $fieldName }}ProgressBar" class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 pb-4 border-b border-gray-200">
                <i class="fas fa-list-check text-primary mr-2"></i>
                Ringkasan Status Dokumen
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @php
                    $uploadedCount = 0;
                    if ($registration->kartu_keluaga_path) $uploadedCount++;
                    if ($registration->ijazah_path) $uploadedCount++;
                    if ($registration->akta_kelahiran_path) $uploadedCount++;
                    if ($registration->pas_foto_path) $uploadedCount++;
                @endphp
                
                @foreach(['kartu_keluaga_path' => 'Kartu Keluarga', 'ijazah_path' => 'Ijazah', 'akta_kelahiran_path' => 'Akta Kelahiran', 'pas_foto_path' => 'Pas Foto'] as $field => $label)
                    <div class="text-center p-4 rounded-lg {{ !empty($registration->{$field}) ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                        <i class="fas {{ !empty($registration->{$field}) ? 'fa-check-circle text-green-600' : 'fa-times-circle text-gray-400' }} text-2xl mb-2"></i>
                        <p class="font-medium text-gray-800 text-sm">{{ $label }}</p>
                        <p class="text-xs mt-1 {{ !empty($registration->{$field}) ? 'text-green-600' : 'text-gray-600' }}">
                            {{ !empty($registration->{$field}) ? 'Uploaded' : 'Belum Upload' }}
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Progress Overview -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Kelengkapan Dokumen</span>
                    <span><strong id="overallProgress">{{ $uploadedCount }}</strong>/4 Dokumen</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div id="overallProgressBar" class="bg-primary h-3 rounded-full transition-all duration-300" style="width: {{ ($uploadedCount / 4) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.components.admin.footer')
</div>

<script>
    function handleFileSelect(event, documentType) {
        const file = event.target.files[0];
        if (!file) return;

        // Validasi tipe file
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan PDF, JPG, atau PNG');
            event.target.value = '';
            return;
        }

        // Validasi ukuran
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB');
            event.target.value = '';
            return;
        }

        uploadDocument(file, documentType);
    }

    async function uploadDocument(file, documentType) {
        const formData = new FormData();
        formData.append('document_type', documentType);
        formData.append('file', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');

        const progressDiv = document.getElementById(`${documentType}Progress`);
        const progressBar = document.getElementById(`${documentType}ProgressBar`);
        const progressPercent = document.getElementById(`${documentType}ProgressPercent`);

        progressDiv.classList.remove('hidden');

        // Simulasi progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
            progressPercent.textContent = Math.round(progress) + '%';
        }, 200);

        try {
            const response = await fetch('{{ route("admin.manage-users.biodata.upload-document", $user) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            progressPercent.textContent = '100%';

            const data = await response.json();

            if (data.success) {
                setTimeout(() => {
                    progressDiv.classList.add('hidden');
                    showSuccess(`${file.name} berhasil diupload`);
                    setTimeout(() => location.reload(), 1000);
                }, 500);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            clearInterval(progressInterval);
            progressDiv.classList.add('hidden');
            alert('Upload gagal: ' + error.message);
            document.getElementById(`${documentType}File`).value = '';
        }
    }

    function deleteDocument(documentType) {
        if (!confirm('Yakin ingin menghapus dokumen ini?')) return;

        fetch(`{{ route("admin.manage-users.biodata.delete-document", [$user, "DOCTYPE"]) }}`.replace('DOCTYPE', documentType), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Dokumen berhasil dihapus');
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('Gagal menghapus: ' + data.message);
            }
        })
        .catch(error => alert('Error: ' + error.message));
    }

    function showSuccess(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg bg-green-500 text-white flex items-center gap-2';
        notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
</script>

<style>
    .upload-area {
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.05);
    }
</style>
@endsection

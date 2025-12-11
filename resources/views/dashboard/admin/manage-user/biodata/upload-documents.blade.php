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

        <!-- Upload Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                <i class="fas fa-cloud-upload-alt text-primary mr-2"></i>
                Upload Dokumen Baru
            </h2>

            <form id="uploadForm" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <!-- Tipe Dokumen -->
                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-folder text-primary mr-2"></i>
                        Tipe Dokumen <span class="text-red-500">*</span>
                    </label>
                    <select id="document_type"
                            name="document_type"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200">
                        <option value="">Pilih Tipe Dokumen</option>
                        @foreach($documentTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih kategori dokumen yang akan di-upload</p>
                </div>

                <!-- File Input -->
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file text-primary mr-2"></i>
                        File Dokumen <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="relative">
                        <div id="dropZone" 
                             class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition duration-200">
                            <input type="file"
                                   id="file"
                                   name="file"
                                   required
                                   class="hidden"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-700 font-medium mb-1">
                                    Drag & Drop file di sini atau
                                    <span class="text-primary font-semibold">pilih file</span>
                                </p>
                                <p class="text-sm text-gray-500">
                                    Format: PDF, JPG, PNG, DOC, DOCX (Max 10MB)
                                </p>
                            </div>
                        </div>
                        
                        <!-- File Preview -->
                        <div id="filePreview" class="hidden mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file text-primary text-2xl"></i>
                                    <div>
                                        <p id="fileName" class="font-medium text-gray-900"></p>
                                        <p id="fileSize" class="text-sm text-gray-500"></p>
                                    </div>
                                </div>
                                <button type="button"
                                        onclick="clearFileInput()"
                                        class="text-red-600 hover:text-red-800 p-2">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment text-primary mr-2"></i>
                        Deskripsi (Opsional)
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="3"
                              placeholder="Masukkan deskripsi atau catatan tentang dokumen ini..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200 resize-none"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Catatan tambahan untuk dokumen ini</p>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                            id="submitBtn"
                            class="flex-1 bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white py-3 px-6 rounded-lg transition duration-200 font-medium shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i>
                        Upload Dokumen
                    </button>
                    <a href="{{ route('admin.manage-users.biodata.show', $user) }}"
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 px-6 rounded-lg transition duration-200 font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Dokumen yang Sudah Ada -->
        @if($documents->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mt-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                <i class="fas fa-list text-primary mr-2"></i>
                Dokumen yang Sudah Ada
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Nama File</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Tipe</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Ukuran</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Tanggal Upload</th>
                            <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-200">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file text-primary"></i>
                                    <span class="font-medium text-gray-900">{{ $document->file_name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="capitalize text-sm text-gray-600">
                                    {{ str_replace('_', ' ', $document->document_type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-sm text-gray-600">
                                    {{ formatBytes($document->file_size) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $document->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $document->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ $document->created_at->translatedFormat('d F Y H:i') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.manage-users.biodata.download-document', [$user, $document]) }}"
                                       class="text-blue-600 hover:text-blue-800 p-2"
                                       title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('admin.manage-users.biodata.delete-document', [$user, $document]) }}"
                                          method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 p-2"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </main>

    @include('layouts.components.admin.footer')
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file');
    const filePreview = document.getElementById('filePreview');
    const uploadForm = document.getElementById('uploadForm');
    const submitBtn = document.getElementById('submitBtn');

    // File input change event
    fileInput.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            displayFileInfo(file);
        }
    });

    // Drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-primary', 'bg-primary/5');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-primary', 'bg-primary/5');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        if (files.length > 0) {
            displayFileInfo(files[0]);
        }
    }, false);

    // Click to select file
    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    function displayFileInfo(file) {
        const validMimes = ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 
                          'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        if (!validMimes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan PDF, JPG, PNG, DOC, atau DOCX');
            fileInput.value = '';
            filePreview.classList.add('hidden');
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB');
            fileInput.value = '';
            filePreview.classList.add('hidden');
            return;
        }

        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatBytes(file.size);
        filePreview.classList.remove('hidden');
    }

    function clearFileInput() {
        fileInput.value = '';
        filePreview.classList.add('hidden');
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    // Form submission
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!fileInput.files.length) {
            alert('Pilih file terlebih dahulu');
            return;
        }

        if (!document.getElementById('document_type').value) {
            alert('Pilih tipe dokumen terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('document_type', document.getElementById('document_type').value);
        formData.append('file', fileInput.files[0]);
        formData.append('description', document.getElementById('description').value);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sedang Upload...';

        try {
            const response = await fetch('{{ route("admin.manage-users.biodata.upload-document", $user) }}', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast('Dokumen berhasil di-upload', 'success');
                uploadForm.reset();
                filePreview.classList.add('hidden');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast('Upload gagal: ' + (data.message || 'Kesalahan tidak diketahui'), 'error');
            }
        } catch (error) {
            showToast('Terjadi kesalahan: ' + error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Dokumen';
        }
    });

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        }, 10);

        setTimeout(() => {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
</script>

<style>
    input[type="file"] {
        display: none;
    }

    #dropZone {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Kelola Konten - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .file-upload-container {
        border: 2px dashed #e2e8f0;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        transition: border-color 0.3s;
    }
    .file-upload-container:hover {
        border-color: #057572;
    }
    .file-preview {
        max-width: 200px;
        max-height: 200px;
        margin: 0 auto;
    }
    .current-file {
        background-color: #f7fafc;
        border-radius: 0.375rem;
        padding: 0.75rem;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-primary mb-2">Kelola Konten Website</h1>
            <p class="text-gray-600">Kelola semua konten yang ditampilkan di halaman utama website</p>
        </div>

        <form action="{{ route('admin.content.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Utama -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-primary mb-4">Informasi Utama</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Utama</label>
                            <input type="text" name="judul" value="{{ old('judul', $settings->judul) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tagline</label>
                            <input type="text" name="tagline" value="{{ old('tagline', $settings->tagline) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="deskripsi" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('deskripsi', $settings->deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Visi dan Misi -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-primary mb-4">Visi & Misi</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Visi</label>
                            <input type="text" name="visi_judul" value="{{ old('visi_judul', $settings->visi_judul) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Visi</label>
                            <textarea name="visi_deskripsi" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('visi_deskripsi', $settings->visi_deskripsi) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Misi</label>
                            <input type="text" name="misi_judul" value="{{ old('misi_judul', $settings->misi_judul) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Misi</label>
                            <textarea name="misi_deskripsi" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('misi_deskripsi', $settings->misi_deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>

               <!-- Program Unggulan -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-primary mb-4">Program Unggulan</h2>

                    <div class="space-y-6" id="program-unggulan-container">
                        @foreach($settings->program_unggulan as $index => $program)
                        <div class="program-item border border-gray-200 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Program</label>
                                    <input type="text" name="program_unggulan[{{ $index }}][nama]"
                                        value="{{ old("program_unggulan.{$index}.nama", $program['nama'] ?? '') }}"
                                        placeholder="Contoh: Tahfidzul Qur'an"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                                    <input type="text" name="program_unggulan[{{ $index }}][target]"
                                        value="{{ old("program_unggulan.{$index}.target", $program['target'] ?? '') }}"
                                        placeholder="Contoh: Hafal 30 Juz dalam 3-5 tahun"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembelajaran</label>
                                <textarea name="program_unggulan[{{ $index }}][metode]" rows="2"
                                        placeholder="Contoh: Talaqqi dan murajaah harian bersama musyrif/musyrifah"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old("program_unggulan.{$index}.metode", $program['metode'] ?? '') }}</textarea>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sistem Evaluasi</label>
                                <textarea name="program_unggulan[{{ $index }}][evaluasi]" rows="2"
                                        placeholder="Contoh: Setoran harian, tasmi mingguan, dan ujian tahunan"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old("program_unggulan.{$index}.evaluasi", $program['evaluasi'] ?? '') }}</textarea>
                            </div>

                            @if($index > 0)
                            <div class="mt-4 flex justify-end">
                                <button type="button" onclick="removeProgram(this)"
                                        class="text-red-600 hover:text-red-800 text-sm flex items-center">
                                    <i class="fas fa-trash mr-1"></i> Hapus Program
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button type="button" onclick="addProgram()"
                                class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center">
                            <i class="fas fa-plus mr-2"></i> Tambah Program
                        </button>
                    </div>
                </div>

                <!-- Alur Pendaftaran -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-primary mb-4">Alur Pendaftaran</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Alur Pendaftaran</label>
                            <input type="text" name="alur_pendaftaran_judul" value="{{ old('alur_pendaftaran_judul', $settings->alur_pendaftaran_judul) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Alur Pendaftaran</label>
                            <textarea name="alur_pendaftaran_deskripsi" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('alur_pendaftaran_deskripsi', $settings->alur_pendaftaran_deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Persyaratan Dokumen -->
                <div class="bg-white rounded-xl shadow-md p-6 lg:col-span-2">
                    <h2 class="text-xl font-semibold text-primary mb-4">Persyaratan Dokumen</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Persyaratan Dokumen</label>
                            <input type="text" name="persyaratan_dokumen_judul" value="{{ old('persyaratan_dokumen_judul', $settings->persyaratan_dokumen_judul) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Persyaratan Dokumen</label>
                            <textarea name="persyaratan_dokumen_deskripsi" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('persyaratan_dokumen_deskripsi', $settings->persyaratan_dokumen_deskripsi) }}</textarea>
                        </div>

                        <!-- File Uploads -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                            @foreach(['akte', 'formulir', 'ijazah', 'kk', 'pasfoto'] as $fileType)
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-2 capitalize">{{ str_replace('_', ' ', $fileType) }}</label>

                                <div class="mb-3">
                                    @if($settings->getFilePath($fileType))
                                    <img src="{{ asset($settings->getFilePath($fileType)) }}"
                                         alt="{{ $fileType }}"
                                         class="file-preview rounded-lg shadow-sm">
                                    <div class="current-file text-xs text-gray-600 mt-2">
                                        File saat ini: {{ basename($settings->getFilePath($fileType)) }}
                                    </div>
                                    @endif
                                </div>

                                <input type="file" name="{{ $fileType }}_file"
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-secondary">

                                @if($settings->{$fileType . '_path'})
                                <button type="button"
                                        onclick="deleteFile('{{ $fileType }}')"
                                        class="mt-2 text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash mr-1"></i> Hapus File
                                </button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-primary hover:bg-secondary text-white font-semibold py-3 px-8 rounded-lg transition duration-300 flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form untuk delete file -->
<form id="deleteFileForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
function deleteFile(fileType) {
    Swal.fire({
        title: 'Hapus File?',
        text: "File yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteFileForm');
            // Perbaikan: Gunakan template literal untuk membuat URL yang benar
            form.action = `/admin/content/file/${fileType}`;
            form.submit();
        }
    });
}



// Program Unggulan Dynamic Form
let programIndex = {{ count($settings->program_unggulan) }};

function addProgram() {
    const container = document.getElementById('program-unggulan-container');
    const newProgram = document.createElement('div');
    newProgram.className = 'program-item border border-gray-200 rounded-lg p-4';
    newProgram.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Program</label>
                <input type="text" name="program_unggulan[${programIndex}][nama]"
                       placeholder="Contoh: Tahfidzul Qur'an"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                <input type="text" name="program_unggulan[${programIndex}][target]"
                       placeholder="Contoh: Hafal 30 Juz dalam 3-5 tahun"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembelajaran</label>
            <textarea name="program_unggulan[${programIndex}][metode]" rows="2"
                      placeholder="Contoh: Talaqqi dan murajaah harian bersama musyrif/musyrifah"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Sistem Evaluasi</label>
            <textarea name="program_unggulan[${programIndex}][evaluasi]" rows="2"
                      placeholder="Contoh: Setoran harian, tasmi mingguan, dan ujian tahunan"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="button" onclick="removeProgram(this)"
                    class="text-red-600 hover:text-red-800 text-sm flex items-center">
                <i class="fas fa-trash mr-1"></i> Hapus Program
            </button>
        </div>
    `;
    container.appendChild(newProgram);
    programIndex++;
}

function removeProgram(button) {
    const programItem = button.closest('.program-item');
    programItem.remove();
}


// Preview image before upload
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const container = this.closest('.file-upload-container');
        const preview = container.querySelector('.file-preview');

        if (file && preview) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection

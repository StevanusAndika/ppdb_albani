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
    .program-item, .faq-item {
        transition: all 0.3s ease;
    }
    .program-item:hover, .faq-item:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left column: Layout switch + Actions -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-lg font-semibold text-primary mb-4">Tampilan (Layout)</h2>
                        <div class="flex gap-3">
                            <button type="button" id="layout-2col-btn"
                                    onclick="toggleLayout('two')"
                                    class="flex-1 bg-primary text-white py-2 px-3 rounded-lg hover:opacity-90">
                                2 Kolom
                            </button>
                            <button type="button" id="layout-1col-btn"
                                    onclick="toggleLayout('one')"
                                    class="flex-1 bg-gray-200 text-gray-700 py-2 px-3 rounded-lg hover:opacity-90">
                                1 Kolom
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Pilih layout untuk melihat preview pengaturan form menjadi satu atau dua kolom.</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-lg font-semibold text-primary mb-4">Aksi</h2>
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="block text-center border border-primary text-primary py-2 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>

                            <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-semibold py-2 rounded-lg flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>

                            <!-- Duplicate save for mobile bottom convenience -->
                            <div class="hidden md:block text-xs text-gray-500">
                                Disimpan: perubahan akan diterapkan di halaman utama.
                            </div>
                        </div>
                    </div>

                    <!-- Small help / info card -->
                    <div class="bg-white rounded-xl shadow-md p-4 text-sm text-gray-600">
                        Tips: Gunakan tombol "Tambah" pada setiap bagian untuk menambah item dinamis (Program, FAQ, Kegiatan).
                    </div>
                </div>

                <!-- Right column: Form content (will toggle between 1 or 2 column layout) -->
                <div class="lg:col-span-2">
                    <div id="form-content" class="grid grid-cols-1 gap-6">
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

                        <!-- Visi & Misi -->
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

                        <!-- FAQ Section -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h2 class="text-xl font-semibold text-primary mb-4">FAQ (Frequently Asked Questions)</h2>

                            <div class="space-y-6" id="faq-container">
                                @foreach($settings->faq as $index => $faq)
                                <div class="faq-item border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-4">
                                        <h3 class="text-lg font-medium text-gray-800">Pertanyaan #{{ $index + 1 }}</h3>
                                        @if($index > 0)
                                        <button type="button" onclick="removeFaq(this)"
                                                class="text-red-600 hover:text-red-800 text-sm flex items-center">
                                            <i class="fas fa-trash mr-1"></i> Hapus FAQ
                                        </button>
                                        @endif
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan</label>
                                            <input type="text" name="faq[{{ $index }}][pertanyaan]"
                                                   value="{{ old("faq.{$index}.pertanyaan", $faq['pertanyaan'] ?? '') }}"
                                                   placeholder="Contoh: Apa saja persyaratan pendaftaran?"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Jawaban</label>
                                            <textarea name="faq[{{ $index }}][jawaban]" rows="3"
                                                      placeholder="Contoh: Persyaratan pendaftaran meliputi..."
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old("faq.{$index}.jawaban", $faq['jawaban'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <button type="button" onclick="addFaq()"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Tambah FAQ
                                </button>
                            </div>
                        </div>

                        <!-- Persyaratan Dokumen dan Kegiatan Pesantren (gabungan) -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h2 class="text-xl font-semibold text-primary mb-4">Persyaratan Dokumen & Kegiatan</h2>

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

                                <div class="bg-white rounded-lg p-4 border border-gray-100">
                                    <h3 class="font-medium text-primary mb-3">Kegiatan Pesantren</h3>
                                    <div id="kegiatan-pesantren-container" class="space-y-4">
                                        @foreach($settings->kegiatan_pesantren as $index => $kegiatan)
                                        <div class="kegiatan-item border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-start justify-between mb-4">
                                                <h3 class="text-lg font-medium text-gray-800">Sesi {{ $index + 1 }}</h3>
                                                @if($index > 0)
                                                <button type="button" onclick="removeKegiatan(this)"
                                                        class="text-red-600 hover:text-red-800 text-sm flex items-center">
                                                    <i class="fas fa-trash mr-1"></i> Hapus Kegiatan
                                                </button>
                                                @endif
                                            </div>

                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                                                    <input type="text" name="kegiatan_pesantren[{{ $index }}][waktu]"
                                                        value="{{ old("kegiatan_pesantren.{$index}.waktu", $kegiatan['waktu'] ?? '') }}"
                                                        placeholder="Contoh: 04:00 - Bada Shubuh"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kegiatan</label>
                                                    <div class="space-y-2" id="kegiatan-list-{{ $index }}">
                                                        @foreach($kegiatan['kegiatan'] as $kIndex => $kItem)
                                                        <div class="flex gap-2 kegiatan-item-input">
                                                            <input type="text"
                                                                name="kegiatan_pesantren[{{ $index }}][kegiatan][]"
                                                                value="{{ $kItem }}"
                                                                placeholder="Contoh: Shalat subuh berjamaan"
                                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                                            @if($kIndex > 0)
                                                            <button type="button" onclick="removeKegiatanItem(this)"
                                                                    class="text-red-600 hover:text-red-800 px-3 py-2">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            @endif
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" onclick="addKegiatanItem({{ $index }})"
                                                            class="mt-2 text-primary hover:text-secondary text-sm flex items-center">
                                                        <i class="fas fa-plus mr-1"></i> Tambah Kegiatan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-4">
                                        <button type="button" onclick="addKegiatan()"
                                                class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center">
                                            <i class="fas fa-plus mr-2"></i> Tambah Sesi Kegiatan
                                        </button>
                                    </div>
                                </div>

                                <!-- File Uploads -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-2">
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

                    <!-- Mobile bottom action (visible on small screens) -->
                    <div class="mt-6 block md:hidden">
                        <div class="flex gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center border border-primary text-primary py-2 rounded-lg">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit" class="flex-1 bg-primary text-white py-2 rounded-lg">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Toggle between one or two column preview for form-content
        function toggleLayout(mode) {
            const content = document.getElementById('form-content');

            if (!content) return;

            if (mode === 'two') {
                // make children layout two-column where appropriate
                content.classList.remove('grid-cols-1');
                content.classList.add('grid-cols-2');
                // highlight buttons
                document.getElementById('layout-2col-btn').classList.add('bg-primary', 'text-white');
                document.getElementById('layout-2col-btn').classList.remove('bg-gray-200', 'text-gray-700');
                document.getElementById('layout-1col-btn').classList.remove('bg-primary', 'text-white');
                document.getElementById('layout-1col-btn').classList.add('bg-gray-200', 'text-gray-700');
            } else {
                content.classList.remove('grid-cols-2');
                content.classList.add('grid-cols-1');
                document.getElementById('layout-1col-btn').classList.add('bg-primary', 'text-white');
                document.getElementById('layout-1col-btn').classList.remove('bg-gray-200', 'text-gray-700');
                document.getElementById('layout-2col-btn').classList.remove('bg-primary', 'text-white');
                document.getElementById('layout-2col-btn').classList.add('bg-gray-200', 'text-gray-700');
            }
        }

        // Initialize default layout (two columns preview)
        document.addEventListener('DOMContentLoaded', function() {
            // default to two-column preview for wider displays
            if (window.innerWidth >= 1024) {
                toggleLayout('two');
            } else {
                toggleLayout('one');
            }
        });
    </script>
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

// FAQ Dynamic Form
let faqIndex = {{ count($settings->faq) }};

function addFaq() {
    const container = document.getElementById('faq-container');
    const newFaq = document.createElement('div');
    newFaq.className = 'faq-item border border-gray-200 rounded-lg p-4';
    newFaq.innerHTML = `
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-800">Pertanyaan #${faqIndex + 1}</h3>
            <button type="button" onclick="removeFaq(this)"
                    class="text-red-600 hover:text-red-800 text-sm flex items-center">
                <i class="fas fa-trash mr-1"></i> Hapus FAQ
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan</label>
                <input type="text" name="faq[${faqIndex}][pertanyaan]"
                       placeholder="Contoh: Apa saja persyaratan pendaftaran?"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jawaban</label>
                <textarea name="faq[${faqIndex}][jawaban]" rows="3"
                          placeholder="Contoh: Persyaratan pendaftaran meliputi..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
            </div>
        </div>
    `;
    container.appendChild(newFaq);
    faqIndex++;
}

function removeFaq(button) {
    const faqItem = button.closest('.faq-item');
    faqItem.remove();

    // Update numbering for remaining FAQs
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach((item, index) => {
        const title = item.querySelector('h3');
        if (title) {
            title.textContent = `Pertanyaan #${index + 1}`;
        }
    });

    // Update faqIndex to prevent gaps
    faqIndex = faqItems.length;
}

// Kegiatan Dynamic Form
let kegiatanIndex = {{ count($settings->kegiatan_pesantren) }};

function addKegiatan() {
    const container = document.getElementById('kegiatan-pesantren-container');
    const newKegiatan = document.createElement('div');
    newKegiatan.className = 'kegiatan-item border border-gray-200 rounded-lg p-4';
    newKegiatan.innerHTML = `
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-800">Sesi ${kegiatanIndex + 1}</h3>
            <button type="button" onclick="removeKegiatan(this)"
                    class="text-red-600 hover:text-red-800 text-sm flex items-center">
                <i class="fas fa-trash mr-1"></i> Hapus Kegiatan
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                <input type="text" name="kegiatan_pesantren[${kegiatanIndex}][waktu]"
                       placeholder="Contoh: 04:00 - Bada Shubuh"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kegiatan</label>
                <div class="space-y-2" id="kegiatan-list-${kegiatanIndex}">
                    <div class="flex gap-2 kegiatan-item-input">
                        <input type="text"
                               name="kegiatan_pesantren[${kegiatanIndex}][kegiatan][]"
                               placeholder="Contoh: Shalat subuh berjamaan"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
                <button type="button" onclick="addKegiatanItem(${kegiatanIndex})"
                        class="mt-2 text-primary hover:text-secondary text-sm flex items-center">
                    <i class="fas fa-plus mr-1"></i> Tambah Kegiatan
                </button>
            </div>
        </div>
    `;
    container.appendChild(newKegiatan);
    kegiatanIndex++;
}

function removeKegiatan(button) {
    const kegiatanItem = button.closest('.kegiatan-item');
    kegiatanItem.remove();
}

function addKegiatanItem(index) {
    const container = document.getElementById(`kegiatan-list-${index}`);
    const newItem = document.createElement('div');
    newItem.className = 'flex gap-2 kegiatan-item-input';
    newItem.innerHTML = `
        <input type="text"
               name="kegiatan_pesantren[${index}][kegiatan][]"
               placeholder="Contoh: Shalat subuh berjamaan"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
        <button type="button" onclick="removeKegiatanItem(this)"
                class="text-red-600 hover:text-red-800 px-3 py-2">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(newItem);
}

function removeKegiatanItem(button) {
    const item = button.closest('.kegiatan-item-input');
    item.remove();
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

// Success/Error Messages
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan!',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        timer: 5000,
        showConfirmButton: true
    });
@endif
</script>
@endsection

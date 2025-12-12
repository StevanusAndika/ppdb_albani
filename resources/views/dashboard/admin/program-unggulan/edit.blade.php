@extends('layouts.app')

@section('title', 'Edit Program Unggulan - Admin')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    @include('layouts.components.admin.navbar')

    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Edit Program Unggulan</h1>
        <p class="text-secondary">Ubah informasi program unggulan</p>
    </header>

    <main class="max-w-2xl mx-auto py-6 px-4 flex-1">
        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                <h4 class="font-bold mb-2">Validasi Gagal</h4>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('admin.program-unggulan.update', $program) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Program --}}
                <div>
                    <label for="nama_program" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Program <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           id="nama_program" 
                           name="nama_program" 
                           value="{{ old('nama_program', $program->nama_program) }}"
                           placeholder="Contoh: Program Tahfidz, Program Intensif, dll"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('nama_program') border-red-500 @enderror"
                           required>
                    @error('nama_program')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Potongan (Diskon) --}}
                <div>
                    <label for="potongan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Diskon / Potongan (%) <span class="text-red-600">*</span>
                    </label>
                    <div class="flex items-center">
                        <input type="number" 
                               id="potongan" 
                               name="potongan" 
                               value="{{ old('potongan', $program->potongan) }}"
                               min="0" 
                               max="100" 
                               step="0.01"
                               placeholder="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('potongan') border-red-500 @enderror"
                               required>
                        <span class="ml-3 text-gray-600">%</span>
                    </div>
                    @error('potongan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Perlu Verifikasi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Perlu Verifikasi Khusus? <span class="text-red-600">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="perlu_verifikasi" 
                                   value="yes"
                                   {{ old('perlu_verifikasi', $program->perlu_verifikasi) === 'yes' ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary">
                            <span class="ml-3 text-gray-700">Ya, program ini memerlukan verifikasi khusus</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="perlu_verifikasi" 
                                   value="no"
                                   {{ old('perlu_verifikasi', $program->perlu_verifikasi) === 'no' ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary">
                            <span class="ml-3 text-gray-700">Tidak perlu verifikasi khusus</span>
                        </label>
                    </div>
                    @error('perlu_verifikasi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dokumen Tambahan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Dokumen Tambahan (Opsional)
                    </label>
                    <div id="dokumentasiContainer" class="space-y-2 mb-3">
                        <!-- Dynamic rows will be added here -->
                    </div>
                    <button type="button" 
                            onclick="addDocumentRow()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Dokumen
                    </button>
                    <p class="text-gray-500 text-sm mt-2">
                        Contoh: SKU, Sertifikat Hafiz, Surat Rekomendasi, Dokumen Kesehatan
                    </p>
                </div>

                {{-- Daftar Dokumen Standar --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">Dokumen Standar yang Sudah Ada:</h4>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-sm">Kartu Keluarga</span>
                        <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-sm">Ijazah</span>
                        <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-sm">Akta Kelahiran</span>
                        <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-sm">Pas Foto</span>
                    </div>
                </div>

                {{-- Hidden input untuk dokumen_tambahan JSON --}}
                <input type="hidden" id="dokumen_tambahan" name="dokumen_tambahan" value="[]">

                {{-- Tombol Action --}}
                <div class="flex gap-3 pt-6 border-t">
                    <a href="{{ route('admin.program-unggulan.index') }}" 
                       class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition text-center">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('layouts.components.footer')
</div>

<script>
let documentRowCount = 0;

function addDocumentRow(docValue = '') {
    const container = document.getElementById('dokumentasiContainer');
    const rowId = documentRowCount++;
    
    const row = document.createElement('div');
    row.id = `doc-row-${rowId}`;
    row.className = 'flex gap-2 items-end';
    row.innerHTML = `
        <input type="text" 
               value="${docValue}"
               placeholder="Nama dokumen (contoh: SKU, Sertifikat Hafiz)"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
               data-doc-index="${rowId}">
        <button type="button" 
                onclick="removeDocumentRow(${rowId})"
                class="px-3 py-2 bg-red-200 text-red-700 rounded-lg hover:bg-red-300">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    container.appendChild(row);
}

function removeDocumentRow(rowId) {
    const row = document.getElementById(`doc-row-${rowId}`);
    if (row) {
        row.remove();
    }
}

function collectDocuments() {
    const rows = document.querySelectorAll('#dokumentasiContainer input[data-doc-index]');
    const documents = [];
    
    rows.forEach(input => {
        const value = input.value.trim();
        if (value) {
            documents.push(value);
        }
    });
    
    document.getElementById('dokumen_tambahan').value = JSON.stringify(documents);
}

// Collect documents before form submission
document.querySelector('form').addEventListener('submit', function(e) {
    collectDocuments();
});

// Load existing data on page load
document.addEventListener('DOMContentLoaded', function() {
    const existingDokumen = @json($program->dokumen_tambahan ?? []);
    
    if (Array.isArray(existingDokumen) && existingDokumen.length > 0) {
        existingDokumen.forEach(doc => {
            if (doc.trim()) {
                addDocumentRow(doc);
            }
        });
    }
});
</script>
@endsection

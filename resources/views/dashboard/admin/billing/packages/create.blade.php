@extends('layouts.app')

@section('title', 'Tambah Paket Billing - Pondok Pesantren Bani Syahid')

@section('content')
<div class="flex flex-col min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-primary">Tambah Paket Billing</h1>
                    <p class="text-secondary mt-2">Buat paket pembayaran baru</p>
                </div>
                <a href="{{ route('admin.billing.packages.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('admin.billing.packages.store') }}" method="POST" id="packageForm">
                @csrf

                <div class="space-y-6">
                    <!-- Nama Paket -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Paket *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                            placeholder="Masukkan nama paket" required>
                        <p class="text-sm text-gray-500 mt-1">Nama paket yang akan ditampilkan kepada pengguna</p>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Paket</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                            placeholder="Masukkan deskripsi paket (opsional)">{{ old('description') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Deskripsi akan ditampilkan di halaman detail paket</p>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keperluan Dokumen -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Yang Diperlukan</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="space-y-3" id="documentsList">
                                <div class="flex gap-2 document-row">
                                    <input type="text" name="required_documents[]" 
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                                        placeholder="Contoh: KTP, Ijazah, Sertifikat, dll">
                                    <button type="button" onclick="removeDocumentRow(this)" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addDocumentRow()" 
                                class="mt-3 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-300 text-sm flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tambah Dokumen
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Masukkan jenis-jenis dokumen yang diperlukan untuk paket ini (opsional)</p>
                        @error('required_documents')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi Status -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-blue-800 font-medium">Catatan:</p>
                                <ul class="text-xs text-blue-600 mt-1 list-disc pl-5 space-y-1">
                                    <li>Masukkan nama paket yang deskriptif dan mudah dipahami</li>
                                    <li>Setelah paket dibuat, Anda dapat menambahkan biaya-biaya terkait</li>
                                    <li>Status paket dapat diaktifkan/nonaktifkan di halaman daftar paket</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="window.history.back()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-3 rounded-full transition duration-300 flex items-center">
                            <i class="fas fa-save mr-2"></i> Simpan Paket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    @include('layouts.components.admin.footer')
</div>

<script>
    // Function to add new document row
    function addDocumentRow() {
        const documentsList = document.getElementById('documentsList');
        const newRow = document.createElement('div');
        newRow.className = 'flex gap-2 document-row';
        newRow.innerHTML = `
            <input type="text" name="required_documents[]" 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                placeholder="Contoh: KTP, Ijazah, Sertifikat, dll">
            <button type="button" onclick="removeDocumentRow(this)" 
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-trash-alt"></i>
            </button>
        `;
        documentsList.appendChild(newRow);
    }

    // Function to remove document row
    function removeDocumentRow(button) {
        const documentsList = document.getElementById('documentsList');
        const rows = documentsList.querySelectorAll('.document-row');
        
        if (rows.length <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Minimal harus ada satu dokumen',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
            return;
        }
        
        button.parentElement.remove();
    }

    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Form validation
    document.getElementById('packageForm').addEventListener('submit', function(e) {
        const nameInput = document.getElementById('name');

        if (!nameInput.value.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Nama paket tidak boleh kosong.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
            nameInput.focus();
            return false;
        }
    });
</script>

<style>
</style>
@endsection

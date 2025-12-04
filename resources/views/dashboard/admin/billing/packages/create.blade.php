@extends('layouts.app')

@section('title', 'Tambah Paket Billing - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-primary">Tambah Paket Billing</h1>
                    <p class="text-secondary mt-2">Buat paket pembayaran baru</p>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-info-circle mr-1"></i> Nama paket akan diambil berdasarkan tipe paket yang dipilih
                    </p>
                </div>
                <a href="{{ route('admin.billing.packages.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto py-6 px-4">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('admin.billing.packages.store') }}" method="POST" id="packageForm">
                @csrf

                <div class="space-y-6">
                    <!-- Tipe Paket -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Paket *</label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                            onchange="updatePackageName()">
                            <option value="">Pilih Tipe Paket</option>
                            <option value="takhossus" {{ old('type') == 'takhossus' ? 'selected' : '' }}>Takhossus Pesantren</option>
                            <option value="plus_sekolah" {{ old('type') == 'plus_sekolah' ? 'selected' : '' }}>Plus Sekolah</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Pilih tipe paket untuk menentukan nama paket</p>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Paket (Auto-generated based on type) -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Paket *</label>
                        <div class="relative">
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                                placeholder="Nama paket akan diisi otomatis" required readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button type="button" onclick="enableManualEdit()" class="text-sm text-primary hover:text-secondary">
                                    <i class="fas fa-edit mr-1"></i> Edit Manual
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-lightbulb mr-1"></i> Nama paket otomatis berdasarkan tipe. Klik "Edit Manual" untuk mengubah.
                        </p>
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

                    <!-- Informasi Status -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-blue-800 font-medium">Catatan:</p>
                                <ul class="text-xs text-blue-600 mt-1 list-disc pl-5 space-y-1">
                                    <li>Nama paket akan otomatis diisi berdasarkan tipe paket</li>
                                    <li>Anda dapat mengedit nama paket secara manual jika diperlukan</li>
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
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Function to update package name based on type
    function updatePackageName() {
        const typeSelect = document.getElementById('type');
        const nameInput = document.getElementById('name');
        const selectedType = typeSelect.value;

        if (selectedType) {
            const packageNames = {
                'takhossus': 'Paket Takhossus Pesantren',
                'plus_sekolah': 'Paket Plus Sekolah'
            };

            nameInput.value = packageNames[selectedType] || '';
        } else {
            nameInput.value = '';
        }
    }

    // Function to enable manual editing of package name
    function enableManualEdit() {
        const nameInput = document.getElementById('name');
        nameInput.removeAttribute('readonly');
        nameInput.focus();

        // Change the button text
        const editButton = document.querySelector('button[onclick="enableManualEdit()"]');
        editButton.innerHTML = '<i class="fas fa-check mr-1"></i> Selesai Edit';
        editButton.setAttribute('onclick', 'disableManualEdit()');
        editButton.classList.remove('text-primary');
        editButton.classList.add('text-green-600');
    }

    // Function to disable manual editing
    function disableManualEdit() {
        const nameInput = document.getElementById('name');
        const typeSelect = document.getElementById('type');

        // If name is empty, revert to auto-generated name
        if (!nameInput.value.trim() && typeSelect.value) {
            updatePackageName();
        }

        nameInput.setAttribute('readonly', true);

        // Change the button text back
        const editButton = document.querySelector('button[onclick="disableManualEdit()"]');
        editButton.innerHTML = '<i class="fas fa-edit mr-1"></i> Edit Manual';
        editButton.setAttribute('onclick', 'enableManualEdit()');
        editButton.classList.remove('text-green-600');
        editButton.classList.add('text-primary');
    }

    // Initialize package name on page load if type is selected
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        if (typeSelect.value) {
            updatePackageName();
        }

        // If old name exists (from validation errors), enable manual edit
        const nameInput = document.getElementById('name');
        if (nameInput.value && nameInput.value !== '') {
            enableManualEdit();
        }
    });

    // Form validation
    document.getElementById('packageForm').addEventListener('submit', function(e) {
        const typeSelect = document.getElementById('type');
        const nameInput = document.getElementById('name');

        if (!typeSelect.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Silakan pilih tipe paket terlebih dahulu.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
            typeSelect.focus();
            return false;
        }

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
    #name:read-only {
        background-color: #f9fafb;
        cursor: not-allowed;
    }

    #name:focus {
        background-color: white;
        cursor: text;
    }
</style>
@endsection

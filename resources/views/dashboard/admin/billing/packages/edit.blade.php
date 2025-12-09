@extends('layouts.app')

@section('title', 'Edit Paket Billing - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-primary">Edit Paket Billing</h1>
                    <p class="text-secondary mt-2">Edit paket pembayaran: {{ $package->name }}</p>
                    <div class="mt-3">
                        <div class="bg-white px-3 py-1 rounded-full border border-gray-200 inline-block">
                            <span class="text-sm font-medium text-gray-700">
                                <i class="fas fa-money-bill-wave mr-1"></i> Total Harga:
                                <span class="text-primary font-bold">{{ $package->formatted_total_amount }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.billing.packages.prices.index', $package) }}" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-full transition duration-300 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i> Kelola Biaya
                    </a>
                    <a href="{{ route('admin.billing.packages.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('admin.billing.packages.update', $package) }}" method="POST" id="packageForm">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Nama Paket -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Paket *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}"
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
                            placeholder="Masukkan deskripsi paket (opsional)">{{ old('description', $package->description) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Deskripsi akan ditampilkan di halaman detail paket</p>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi Status -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            <div class="w-full">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm text-blue-800 font-medium">Status Paket:</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $package->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <ul class="text-xs text-blue-600 mt-1 list-disc pl-5 space-y-1">
                                    <li>Status dapat diubah di halaman daftar paket menggunakan toggle switch</li>
                                    <li>Paket nonaktif tidak akan muncul untuk dipilih oleh calon santri</li>
                                    <li>Total harga saat ini: <strong>{{ $package->formatted_total_amount }}</strong> (dari {{ $package->activePrices->count() }} biaya aktif)</li>
                                    <li><a href="{{ route('admin.billing.packages.prices.index', $package) }}" class="text-primary hover:underline font-medium">Kelola biaya paket →</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Total Harga -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Total Harga Paket</p>
                                <p class="text-2xl font-bold text-primary mt-1">{{ $package->formatted_total_amount }}</p>
                                <p class="text-xs text-gray-500 mt-1">Jumlah dari {{ $package->activePrices->count() }} biaya aktif</p>
                            </div>
                            <a href="{{ route('admin.billing.packages.prices.index', $package) }}" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-full transition duration-300 flex items-center">
                                <i class="fas fa-eye mr-2"></i> Lihat Detail Biaya
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="window.history.back()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-3 rounded-full transition duration-300 flex items-center">
                            <i class="fas fa-save mr-2"></i> Update Paket
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

        // Confirm before updating
        e.preventDefault();
        Swal.fire({
            title: 'Update Paket?',
            text: 'Apakah Anda yakin ingin memperbarui paket ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Update',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById('packageForm').submit();
            }
        });
    });
</script>

<style>
    #name:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
@endsection

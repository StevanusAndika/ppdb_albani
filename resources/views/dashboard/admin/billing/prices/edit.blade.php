@extends('layouts.app')

@section('title', 'Edit Biaya - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary nav-logo">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary font-medium">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">Logout</button>
                </form>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-primary focus:outline-none mobile-menu-button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-2 bg-white p-4 rounded-xl shadow-lg">
            <div class="flex flex-col space-y-2">
                <a href="{{ url('/') }}" class="text-primary">Beranda</a>
                <a href="{{ route('admin.dashboard') }}" class="text-primary">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-primary">Edit Biaya</h1>
                    <p class="text-secondary mt-2">Paket: {{ $package->name }} - Biaya: {{ $price->item_name }}</p>
                </div>
               
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto py-6 px-4">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('admin.billing.packages.prices.update', [$package, $price]) }}" method="POST" id="priceForm">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Nama Biaya -->
                    <div>
                        <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Biaya *</label>
                        <input type="text" name="item_name" id="item_name" value="{{ old('item_name', $price->item_name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                            placeholder="Contoh: Biaya Pendaftaran, Uang Pangkal, SPP Bulanan, dll." required>
                        @error('item_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Biaya</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                            placeholder="Deskripsi detail tentang biaya ini (opsional)">{{ old('description', $price->description) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Jelaskan detail biaya ini untuk keperluan informasi</p>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Jumlah -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Total Biaya *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">Rp</span>
                            </div>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', intval($price->amount)) }}" min="0"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-300"
                                placeholder="300000" required>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Masukkan total biaya dalam Rupiah (contoh: 300000 untuk Rp.300.000)</p>
                        <p class="text-sm text-green-600 font-medium mt-1">Format tampilan: {{ $price->formatted_amount }}</p>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Status -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-blue-800 font-medium">Status Biaya:
                                    <span class="font-semibold {{ $price->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $price->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </p>
                                <p class="text-xs text-blue-600 mt-1">Status dapat diubah di halaman daftar biaya menggunakan toggle switch</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-3 rounded-full transition duration-300 flex items-center">
                            <i class="fas fa-save mr-2"></i> Update Biaya
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tombol Kembali di bawah form -->
            <div class="mt-6 flex justify-start">
                <a href="{{ route('admin.billing.packages.prices.index', $package) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Biaya
                </a>
            </div>
        </div>
    </main>
</div>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Format amount input untuk menghilangkan desimal
    document.getElementById('amount')?.addEventListener('input', function(e) {
        let value = e.target.value;
        // Hapus karakter selain angka
        value = value.replace(/[^\d]/g, '');
        e.target.value = value;
    });
</script>
@endsection

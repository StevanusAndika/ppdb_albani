@extends('layouts.app')

@section('title', 'Kelola Biaya - Pondok Pesantren Bani Syahid')

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
                    <h1 class="text-2xl md:text-3xl font-bold text-primary">Kelola Biaya</h1>
                    <p class="text-secondary mt-2">Paket: {{ $package->name }} ({{ $package->type_label }})</p>
                    <p class="text-sm text-gray-600 mt-1">Total: {{ $package->formatted_total_amount }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.billing.packages.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('admin.billing.packages.prices.create', $package) }}" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Tambah Biaya
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Biaya</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($prices as $price)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $price->item_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                {{ $price->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $price->formatted_amount }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <label class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" {{ $price->is_active ? 'checked' : '' }}
                                            data-price-id="{{ $price->id }}"
                                            data-package-id="{{ $package->id }}"
                                            class="sr-only price-status-toggle">
                                        <div class="block {{ $price->is_active ? 'bg-green-500' : 'bg-gray-300' }} w-12 h-6 rounded-full toggle-bg"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition {{ $price->is_active ? 'translate-x-6' : '' }}"></div>
                                    </div>
                                    <span class="ml-2 text-xs text-gray-600 price-status-text">
                                        {{ $price->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </label>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.billing.packages.prices.edit', [$package, $price]) }}" class="text-yellow-600 hover:text-yellow-900 inline-flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.billing.packages.prices.destroy', [$package, $price]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus biaya ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-gray-500">Belum ada biaya untuk paket ini.</p>
                                    <a href="{{ route('admin.billing.packages.prices.create', $package) }}" class="text-primary hover:text-secondary mt-2">
                                        Tambah biaya pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

    // Auto toggle status untuk prices
    document.addEventListener('DOMContentLoaded', function() {
        const priceToggles = document.querySelectorAll('.price-status-toggle');

        priceToggles.forEach(toggle => {
            const toggleBg = toggle.parentElement.querySelector('.toggle-bg');
            const toggleDot = toggle.parentElement.querySelector('.dot');
            const statusText = toggle.parentElement.parentElement.querySelector('.price-status-text');

            toggle.addEventListener('change', function() {
                const priceId = this.getAttribute('data-price-id');
                const packageId = this.getAttribute('data-package-id');
                const isActive = this.checked;

                // Update UI immediately
                if (isActive) {
                    toggleBg.classList.remove('bg-gray-300');
                    toggleBg.classList.add('bg-green-500');
                    toggleDot.classList.add('translate-x-6');
                    statusText.textContent = 'Aktif';
                } else {
                    toggleBg.classList.remove('bg-green-500');
                    toggleBg.classList.add('bg-gray-300');
                    toggleDot.classList.remove('translate-x-6');
                    statusText.textContent = 'Nonaktif';
                }

                // Kirim request AJAX
                fetch(`/admin/billing/packages/${packageId}/prices/${priceId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        is_active: isActive
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            background: '#f0fdf4',
                            color: '#166534'
                        });
                    } else {
                        // Revert UI if failed
                        this.checked = !isActive;
                        if (!isActive) {
                            toggleBg.classList.remove('bg-gray-300');
                            toggleBg.classList.add('bg-green-500');
                            toggleDot.classList.add('translate-x-6');
                            statusText.textContent = 'Aktif';
                        } else {
                            toggleBg.classList.remove('bg-green-500');
                            toggleBg.classList.add('bg-gray-300');
                            toggleDot.classList.remove('translate-x-6');
                            statusText.textContent = 'Nonaktif';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat mengubah status.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ef4444',
                            background: '#fef2f2',
                            color: '#dc2626'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert UI on error
                    this.checked = !isActive;
                    if (!isActive) {
                        toggleBg.classList.remove('bg-gray-300');
                        toggleBg.classList.add('bg-green-500');
                        toggleDot.classList.add('translate-x-6');
                        statusText.textContent = 'Aktif';
                    } else {
                        toggleBg.classList.remove('bg-green-500');
                        toggleBg.classList.add('bg-gray-300');
                        toggleDot.classList.remove('translate-x-6');
                        statusText.textContent = 'Nonaktif';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan jaringan.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444',
                        background: '#fef2f2',
                        color: '#dc2626'
                    });
                });
            });
        });
    });
</script>

<style>
    .price-status-toggle:checked + .toggle-bg {
        background-color: #10B981 !important;
    }

    .price-status-toggle:checked + .toggle-bg + .dot {
        transform: translateX(150%);
    }

    .toggle-bg, .dot {
        transition: all 0.3s ease;
    }
</style>
@endsection

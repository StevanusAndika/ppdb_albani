@extends('layouts.app')

@section('title', 'Kelola Paket Billing - Pondok Pesantren Bani Syahid')

@section('content')
<div class=" flex flex-col min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-primary">Kelola Paket Billing</h1>
                    <p class="text-secondary mt-2">Kelola paket pembayaran untuk PPDB</p>
                </div>
                <a href="{{ route('admin.billing.packages.create') }}" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-full transition duration-300 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Paket
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokumen Diperlukan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($packages as $package)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $package->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $package->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($package->required_documents && count($package->required_documents) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($package->required_documents as $doc)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $doc }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $package->formatted_total_amount }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <label class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" {{ $package->is_active ? 'checked' : '' }}
                                            data-package-id="{{ $package->id }}"
                                            class="sr-only package-status-toggle">
                                        <div class="block {{ $package->is_active ? 'bg-green-500' : 'bg-gray-300' }} w-12 h-6 rounded-full toggle-bg"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition {{ $package->is_active ? 'translate-x-6' : '' }}"></div>
                                    </div>
                                    <span class="ml-2 text-xs text-gray-600 package-status-text">
                                        {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </label>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.billing.packages.prices.index', $package) }}" class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                    <i class="fas fa-money-bill-wave mr-1"></i> Biaya
                                </a>
                                <a href="{{ route('admin.billing.packages.edit', $package) }}" class="text-yellow-600 hover:text-yellow-900 inline-flex items-center edit-package-btn">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <button type="button" data-package-id="{{ $package->id }}"
                                        data-package-name="{{ $package->name }}"
                                        class="text-red-600 hover:text-red-900 inline-flex items-center delete-package-btn">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-gray-500">Belum ada paket yang dibuat.</p>
                                    <a href="{{ route('admin.billing.packages.create') }}" class="text-primary hover:text-secondary mt-2">
                                        Tambah paket pertama
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
 @include('layouts.components.admin.footer')

</div>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Auto toggle status untuk packages
    document.addEventListener('DOMContentLoaded', function() {
        const packageToggles = document.querySelectorAll('.package-status-toggle');

        packageToggles.forEach(toggle => {
            const toggleBg = toggle.parentElement.querySelector('.toggle-bg');
            const toggleDot = toggle.parentElement.querySelector('.dot');
            const statusText = toggle.parentElement.parentElement.querySelector('.package-status-text');

            toggle.addEventListener('change', function() {
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
                fetch(`/admin/billing/packages/${packageId}/toggle-status`, {
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

        // SweetAlert untuk konfirmasi hapus paket
        document.querySelectorAll('.delete-package-btn').forEach(button => {
            button.addEventListener('click', function() {
                const packageId = this.getAttribute('data-package-id');
                const packageName = this.getAttribute('data-package-name');

                Swal.fire({
                    title: 'Hapus Paket?',
                    html: `Apakah Anda yakin ingin menghapus paket <strong>"${packageName}"</strong>?<br><br>
                           <span class="text-sm text-red-600">
                           <i class="fas fa-exclamation-triangle mr-1"></i>
                           Tindakan ini akan menghapus semua biaya yang terkait dengan paket ini dan tidak dapat dikembalikan!
                           </span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`/admin/billing/packages/${packageId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            );
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Paket berhasil dihapus.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Reload halaman setelah 2 detik
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        });
                    }
                });
            });
        });

        // SweetAlert untuk konfirmasi edit (tampilkan informasi sebelum pindah)
        document.querySelectorAll('.edit-package-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const editUrl = this.getAttribute('href');

                Swal.fire({
                    title: 'Edit Paket?',
                    text: 'Anda akan diarahkan ke halaman edit paket.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Edit',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = editUrl;
                    }
                });
            });
        });
    });
</script>

<style>
    .package-status-toggle:checked + .toggle-bg {
        background-color: #10B981 !important;
    }

    .package-status-toggle:checked + .toggle-bg + .dot {
        transform: translateX(150%);
    }

    .toggle-bg, .dot {
        transition: all 0.3s ease;
    }
</style>
@endsection

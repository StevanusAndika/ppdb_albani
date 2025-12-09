@extends('layouts.app')

@section('title', 'Manajemen Users - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
   @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Manajemen Users</h1>
        <p class="text-secondary">Kelola data pengguna sistem PPDB</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Daftar Users</h2>
                    <p class="text-sm text-gray-600">Total: {{ $users->total() }} user</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <!-- Search Form -->
                    <form action="{{ route('admin.manage-users.index') }}" method="GET" class="w-full md:w-auto">
                        <div class="relative flex items-center">
                            <div class="relative flex-1">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Cari nama, email, atau nomor telepon..."
                                       class="w-full px-4 py-2.5 pl-10 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                @if(request('search'))
                                    <button type="button"
                                            onclick="clearSearch()"
                                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
                                            title="Hapus pencarian">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                            <button type="submit"
                                    class="ml-2 bg-primary hover:bg-secondary text-white px-4 py-2.5 rounded-lg transition duration-200 flex items-center gap-2 whitespace-nowrap">
                                <i class="fas fa-search"></i>
                                Cari
                            </button>
                        </div>
                    </form>

                    <!-- Add User Button -->
                    <a href="{{ route('admin.manage-users.create') }}"
                       class="bg-primary hover:bg-secondary text-white px-6 py-2.5 rounded-lg transition duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-plus"></i>
                        Tambah User
                    </a>
                </div>
            </div>

            <!-- Search Results Info -->
            @if(request('search'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-search text-blue-500 mr-2"></i>
                            <span class="text-sm text-blue-700">
                                Hasil pencarian untuk: <span class="font-semibold">"{{ request('search') }}"</span>
                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    {{ $users->total() }} hasil ditemukan
                                </span>
                            </span>
                        </div>
                        <button onclick="clearSearch()"
                                class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <i class="fas fa-times"></i>
                            Hapus pencarian
                        </button>
                    </div>
                </div>
            @endif

            <!-- Users Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Nama</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Nomor Telepon</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tanggal Dibuat</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-gray-700">{{ $user->email }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-gray-700 font-mono text-sm">{{ $user->phone_number }}</div>
                            </td>
                            <td class="py-4 px-4">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-purple-100 text-purple-800',
                                        'calon_santri' => 'bg-blue-100 text-blue-800'
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-500">
                                {{ $user->created_at->translatedFormat('d/m/Y') }}
                                <div class="text-xs text-gray-400">
                                    {{ $user->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.manage-users.edit', $user) }}"
                                       class="text-blue-600 hover:text-blue-900 transition duration-200 p-2 rounded-lg hover:bg-blue-50"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.manage-users.toggle-status', $user) }}"
                                          method="POST"
                                          class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-{{ $user->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $user->is_active ? 'yellow' : 'green' }}-900 transition duration-200 p-2 rounded-lg hover:bg-{{ $user->is_active ? 'yellow' : 'green' }}-50"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.manage-users.destroy', $user) }}"
                                          method="POST"
                                          class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="text-red-600 hover:text-red-900 transition duration-200 p-2 rounded-lg hover:bg-red-50 delete-btn"
                                                title="Hapus"
                                                data-user-name="{{ $user->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    @if(request('search'))
                                        <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-gray-500 mb-2">Tidak ada hasil ditemukan untuk "{{ request('search') }}"</p>
                                        <button onclick="clearSearch()"
                                                class="text-primary hover:text-secondary text-sm">
                                            Tampilkan semua user
                                        </button>
                                    @else
                                        <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-gray-500 mb-2">Belum ada data user</p>
                                        <a href="{{ route('admin.manage-users.create') }}"
                                           class="text-primary hover:text-secondary text-sm">
                                            Tambah user pertama
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Menampilkan
                    <span class="font-medium">{{ $users->firstItem() }}</span>
                    sampai
                    <span class="font-medium">{{ $users->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $users->total() }}</span>
                    hasil
                </div>

                <div class="flex space-x-1">
                    <!-- Previous Page Link -->
                    @if($users->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}"
                           class="px-3 py-2 text-primary bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    <!-- Pagination Elements -->
                    @foreach($users->links()->elements[0] as $page => $url)
                        @if($page == $users->currentPage())
                            <span class="px-3 py-2 text-white bg-primary border border-primary rounded-lg">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}{{ request('search') ? '&search=' . request('search') : '' }}"
                               class="px-3 py-2 text-primary bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">{{ $page }}</a>
                        @endif
                    @endforeach

                    <!-- Next Page Link -->
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}"
                           class="px-3 py-2 text-primary bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </main>

   @include('layouts.components.admin.footer')

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Function to clear search
    function clearSearch() {
        window.location.href = "{{ route('admin.manage-users.index') }}";
    }

    // SweetAlert for delete confirmation
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const form = this.closest('.delete-form');
                const userName = this.getAttribute('data-user-name');

                Swal.fire({
                    title: 'Hapus User?',
                    html: `Apakah Anda yakin ingin menghapus user <strong>${userName}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    background: '#fff',
                    color: '#374151'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form via AJAX
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    confirmButtonColor: '#059669',
                                    background: '#fff',
                                    color: '#374151'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message,
                                    confirmButtonColor: '#dc2626',
                                    background: '#fff',
                                    color: '#374151'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus user.',
                                confirmButtonColor: '#dc2626',
                                background: '#fff',
                                color: '#374151'
                            });
                        });
                    }
                });
            });
        });

        // Real-time search with debounce
        const searchInput = document.querySelector('input[name="search"]');
        const searchButton = document.querySelector('button[type="submit"]');
        let searchTimeout;

        if (searchInput) {
            // Add loading state to search button
            const originalButtonHTML = searchButton.innerHTML;

            // Debounce search input for better performance
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    // If empty, clear search immediately
                    if (!this.value.trim()) {
                        this.closest('form').submit();
                    }
                }, 800);
            });

            // Submit form on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    // Show loading state
                    searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
                    searchButton.disabled = true;

                    setTimeout(() => {
                        searchButton.innerHTML = originalButtonHTML;
                        searchButton.disabled = false;
                    }, 1000);

                    this.closest('form').submit();
                }
            });

            // Clear search button functionality
            const clearButton = searchInput.parentElement.querySelector('button[title="Hapus pencarian"]');
            if (clearButton) {
                clearButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    clearSearch();
                });
            }
        }

        // Advanced search filter (optional enhancement)
        const advancedSearchBtn = document.createElement('button');
        advancedSearchBtn.innerHTML = '<i class="fas fa-filter"></i>';
        advancedSearchBtn.className = 'ml-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2.5 rounded-lg transition duration-200';
        advancedSearchBtn.title = 'Filter lanjutan';

        // You can add advanced filter functionality here
        // advancedSearchBtn.addEventListener('click', function() {
        //     // Show advanced filter modal
        // });

        // Insert after search form if needed
        // const searchForm = document.querySelector('form[action*="manage-users"]');
        // if (searchForm) {
        //     searchForm.parentElement.appendChild(advancedSearchBtn);
        // }
    });
</script>

<style>
    /* Custom scrollbar for table */
    .overflow-x-auto {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }

    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Smooth transitions */
    tr {
        transition: all 0.2s ease-in-out;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        table {
            min-width: 800px;
        }

        .flex-col.md\:flex-row {
            flex-direction: column;
        }

        .relative.flex.items-center {
            flex-direction: column;
            gap: 10px;
        }

        .relative.flex.items-center > .relative.flex-1 {
            width: 100%;
        }

        .relative.flex.items-center > button {
            width: 100%;
            justify-content: center;
        }

        input[name="search"] {
            width: 100% !important;
        }
    }

    /* Search input focus effects */
    input[name="search"]:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Button hover effects */
    button[type="submit"]:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
</style>
@endsection

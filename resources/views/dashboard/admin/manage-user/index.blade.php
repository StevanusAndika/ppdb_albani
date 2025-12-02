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
    <main class="w-full mx-auto py-6 px-4">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Users</h2>
                <a href="{{ route('admin.manage-users.create') }}"
                   class="bg-primary hover:bg-secondary text-white px-6 py-2.5 rounded-full transition duration-300 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Tambah User
                </a>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Nama</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tanggal Dibuat</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-4">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
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
                                {{ $user->created_at->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.manage-users.edit', $user) }}"
                                       class="text-blue-600 hover:text-blue-900 transition duration-200 p-2 rounded-full hover:bg-blue-50"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.manage-users.toggle-status', $user) }}"
                                          method="POST"
                                          class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-{{ $user->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $user->is_active ? 'yellow' : 'green' }}-900 transition duration-200 p-2 rounded-full hover:bg-{{ $user->is_active ? 'yellow' : 'green' }}-50"
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
                                                class="text-red-600 hover:text-red-900 transition duration-200 p-2 rounded-full hover:bg-red-50 delete-btn"
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
                            <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada data user</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="mt-6 flex items-center justify-between">
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
                        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 text-primary bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    <!-- Pagination Elements -->
                    @foreach($users->links()->elements[0] as $page => $url)
                        @if($page == $users->currentPage())
                            <span class="px-3 py-2 text-white bg-primary border border-primary rounded-lg">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-primary bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">{{ $page }}</a>
                        @endif
                    @endforeach

                    <!-- Next Page Link -->
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 text-primary bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
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
    });
</script>
@endsection

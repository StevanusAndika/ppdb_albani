@extends('layouts.app')

@section('title', 'Edit User - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary nav-logo">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="{{ route('admin.manage-users.index') }}" class="text-primary hover:text-secondary font-medium">Profil</a>
                <a href="{{ route('admin.manage-users.index') }}" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="{{ route('admin.manage-users.index') }}" class="text-primary hover:text-secondary font-medium">Dokumen</a>


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
                <a href="{{ route('admin.dashboard') }}" class="text-primary">Dashboard</a>
                <a href="{{ route('admin.manage-users.index') }}" class="text-primary">Manajemen Users</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Edit User</h1>
        <p class="text-secondary">Edit data user {{ $user->name }}</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto py-6 px-4">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('admin.manage-users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                               required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                               required>
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                        <select id="role"
                                name="role"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                                required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Info tambahan -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700 mb-2">Informasi Tambahan</h3>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div>Nomor Telepon: <span class="font-medium">{{ $user->phone_number }}</span></div>
                            <div>Status:
                                <span class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <div>Dibuat: <span class="font-medium">{{ $user->created_at->translatedFormat('d F Y H:i') }}</span></div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.manage-users.index') }}"
                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-lg text-center transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 bg-primary hover:bg-secondary text-white py-3 px-6 rounded-lg transition duration-200">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

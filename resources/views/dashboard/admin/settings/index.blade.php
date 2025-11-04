@extends('layouts.app')

@section('title', 'Pengaturan Admin - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .tab-button {
        transition: all 0.3s ease;
        flex: 1;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .tab-button.active {
        background-color: #057572;
        color: white;
    }
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        transition: border-color 0.3s ease;
    }
    .form-input:focus {
        outline: none;
        border-color: #057572;
        ring: 2px solid #057572;
    }
    .icon-bg {
        background-color: rgba(5, 117, 114, 0.1);
    }
    /* Perbaikan untuk navbar mobile */
    @media (max-width: 767px) {
        .nav-container {
            padding: 0.75rem 1rem;
        }
        .nav-logo {
            font-size: 1.1rem;
        }
        .mobile-menu-button {
            padding: 0.5rem;
        }
        .mobile-menu {
            border-radius: 1rem;
            margin-top: 0.75rem;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .mobile-menu-item {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 0.25rem;
        }
        /* Perbaikan untuk tabs di mobile */
        .tabs-container {
            overflow-x: auto;
        }
        .tabs-nav {
            min-width: 100%;
            flex-wrap: nowrap;
        }
        .tab-button {
            flex: 1;
            min-width: 120px;
            font-size: 0.875rem;
            padding: 0.75rem 0.5rem;
        }
        .tab-button i {
            margin-right: 0.25rem;
        }
    }
    /* Pastikan menu desktop selalu visible */
    .desktop-menu {
        display: flex !important;
    }
    @media (max-width: 767px) {
        .desktop-menu {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary nav-logo">Ponpes Al Bani</div>

            <!-- Desktop menu - SELALU VISIBLE di desktop -->
            <div class="desktop-menu flex space-x-6 items-center">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium transition duration-300">Beranda</a>
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary font-medium transition duration-300">Dashboard</a>
                <a href="{{ route('admin.settings.index') }}" class="text-primary hover:text-secondary font-medium transition duration-300 bg-primary/10 px-3 py-1 rounded-full">Pengaturan</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">
                        Logout
                    </button>
                </form>
            </div>

            <!-- Mobile menu button - hanya visible di mobile -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-primary focus:outline-none mobile-menu-button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu - hanya untuk mobile -->
        <div id="mobile-menu" class="hidden md:hidden mt-2 mobile-menu bg-white">
            <div class="flex flex-col space-y-1">
                <a href="{{ url('/') }}" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="{{ route('admin.dashboard') }}" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.settings.index') }}" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300 bg-primary/10">
                    <i class="fas fa-cog mr-2"></i>Pengaturan
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full mobile-menu-item bg-red-500 text-white py-2 rounded-full hover:bg-red-600 transition duration-300 text-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Pengaturan Admin</h1>
        <p class="text-secondary">Kelola informasi akun dan profil Anda</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-6 px-4">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 tabs-container">
                <nav class="flex tabs-nav">
                    <button class="tab-button py-4 px-2 md:px-6 text-center font-medium text-gray-600 hover:text-primary hover:bg-gray-50 transition duration-300 {{ session('active_tab', 'profile') == 'profile' ? 'active' : '' }}"
                            data-tab="profile-tab">
                        <i class="fas fa-user-circle md:mr-2"></i>
                        <span>Profil</span>
                    </button>
                    <button class="tab-button py-4 px-2 md:px-6 text-center font-medium text-gray-600 hover:text-primary hover:bg-gray-50 transition duration-300 {{ session('active_tab', 'profile') == 'google' ? 'active' : '' }}"
                            data-tab="google-tab">
                        <i class="fab fa-google md:mr-2"></i>
                        <span>Google</span>
                    </button>
                    <button class="tab-button py-4 px-2 md:px-6 text-center font-medium text-gray-600 hover:text-primary hover:bg-gray-50 transition duration-300 {{ session('active_tab', 'profile') == 'account' ? 'active' : '' }}"
                            data-tab="account-tab">
                        <i class="fas fa-info-circle md:mr-2"></i>
                        <span>Informasi Akun</span>
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-4 md:p-6">
                <!-- Profile Tab -->
                <div id="profile-tab" class="tab-content {{ session('active_tab', 'profile') == 'profile' ? 'active' : '' }}">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Informasi Profil</h3>

                    <form id="profile-form" action="{{ route('admin.settings.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                       class="form-input @error('name') border-red-500 @enderror" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="form-input @error('email') border-red-500 @enderror" required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                       class="form-input @error('phone_number') border-red-500 @enderror"
                                       placeholder="Contoh: 081234567890">
                                @error('phone_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-full transition duration-300">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Google Tab -->
                <div id="google-tab" class="tab-content {{ session('active_tab', 'profile') == 'google' ? 'active' : '' }}">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Koneksi Google</h3>

                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-gray-200 rounded-lg gap-4">
                            <div class="flex items-center">
                                <div class="bg-red-500 rounded-full p-3 mr-4">
                                    <i class="fab fa-google text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Akun Google</h4>
                                    <p class="text-gray-600 text-sm">
                                        @if($user->isSocialiteUser())
                                            Terhubung dengan akun Google
                                        @else
                                            Belum terhubung dengan akun Google
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-center sm:justify-end">
                                @if($user->isSocialiteUser())
                                    <form id="disconnect-google-form" action="{{ route('admin.settings.google.disconnect') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full transition duration-300 w-full sm:w-auto">
                                            <i class="fas fa-unlink mr-2"></i>Putuskan Koneksi
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('socialite.redirect', ['provider' => 'google']) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full transition duration-300 w-full sm:w-auto text-center block sm:inline-block">
                                        <i class="fab fa-google mr-2"></i>Hubungkan ke Google
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-yellow-800">Informasi</h4>
                                    <p class="text-yellow-700 text-sm">
                                        Menghubungkan akun Google memungkinkan Anda login menggunakan akun Google.
                                        Jika sudah terhubung, tombol akan berubah menjadi "Putuskan Koneksi".
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Tab -->
                <div id="account-tab" class="tab-content {{ session('active_tab', 'profile') == 'account' ? 'active' : '' }}">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Informasi Akun</h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Role</label>
                                <p class="text-lg font-semibold text-primary capitalize">{{ $user->role }}</p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                                <p class="text-lg font-semibold {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Dibuat</label>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ $user->created_at->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Terakhir Diperbarui</label>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ $user->updated_at->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-800">Informasi Akun</h4>
                                    <p class="text-blue-700 text-sm">
                                        Informasi ini menunjukkan detail akun Anda. Role dan status akun tidak dapat diubah melalui halaman ini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 px-4 mt-6">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2025 PPDB Pesantren AI-Our'an Bani Syahid</p>
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to current button and target content
                this.classList.add('active');
                document.getElementById(targetTab).classList.add('active');
            });
        });

        // Format phone number input
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0')) {
                    value = value.substring(1);
                }
                e.target.value = value;
            });
        }

        // SweetAlert for form submission
        const profileForm = document.getElementById('profile-form');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                // Validation will be handled by Laravel
                // SweetAlert will show from backend response
            });
        }

        // SweetAlert for Google disconnect
        const disconnectForm = document.getElementById('disconnect-google-form');
        if (disconnectForm) {
            disconnectForm.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Putuskan Koneksi?',
                    text: "Apakah Anda yakin ingin memutuskan koneksi dengan Google?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#057572',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Putuskan!',
                    cancelButtonText: 'Batal',
                    background: '#fff',
                    color: '#374151'
                }).then((result) => {
                    if (result.isConfirmed) {
                        disconnectForm.submit();
                    }
                });
            });
        }

        // SweetAlert for notifications
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#057572',
                background: '#f0fdf4',
                color: '#166534',
                timer: 3000,
                showConfirmButton: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
                background: '#fef2f2',
                color: '#dc2626',
                timer: 4000,
                showConfirmButton: true
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#ef4444',
                background: '#fef2f2',
                color: '#dc2626',
                showConfirmButton: true
            });
        @endif
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard Admin - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary nav-logo">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="{{ route('admin.settings.index') }}?tab=profile" class="text-primary hover:text-secondary font-medium">Profil</a>
                <a href="#pendaftaran" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="#dokumen" class="text-primary hover:text-secondary font-medium">Dokumen</a>
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
                <a href="#profile" class="text-primary">Profil</a>
                <a href="#pendaftaran" class="text-primary">Pendaftaran</a>
                <a href="#dokumen" class="text-primary">Dokumen</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header Hero -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Dashboard Admin</h1>
        <p class="text-secondary">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span> — Panel pengelolaan sistem PPDB.</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Profile Card -->
            <div id="profile" class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center gap-4">
                    <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-2xl text-primary"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-primary">{{ Auth::user()->name }}</h3>
                        <p class="text-secondary text-sm">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-2 text-sm text-secondary">
                    <div class="flex justify-between"><span>Telepon</span><span class="font-medium">{{ Auth::user()->phone_number ?? '-' }}</span></div>
                    <div class="flex justify-between"><span>Role</span><span class="font-medium text-blue-600">{{ Auth::user()->role}}</span></div>
                    <div class="flex justify-between"><span>Tanggal Bergabung</span><span class="font-medium">{{ Auth::user()->created_at->translatedFormat('d F Y') }}</span></div>
                </div>

                <div class="mt-6 flex gap-3">
                     <a href="{{ route('admin.settings.index') }}?tab=profile" class="w-full text-center bg-primary text-white py-2 rounded-full transition duration-300 hover:bg-secondary">Edit Profil</a>
                </div>
            </div>

            <!-- Right: Main admin content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Welcome Message -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="text-gray-600">Anda login sebagai <span class="font-semibold text-blue-600">{{ Auth::user()->role }}</span></p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="fas fa-user-graduate text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Santri</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::where('role', 'calon_santri')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-2xl font-semibold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                <i class="fas fa-ban text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Ditolak</p>
                                <p class="text-2xl font-semibold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.manage-users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-user-cog text-2xl mb-2"></i>
                            <p>Kelola User</p>
                        </a>
                        <a href="#" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-clipboard-list text-2xl mb-2"></i>
                            <p>Verifikasi</p>
                        </a>
                        <a href="#" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-chart-bar text-2xl mb-2"></i>
                            <p>Laporan</p>
                        </a>
                        <a href="{{ route('admin.billing.packages.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-money-bill-wave text-2xl mb-2"></i>
                            <p>Kelola Paket & Harga</p>
                        </a>
                    </div>
                </div>

                <!-- Additional Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Kelola Konten Website</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.content.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-edit text-2xl mb-2"></i>
                            <p>Kelola Konten</p>
                        </a>

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

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) mobileMenu.classList.toggle('hidden');
        });
    </script>
</div>
@endsection

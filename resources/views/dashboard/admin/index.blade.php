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
                <a href="{{ route('admin.registrations.index') }}" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
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
                <a href="{{ route('admin.settings.index') }}?tab=profile" class="text-primary">Profil</a>
                <a href="{{ route('admin.registrations.index') }}" class="text-primary">Pendaftaran</a>
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
                    <p class="text-gray-600 mt-2">Total <span class="font-semibold">{{ $stats['total_registrations'] }}</span> pendaftaran telah masuk ke sistem.</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Pendaftaran</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_registrations'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="fas fa-user-check text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Diterima</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_registrations'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_registrations'] }}</p>
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
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected_registrations'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Registrations -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Pendaftaran Terbaru</h3>
                        <a href="{{ route('admin.registrations.index') }}" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua</a>
                    </div>

                    @if($recentRegistrations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">ID Pendaftaran</th>
                                    <th class="px-4 py-3">Nama Santri</th>
                                    <th class="px-4 py-3">Paket</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRegistrations as $registration)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono text-xs">{{ $registration->id_pendaftaran }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $registration->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $registration->user->email }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                            {{ $registration->package->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColors = [
                                                'belum_mendaftar' => 'bg-gray-100 text-gray-800',
                                                'telah_mengisi' => 'bg-blue-100 text-blue-800',
                                                'telah_dilihat' => 'bg-yellow-100 text-yellow-800',
                                                'menunggu_diverifikasi' => 'bg-orange-100 text-orange-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'diterima' => 'bg-green-100 text-green-800'
                                            ];
                                        @endphp
                                        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $statusColors[$registration->status_pendaftaran] }}">
                                            {{ $registration->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500">
                                        {{ $registration->created_at->translatedFormat('d M Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada pendaftaran</p>
                    </div>
                    @endif
                </div>

               <!-- Di bagian Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.manage-users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-user-cog text-2xl mb-2"></i>
                            <p>Kelola User</p>
                        </a>
                        <a href="{{ route('admin.registrations.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-clipboard-list text-2xl mb-2"></i>
                            <p>Kelola Pendaftaran</p>
                        </a>
                        <!-- TAMBAHKAN INI: Link ke halaman transaksi -->
                        <a href="{{ route('admin.transactions.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white p-4 rounded-lg transition duration-200 text-center">
                                <i class="fas fa-credit-card text-2xl mb-2"></i>
                                <p>Kelola Transaksi</p>
                            </a>
                            <a href="{{ route('admin.billing.packages.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-box text-2xl mb-2"></i>
                            <p>Kelola Paket</p>
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
                        <a href="{{ route('admin.settings.index') }}" class="bg-teal-500 hover:bg-teal-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-cogs text-2xl mb-2"></i>
                            <p>Pengaturan</p>
                        </a>
                        <a href="#" class="bg-pink-500 hover:bg-pink-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-chart-bar text-2xl mb-2"></i>
                            <p>Laporan</p>
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

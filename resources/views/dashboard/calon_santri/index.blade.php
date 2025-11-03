@extends('layouts.app')

@section('title', 'Dashboard Santri - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar (in-page for styling parity with welcome) -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="#profile" class="text-primary hover:text-secondary font-medium">Profil</a>
                <a href="#pendaftaran" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="#dokumen" class="text-primary hover:text-secondary font-medium">Dokumen</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded-full hover:bg-secondary transition duration-300">Logout</button>
                </form>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-primary focus:outline-none">
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
                    <button type="submit" class="w-full bg-primary text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header Hero -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Dashboard Santri</h1>
        <p class="text-secondary">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span> — Selamat datang di panel pendaftaran.</p>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md mt-6">
            <div class="flex flex-col md:flex-row items-center md:items-start md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-primary">Status Akun</h2>
                    <p class="text-secondary mt-1">Anda login sebagai <span class="font-semibold text-green-600">Calon Santri</span></p>
                </div>

                <div class="flex gap-3">
                    <a href="#" class="bg-primary text-white px-4 py-1.5 rounded-full hover:bg-secondary transition duration-300 flex items-center justify-center">Lihat Status</a>
                    <a href="#pendaftaran" class="bg-emerald-600 text-white px-4 py-1.5 rounded-full hover:bg-emerald-700 transition duration-300 flex items-center justify-center">Isi Form Pendaftaran</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Profile Card -->
            <div id="profile" class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center gap-4">
                    <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-primary"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-primary">{{ Auth::user()->name }}</h3>
                        <p class="text-secondary text-sm">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-2 text-sm text-secondary">
                    <div class="flex justify-between"><span>Telepon</span><span class="font-medium">{{ Auth::user()->phone_number ?? '-' }}</span></div>
                    <div class="flex justify-between"><span>Role</span><span class="font-medium text-green-600">Calon Santri</span></div>
                    <div class="flex justify-between"><span>Tanggal Daftar</span><span class="font-medium">-</span></div>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="#" class="w-full text-center bg-primary text-white py-2 rounded-full">Edit Profil</a>
                    <a href="#dokumen" class="w-full text-center bg-secondary text-white py-2 rounded-full">Unggah Dokumen</a>
                </div>
            </div>

            <!-- Middle: Status & Steps -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-primary mb-3">Status Pendaftaran</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <p class="text-secondary items-center">Status saat ini:</p>
                            <div>
                                <div class="px-3 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 items-center">
                                    <i class="fas fa-clock mr-2"></i>Belum Mendaftar
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="#pendaftaran" class="bg-primary text-white px-4 py-2 rounded-full">Mulai Pendaftaran</a>
                        </div>
                    </div>
                </div>

                <div id="pendaftaran" class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-primary mb-4">Alur Pendaftaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 border border-primary/10 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="step-number">1</div>
                                <div>
                                    <h4 class="font-semibold text-primary">Buat Akun</h4>
                                    <p class="text-sm text-secondary">Sudah selesai</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 border border-primary/10 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="step-number">2</div>
                                <div>
                                    <h4 class="font-semibold text-primary">Isi Biodata</h4>
                                    <p class="text-sm text-secondary">Lengkapi data & unggah berkas</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 border border-primary/10 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="step-number">3</div>
                                <div>
                                    <h4 class="font-semibold text-primary">Pembayaran</h4>
                                    <p class="text-sm text-secondary">Konfirmasi pembayaran</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="dokumen" class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-primary mb-3">Persyaratan Dokumen</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 rounded-lg border border-primary/10 text-center">
                            <img src="{{ asset('image/formulir.png') }}" class="mx-auto w-12 h-12 mb-2" alt="formulir">
                            <div class="text-sm font-semibold text-primary">Formulir</div>
                        </div>
                        <div class="p-4 rounded-lg border border-primary/10 text-center">
                            <img src="{{ asset('image/pasfoto.png') }}" class="mx-auto w-12 h-12 mb-2" alt="pasfoto">
                            <div class="text-sm font-semibold text-primary">Pas Foto</div>
                        </div>
                        <div class="p-4 rounded-lg border border-primary/10 text-center">
                            <img src="{{ asset('image/akte.png') }}" class="mx-auto w-12 h-12 mb-2" alt="akte">
                            <div class="text-sm font-semibold text-primary">Akte</div>
                        </div>
                        <div class="p-4 rounded-lg border border-primary/10 text-center">
                            <img src="{{ asset('image/kk.png') }}" class="mx-auto w-12 h-12 mb-2" alt="kk">
                            <div class="text-sm font-semibold text-primary">Kartu Keluarga</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer (simple) -->
    <footer class="bg-primary text-white py-8 px-4 mt-6">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2025 PPDB Pesantren AI-Our'an Bani Syahid</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle (small, follows welcome pattern)
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) mobileMenu.classList.toggle('hidden');
        });
    </script>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard Santri - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div class="logo-container">
                        <div class="logo-text">PPDB</div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-xl font-bold text-gray-900">Dashboard Santri</h1>
                        <p class="text-sm text-gray-600">Pondok Pesantren Bani Syahid</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Halo, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="text-gray-600">Anda login sebagai <span class="font-semibold text-green-600">Calon Santri</span></p>
                </div>
            </div>

            <!-- Status Pendaftaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pendaftaran</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600">Status:</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i>Belum Mendaftar
                                </span>
                            </div>
                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                <i class="fas fa-edit mr-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pribadi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium">{{ Auth::user()->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">No. Telepon:</span>
                                <span class="font-medium">{{ Auth::user()->phone_number ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Role:</span>
                                <span class="font-medium text-green-600">Calon Santri</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-user-edit text-2xl mb-2"></i>
                            <p>Edit Profil</p>
                        </button>
                        <button class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-file-alt text-2xl mb-2"></i>
                            <p>Form Pendaftaran</p>
                        </button>
                        <button class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition duration-200 text-center">
                            <i class="fas fa-history text-2xl mb-2"></i>
                            <p>Riwayat</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

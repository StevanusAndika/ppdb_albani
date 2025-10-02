@extends('layouts.app')

@section('title', 'Dashboard Santri - PPDB PESANTREN AL-GURAN BANI SYAHID')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Santri</h1>
                <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}! Status: Calon Santri</p>
            </div>
            <div class="bg-green-100 text-green-600 px-4 py-2 rounded-xl font-semibold">
                <i class="fas fa-user-graduate mr-2"></i>Role: Calon Santri
            </div>
        </div>
    </div>

    <!-- Progress Pendaftaran -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Progress Pendaftaran</h3>
        <div class="space-y-4">
            <!-- Step 1 -->
            <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-semibold">
                    1
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Formulir Data Diri</p>
                    <p class="text-sm text-gray-600">Lengkapi informasi pribadi anda</p>
                </div>
                <div class="bg-green-100 text-green-600 px-3 py-1 rounded-lg text-sm font-semibold">
                    <i class="fas fa-check mr-1"></i>Selesai
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-semibold">
                    2
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Upload Berkas</p>
                    <p class="text-sm text-gray-600">Upload foto dan dokumen pendukung</p>
                </div>
                <div class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-lg text-sm font-semibold">
                    <i class="fas fa-clock mr-1"></i>Proses
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="bg-gray-400 text-white w-8 h-8 rounded-full flex items-center justify-center font-semibold">
                    3
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Verifikasi Admin</p>
                    <p class="text-sm text-gray-600">Menunggu verifikasi dari administrator</p>
                </div>
                <div class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-sm font-semibold">
                    Menunggu
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="bg-gray-400 text-white w-8 h-8 rounded-full flex items-center justify-center font-semibold">
                    4
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Hasil Seleksi</p>
                    <p class="text-sm text-gray-600">Pengumuman hasil penerimaan</p>
                </div>
                <div class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-sm font-semibold">
                    Menunggu
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Penting -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Penting</h3>
            <div class="space-y-3">
                <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                    <div>
                        <p class="font-semibold text-gray-800">Batas Waktu Pendaftaran</p>
                        <p class="text-sm text-gray-600">31 Desember 2024</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                    <div>
                        <p class="font-semibold text-gray-800">Tes Seleksi</p>
                        <p class="text-sm text-gray-600">15 Januari 2025</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <i class="fas fa-calendar-check text-green-600 mt-1"></i>
                    <div>
                        <p class="font-semibold text-gray-800">Pengumuman Hasil</p>
                        <p class="text-sm text-gray-600">30 Januari 2025</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="#" class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i>Lengkapi Formulir
                </a>
                <a href="#" class="w-full bg-green-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-green-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-upload mr-2"></i>Upload Berkas
                </a>
                <a href="#" class="w-full bg-purple-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-purple-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-download mr-2"></i>Download Formulir
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard Admin - PPDB PESANTREN AL-GURAN BANI SYAHID')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Admin</h1>
                <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}! Anda login sebagai Administrator.</p>
            </div>
            <div class="bg-blue-100 text-blue-600 px-4 py-2 rounded-xl font-semibold">
                <i class="fas fa-user-shield mr-2"></i>Role: Admin
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Pendaftar</p>
                    <h3 class="text-2xl font-bold text-gray-800">150</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Pending Verifikasi</p>
                    <h3 class="text-2xl font-bold text-gray-800">23</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-xl">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Diterima</p>
                    <h3 class="text-2xl font-bold text-gray-800">95</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Ditolak</p>
                    <h3 class="text-2xl font-bold text-gray-800">32</h3>
                </div>
                <div class="bg-red-100 p-3 rounded-xl">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="#" class="bg-blue-50 p-4 rounded-xl border border-blue-200 hover:bg-blue-100 transition-colors text-center">
                        <i class="fas fa-user-plus text-blue-600 text-2xl mb-2"></i>
                        <p class="font-semibold text-gray-800">Tambah Admin</p>
                    </a>
                    <a href="#" class="bg-green-50 p-4 rounded-xl border border-green-200 hover:bg-green-100 transition-colors text-center">
                        <i class="fas fa-file-export text-green-600 text-2xl mb-2"></i>
                        <p class="font-semibold text-gray-800">Export Data</p>
                    </a>
                    <a href="#" class="bg-purple-50 p-4 rounded-xl border border-purple-200 hover:bg-purple-100 transition-colors text-center">
                        <i class="fas fa-cog text-purple-600 text-2xl mb-2"></i>
                        <p class="font-semibold text-gray-800">Pengaturan</p>
                    </a>
                    <a href="#" class="bg-orange-50 p-4 rounded-xl border border-orange-200 hover:bg-orange-100 transition-colors text-center">
                        <i class="fas fa-chart-bar text-orange-600 text-2xl mb-2"></i>
                        <p class="font-semibold text-gray-800">Laporan</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <i class="fas fa-user-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Pendaftaran Baru</p>
                            <p class="text-sm text-gray-600">Ahmad baru saja mendaftar</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-file-upload text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Upload Berkas</p>
                            <p class="text-sm text-gray-600">Siti mengupload foto</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="bg-yellow-100 p-2 rounded-lg">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Menunggu Verifikasi</p>
                            <p class="text-sm text-gray-600">5 pendaftar perlu diverifikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

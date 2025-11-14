@extends('layouts.app')

@section('title', 'Dashboard Santri - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary nav-logo">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="#profile" class="text-primary hover:text-secondary font-medium">Profil</a>
                <a href="{{ route('santri.settings.index') }}" class="text-primary hover:text-secondary font-medium">Pengaturan</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary font-medium">Dokumen</a>
                <a href="{{ route('santri.payments.index') }}" class="text-primary hover:text-secondary font-medium">Pembayaran</a>
                <a href="{{ route('santri.faq.index') }}" class="text-primary hover:text-secondary font-medium">FAQ</a>
                <a href="{{ route('santri.kegiatan.index') }}" class="text-primary hover:text-secondary font-medium">Kegiatan</a>
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
                <a href="{{ route('santri.settings.index') }}" class="text-primary">Pengaturan</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary">Dokumen</a>
                <a href="{{ route('santri.payments.index') }}" class="text-primary">Pembayaran</a>
                <a href="{{ route('santri.faq.index') }}" class="text-primary">FAQ</a>
                <a href="{{ route('santri.kegiatan.index') }}" class="text-primary">Kegiatan</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header Hero -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Dashboard Santri</h1>
        <p class="text-secondary">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span> — Selamat datang di panel pendaftaran.</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Profile Card & Menu -->
            <div class="space-y-6">
                <!-- Profile Card -->
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
                        <div class="flex justify-between"><span>Role</span><span class="font-medium text-green-600">{{ Auth::user()->role }}</span></div>
                        <div class="flex justify-between">
                            <span>Tanggal Daftar</span>
                            <span class="font-medium">{{ Auth::user()->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                        @if($registration)
                        <div class="flex justify-between">
                            <span>Paket Dipilih</span>
                            <span class="font-medium text-primary">{{ $registration->package->name ?? '-' }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6 flex gap-3">
                        @if($registration)
                            <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                                Edit Biodata
                            </a>
                        @else
                            <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                                Isi Biodata
                            </a>
                        @endif
                    </div>

                    <!-- Settings Button -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('santri.settings.index') }}" class="w-full bg-gray-600 text-white py-2 rounded-full hover:bg-gray-700 transition duration-300 flex items-center justify-center">
                            <i class="fas fa-cog mr-2"></i> Pengaturan Akun
                        </a>
                        <p class="text-xs text-gray-500 text-center mt-2">Kelola profil, email, dan koneksi Google</p>
                    </div>

                    <!-- Download All Documents Button -->
                    @if($registration && $registration->hasAllDocuments())
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button onclick="downloadAllDocuments()" class="w-full bg-purple-600 text-white py-2 rounded-full hover:bg-purple-700 transition duration-300 flex items-center justify-center">
                            <i class="fas fa-file-archive mr-2"></i> Download Semua Dokumen
                        </button>
                    </div>
                    @endif

                    <!-- Barcode Button -->
                    {{-- @if($registration && $barcodeUrl)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button onclick="showBarcodeModal()" class="w-full bg-indigo-600 text-white py-2 rounded-full hover:bg-indigo-700 transition duration-300 flex items-center justify-center">
                            <i class="fas fa-qrcode mr-2"></i> Lihat QR Code
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-2">Untuk verifikasi pendaftaran</p>
                    </div>
                    @endif --}}
                </div>

                <!-- Quick Menu Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-primary mb-4">Menu Cepat</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Biodata Card -->
                        <a href="{{ route('santri.biodata.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-4 text-center hover:from-blue-600 hover:to-blue-700 transition duration-300 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-edit text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">Biodata</span>
                            </div>
                        </a>

                        <!-- Dokumen Card -->
                        <a href="{{ route('santri.documents.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-4 text-center hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file-upload text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">Dokumen</span>
                            </div>
                        </a>

                        <!-- Pembayaran Card -->
                        <a href="{{ route('santri.payments.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg p-4 text-center hover:from-purple-600 hover:to-purple-700 transition duration-300 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-credit-card text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">Pembayaran</span>
                            </div>
                        </a>

                        <!-- Barcode Card -->
                        @if($registration && $barcodeUrl)
                        {{-- <a href="javascript:void(0)" onclick="showBarcodeModal()" class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg p-4 text-center hover:from-indigo-600 hover:to-indigo-700 transition duration-300 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-qrcode text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">QR Code</span>
                            </div>
                        </a> --}}
                        @else
                        <div class="bg-gradient-to-br from-gray-300 to-gray-400 text-white rounded-lg p-4 text-center opacity-50">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-qrcode text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">QR Code</span>
                            </div>
                        </div>
                        @endif

                        <!-- Settings Card -->
                        <a href="{{ route('santri.settings.index') }}" class="bg-gradient-to-br from-gray-600 to-gray-700 text-white rounded-lg p-4 text-center hover:from-gray-700 hover:to-gray-800 transition duration-300 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cog text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">Pengaturan</span>
                            </div>
                        </a>

                        <!-- FAQ Card -->
                        <a href="{{ route('santri.faq.index') }}" class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg p-4 text-center hover:from-indigo-600 hover:to-indigo-700 transition duration-300 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-question-circle text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">FAQ</span>
                            </div>
                        </a>

                        <!-- Kegiatan Card -->
                        <a href="{{ route('santri.kegiatan.index') }}" class="bg-gradient-to-br from-pink-500 to-pink-600 text-white rounded-lg p-4 text-center hover:from-pink-600 hover:to-pink-700 transition duration-300 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-calendar-alt text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">Kegiatan</span>
                            </div>
                        </a>

                        <a href="https://api.whatsapp.com/send?phone=6287748115931&text=Halo%20Admin%20Pondok%20Pesantren%20Al%20Quran%20Bani%20Syahid%2C%20saya%20memiliki%20kendala%20atau%20ingin%20konsultasi%20seputar%20Pondok%20Pesantren%20Al%20Quran%20Bani%20Syahid"
                        class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-4 text-center hover:from-green-600 hover:to-green-700 transition duration-300 transform hover:scale-105">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-headset text-2xl mb-2"></i>
                                <span class="font-semibold text-sm">Bantuan</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Progress Summary -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Ringkasan Progress</h4>
                    <div class="space-y-3">
                        @php
                            $progressItems = [
                                'Biodata' => [
                                    'completed' => (bool)$registration,
                                    'route' => route('santri.biodata.index'),
                                    'color' => 'blue'
                                ],
                                'Dokumen' => [
                                    'completed' => $registration && $registration->hasAllDocuments(),
                                    'progress' => $documentProgress ?? 0,
                                    'route' => route('santri.documents.index'),
                                    'color' => 'green'
                                ],
                                'Pembayaran' => [
                                    'completed' => $hasSuccessfulPayment ?? false,
                                    'route' => route('santri.payments.index'),
                                    'color' => 'purple'
                                ],
                                'QR Code' => [
                                    'completed' => $registration && $barcodeUrl,
                                    'route' => 'javascript:void(0)',
                                    'onclick' => 'showBarcodeModal()',
                                    'color' => 'indigo'
                                ],
                                'Pengaturan' => [
                                    'completed' => true,
                                    'route' => route('santri.settings.index'),
                                    'color' => 'gray'
                                ]
                            ];
                        @endphp

                        @foreach($progressItems as $label => $item)
                        <a href="{{ $item['route'] }}"
                           @if(isset($item['onclick'])) onclick="{{ $item['onclick'] }}" @endif
                           class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition duration-300">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-{{ $item['color'] }}-500 mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            </div>
                            <div class="flex items-center">
                                @if(isset($item['progress']))
                                    <span class="text-xs text-gray-500 mr-2">{{ $item['progress'] }}%</span>
                                @endif
                                @if($item['completed'])
                                    <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                @else
                                    <i class="fas fa-clock text-yellow-500 text-sm"></i>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Biodata Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="fas fa-user-edit text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Biodata</p>
                                @if($registration)
                                    <p class="text-lg font-semibold text-gray-900">Lengkap</p>
                                @else
                                    <p class="text-lg font-semibold text-gray-900">Belum</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="fas fa-file-alt text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Dokumen</p>
                                @if($registration && $registration->hasAllDocuments())
                                    <p class="text-lg font-semibold text-gray-900">Lengkap</p>
                                @elseif($registration)
                                    <p class="text-lg font-semibold text-gray-900">{{ round($documentProgress) }}%</p>
                                @else
                                    <p class="text-lg font-semibold text-gray-900">Belum</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <i class="fas fa-credit-card text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pembayaran</p>
                                @if($hasSuccessfulPayment)
                                    <p class="text-lg font-semibold text-gray-900">Lunas</p>
                                @elseif($registration && $registration->hasAllDocuments())
                                    <p class="text-lg font-semibold text-gray-900">Siap Bayar</p>
                                @else
                                    <p class="text-lg font-semibold text-gray-900">Menunggu</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Barcode Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <i class="fas fa-qrcode text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">QR Code</p>
                                @if($registration && $barcodeUrl)
                                    <button onclick="showBarcodeModal()" class="text-lg font-semibold text-indigo-600 hover:text-indigo-800">
                                        Tersedia
                                    </button>
                                @else
                                    <p class="text-lg font-semibold text-gray-900">-</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Pendaftaran Detail -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-primary">Status Pendaftaran</h3>
                        @if($registration)
                            <div class="flex gap-2">
                                @if($registration->status_pendaftaran == 'ditolak')
                                    <a href="{{ route('santri.biodata.index') }}" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition duration-300 text-sm">
                                        Perbaiki Data
                                    </a>
                                @endif

                            </div>
                        @else
                            <a href="{{ route('santri.biodata.index') }}" class="bg-primary text-white px-4 py-2 rounded-full hover:bg-secondary transition duration-300 text-sm">
                                Mulai Pendaftaran
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-4">
                        @if($registration)
                            @php
                                $statusColors = [
                                    'belum_mendaftar' => 'bg-gray-100 text-gray-800',
                                    'telah_mengisi' => 'bg-blue-100 text-blue-800',
                                    'telah_dilihat' => 'bg-yellow-100 text-yellow-800',
                                    'menunggu_diverifikasi' => 'bg-orange-100 text-orange-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'diterima' => 'bg-green-100 text-green-800',
                                    'perlu_review' => 'bg-purple-100 text-purple-800'
                                ];
                                $statusIcons = [
                                    'belum_mendaftar' => 'fa-clock',
                                    'telah_mengisi' => 'fa-edit',
                                    'telah_dilihat' => 'fa-eye',
                                    'menunggu_diverifikasi' => 'fa-hourglass-half',
                                    'ditolak' => 'fa-times-circle',
                                    'diterima' => 'fa-check-circle',
                                    'perlu_review' => 'fa-search-plus'
                                ];
                            @endphp
                            <div class="px-4 py-3 rounded-full text-base font-medium {{ $statusColors[$registration->status_pendaftaran] ?? 'bg-gray-100 text-gray-800' }} flex items-center">
                                <i class="fas {{ $statusIcons[$registration->status_pendaftaran] ?? 'fa-question-circle' }} mr-3"></i>
                                {{ $registration->status_label }}
                            </div>
                            @if($registration)
                                <div class="text-sm text-gray-600">
                                    ID: <span class="font-mono font-bold">{{ $registration->id_pendaftaran }}</span>
                                </div>
                            @endif
                        @else
                            <div class="px-4 py-3 rounded-full text-base font-medium bg-yellow-100 text-yellow-800 flex items-center">
                                <i class="fas fa-clock mr-3"></i>Belum Mendaftar
                            </div>
                        @endif
                    </div>

                    @if($registration && $registration->status_pendaftaran == 'ditolak' && $registration->catatan_admin)
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-3"></i>
                            <div>
                                <p class="font-medium text-red-800">Catatan dari Admin:</p>
                                <p class="text-red-600 text-sm mt-1">{{ $registration->catatan_admin }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Progress Steps -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-primary mb-4">Progress Pendaftaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Step 1: Buat Akun -->
                        <div class="p-4 border-2 border-green-500 bg-green-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="step-number bg-green-500 text-white">1</div>
                                <div>
                                    <h4 class="font-semibold text-green-800">Buat Akun</h4>
                                    <p class="text-sm text-green-600">Selesai</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Isi Biodata -->
                        <div class="p-4 border-2 {{ $registration ? 'border-green-500 bg-green-50' : 'border-gray-300' }} rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="step-number {{ $registration ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">2</div>
                                <div>
                                    <h4 class="font-semibold {{ $registration ? 'text-green-800' : 'text-gray-600' }}">Isi Biodata</h4>
                                    <p class="text-sm {{ $registration ? 'text-green-600' : 'text-gray-500' }}">
                                        {{ $registration ? 'Selesai' : 'Belum' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Upload Dokumen -->
                        <div class="p-4 border-2 {{ $registration && $registration->hasAllDocuments() ? 'border-green-500 bg-green-50' : 'border-gray-300' }} rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="step-number {{ $registration && $registration->hasAllDocuments() ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">3</div>
                                <div>
                                    <h4 class="font-semibold {{ $registration && $registration->hasAllDocuments() ? 'text-green-800' : 'text-gray-600' }}">Upload Dokumen</h4>
                                    <p class="text-sm {{ $registration && $registration->hasAllDocuments() ? 'text-green-600' : 'text-gray-500' }}">
                                        @if($registration && $registration->hasAllDocuments())
                                            Lengkap
                                        @elseif($registration)
                                            {{ round($documentProgress) }}%
                                        @else
                                            Belum
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Pembayaran -->
                        <div class="p-4 border-2 {{ $hasSuccessfulPayment ? 'border-green-500 bg-green-50' : 'border-gray-300' }} rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="step-number {{ $hasSuccessfulPayment ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">4</div>
                                <div>
                                    <h4 class="font-semibold {{ $hasSuccessfulPayment ? 'text-green-800' : 'text-gray-600' }}">Pembayaran</h4>
                                    <p class="text-sm {{ $hasSuccessfulPayment ? 'text-green-600' : 'text-gray-500' }}">
                                        @if($hasSuccessfulPayment)
                                            Lunas
                                        @elseif($registration && $registration->hasAllDocuments())
                                            Siap Bayar
                                        @else
                                            Menunggu
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @if($registration)
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progress Keseluruhan</span>
                            <span>{{ $totalProgress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-primary h-3 rounded-full transition-all duration-300"
                                 style="width: {{ $totalProgress }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Barcode Section -->
                @if($registration && $barcodeUrl)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-primary">QR Code Pendaftaran</h3>
                        <div class="flex gap-2">
                            <button onclick="showBarcodeModal()" class="bg-indigo-500 text-white px-4 py-2 rounded-full hover:bg-indigo-600 transition duration-300 text-sm flex items-center">
                                <i class="fas fa-expand mr-1"></i> Lihat Fullscreen
                            </button>
                            <a href="{{ $barcodeDownloadUrl }}" class="bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700 transition duration-300 text-sm flex items-center">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <!-- QR Code Preview -->
                        <div class="bg-white p-4 rounded-lg border-2 border-indigo-200 shadow-sm">
                            <img src="{{ $barcodeUrl }}"
                                 alt="QR Code Pendaftaran"
                                 class="w-48 h-48 mx-auto qr-fade-in"
                                 id="barcodePreview">
                        </div>

                        <!-- Barcode Information -->
                        <div class="flex-1">
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-2">Informasi QR Code</h4>
                                    <p class="text-sm text-gray-600">
                                        QR Code ini berisi informasi pendaftaran Anda dan dapat digunakan untuk verifikasi oleh admin.
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500">ID Pendaftaran</p>
                                        <p class="font-mono font-bold text-primary">{{ $registration->id_pendaftaran }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Status</p>
                                        <p class="font-semibold text-green-600">Aktif</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <button onclick="showBarcodeModal()" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition duration-300 text-sm flex items-center">
                                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                                    </button>
                                    <a href="{{ $barcodeDownloadUrl }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 text-sm flex items-center">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </a>
                                    <a href="{{ $barcodeInfoUrl }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-sm flex items-center">
                                        <i class="fas fa-external-link-alt mr-1"></i> Info Page
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Dokumen Section -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-primary">Kelengkapan Dokumen</h3>
                        <div class="flex gap-2">
                            @if($registration && $registration->hasAllDocuments())
                            <button onclick="downloadAllDocuments()" class="bg-purple-600 text-white px-3 py-1 rounded-full hover:bg-purple-700 transition duration-300 text-sm flex items-center">
                                <i class="fas fa-file-archive mr-1"></i> Download Semua Data ZIP
                            </button>
                            @endif
                            <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary text-sm font-medium bg-gray-100 px-3 py-1 rounded-full">
                                Kelola Dokumen
                            </a>
                        </div>
                    </div>

                    @if($registration)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $documents = [
                                'kartu_keluarga' => [
                                    'name' => 'Kartu Keluarga',
                                    'uploaded' => !empty($registration->kartu_keluaga_path),
                                    'icon' => 'fas fa-id-card',
                                    'color' => 'blue'
                                ],
                                'ijazah' => [
                                    'name' => 'Ijazah',
                                    'uploaded' => !empty($registration->ijazah_path),
                                    'icon' => 'fas fa-graduation-cap',
                                    'color' => 'green'
                                ],
                                'akta_kelahiran' => [
                                    'name' => 'Akta Kelahiran',
                                    'uploaded' => !empty($registration->akta_kelahiran_path),
                                    'icon' => 'fas fa-birthday-cake',
                                    'color' => 'purple'
                                ],
                                'pas_foto' => [
                                    'name' => 'Pas Foto',
                                    'uploaded' => !empty($registration->pas_foto_path),
                                    'icon' => 'fas fa-camera',
                                    'color' => 'orange'
                                ]
                            ];
                        @endphp

                        @foreach($documents as $type => $doc)
                        <div class="p-4 rounded-lg border-2 {{ $doc['uploaded'] ? 'border-green-500 bg-green-50' : 'border-gray-300' }} text-center transition duration-300 hover:shadow-md">
                            <i class="{{ $doc['icon'] }} text-2xl {{ $doc['uploaded'] ? 'text-green-500' : 'text-gray-400' }} mb-2"></i>
                            <div class="text-sm font-semibold {{ $doc['uploaded'] ? 'text-green-700' : 'text-gray-600' }}">
                                {{ $doc['name'] }}
                            </div>
                            <div class="text-xs mt-1 {{ $doc['uploaded'] ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $doc['uploaded'] ? '✓ Terunggah' : 'Belum diunggah' }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Document Progress -->
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progress Dokumen</span>
                            <span>{{ round($documentProgress) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full transition-all duration-300"
                                 style="width: {{ $documentProgress }}%"></div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-6">
                        <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Silakan isi biodata terlebih dahulu untuk mengunggah dokumen</p>
                        <a href="{{ route('santri.biodata.index') }}" class="inline-block mt-3 bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                            Isi Biodata Sekarang
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Status Pembayaran -->
                @if($registration && $registration->hasAllDocuments())
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-primary">Status Pembayaran</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('santri.payments.index') }}" class="text-primary hover:text-secondary text-sm font-medium bg-gray-100 px-3 py-1 rounded-full">
                                Riwayat Pembayaran
                            </a>
                            @if(!$hasSuccessfulPayment)
                            <a href="{{ route('santri.payments.create') }}" class="bg-primary text-white px-3 py-1 rounded-full hover:bg-secondary transition duration-300 text-sm">
                                Bayar Sekarang
                            </a>
                            @endif
                        </div>
                    </div>

                    @if($latestPayment)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-blue-800">Pembayaran Terakhir</h4>
                                <p class="text-blue-600 text-sm">Kode: {{ $latestPayment->payment_code }}</p>
                                <p class="text-blue-600 text-sm">Jumlah: {{ $latestPayment->formatted_amount }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $latestPayment->status_color }}">
                                    {{ $latestPayment->status_label }}
                                </span>
                                <p class="text-blue-600 text-sm mt-1">{{ $latestPayment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-6">
                        <i class="fas fa-credit-card text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada pembayaran</p>
                        <a href="{{ route('santri.payments.create') }}" class="inline-block mt-3 bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                           Bayar Sekarang
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-primary mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('santri.biodata.index') }}" class="p-4 border-2 border-primary rounded-lg text-center hover:bg-primary hover:text-white transition duration-300">
                            <i class="fas fa-edit text-2xl mb-2"></i>
                            <p class="font-semibold">Edit Biodata</p>
                            <p class="text-sm opacity-75">Perbarui data pribadi</p>
                        </a>
                        <a href="{{ route('santri.documents.index') }}" class="p-4 border-2 border-secondary rounded-lg text-center hover:bg-secondary hover:text-white transition duration-300">
                            <i class="fas fa-upload text-2xl mb-2"></i>
                            <p class="font-semibold">Upload Dokumen</p>
                            <p class="text-sm opacity-75">Kelola berkas persyaratan</p>
                        </a>
                        <a href="{{ route('santri.payments.index') }}" class="p-4 border-2 border-green-500 rounded-lg text-center hover:bg-green-500 hover:text-white transition duration-300">
                            <i class="fas fa-receipt text-2xl mb-2"></i>
                            <p class="font-semibold">Riwayat Bayar</p>
                            <p class="text-sm opacity-75">Lihat status pembayaran</p>
                        </a>
                        @if($registration && $barcodeUrl)
                        {{-- <a href="javascript:void(0)" onclick="showBarcodeModal()" class="p-4 border-2 border-indigo-500 rounded-lg text-center hover:bg-indigo-500 hover:text-white transition duration-300">
                            <i class="fas fa-qrcode text-2xl mb-2"></i>
                            <p class="font-semibold">QR Code</p>
                            <p class="text-sm opacity-75">Lihat barcode pendaftaran</p>
                        </a> --}}
                        @else
                        <div class="p-4 border-2 border-gray-300 rounded-lg text-center text-gray-400">
                            <i class="fas fa-qrcode text-2xl mb-2"></i>
                            <p class="font-semibold">QR Code</p>
                            <p class="text-sm opacity-75">Tersedia setelah daftar</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Barcode Modal -->
    <div id="barcodeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-primary">QR Code Pendaftaran</h3>
                <button onclick="closeBarcodeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            @if($registration && $barcodeUrl)
            <div class="text-center">
                <!-- QR Code Image -->
                <div class="bg-white p-4 rounded-lg border-2 border-gray-200 mb-4">
                    <img src="{{ $barcodeUrl }}"
                         alt="QR Code Pendaftaran"
                         class="w-64 h-64 mx-auto qr-fade-in"
                         id="barcodeImage">
                </div>

                <!-- ID Pendaftaran -->
                <div class="mb-4">
                    <p class="text-sm text-gray-600">ID Pendaftaran:</p>
                    <p class="font-mono font-bold text-lg text-primary">{{ $registration->id_pendaftaran }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ $barcodeDownloadUrl }}"
                       class="bg-primary text-white px-4 py-2 rounded-full hover:bg-secondary transition duration-300 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i> Download QR 
                    </a>
                    <a href="{{ $barcodeInfoUrl }}"
                       target="_blank"
                       class="bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-external-link-alt mr-2"></i> Info Lengkap
                    </a>
                    <button onclick="refreshBarcode()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh
                    </button>
                </div>

                <!-- Information -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        QR Code ini dapat digunakan untuk verifikasi pendaftaran Anda
                    </p>
                </div>
            </div>
            @else
            <div class="text-center py-6">
                <i class="fas fa-qrcode text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">QR Code akan tersedia setelah Anda menyelesaikan pendaftaran</p>
                @if(!$registration)
                <a href="{{ route('santri.biodata.index') }}" class="inline-block mt-3 bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                    Mulai Pendaftaran
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 px-4 mt-6">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2025 PPDB Pesantren Al-Qur'an Bani Syahid</p>
        </div>
    </footer>

    <style>
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.875rem;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .qr-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) mobileMenu.classList.toggle('hidden');
        });

        // Download all documents as ZIP
        function downloadAllDocuments() {
            Swal.fire({
                title: 'Mempersiapkan File...',
                text: 'Sedang membuat file ZIP dari semua dokumen',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const link = document.createElement('a');
            link.href = `/santri/documents/download-all`;
            link.target = '_blank';

            fetch(`/santri/documents/download-all`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                Swal.close();
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Download gagal');
                    });
                }

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                Swal.fire({
                    icon: 'success',
                    title: 'Download Berhasil',
                    text: 'Semua dokumen berhasil didownload dalam format ZIP',
                    confirmButtonText: 'OK'
                });
            })
            .catch(error => {
                Swal.close();
                console.error('Download all error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Download Gagal',
                    text: error.message || 'Terjadi kesalahan saat mendownload file ZIP',
                    confirmButtonText: 'Mengerti'
                });
            });
        }

        // Barcode Modal Functions
        function showBarcodeModal() {
            const modal = document.getElementById('barcodeModal');
            if (modal) {
                modal.classList.remove('hidden');
                // Refresh barcode image to ensure it's current
                refreshBarcode();
            }
        }

        function closeBarcodeModal() {
            const modal = document.getElementById('barcodeModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function refreshBarcode() {
            const barcodeImage = document.getElementById('barcodeImage');
            const barcodePreview = document.getElementById('barcodePreview');

            if (barcodeImage && '{{ $barcodeUrl }}') {
                // Add timestamp to prevent caching
                const timestamp = new Date().getTime();
                barcodeImage.src = '{{ $barcodeUrl }}' + '?t=' + timestamp;

                // Show loading effect
                barcodeImage.classList.remove('qr-fade-in');
                setTimeout(() => {
                    barcodeImage.classList.add('qr-fade-in');
                }, 100);
            }

            if (barcodePreview && '{{ $barcodeUrl }}') {
                const timestamp = new Date().getTime();
                barcodePreview.src = '{{ $barcodeUrl }}' + '?t=' + timestamp;

                barcodePreview.classList.remove('qr-fade-in');
                setTimeout(() => {
                    barcodePreview.classList.add('qr-fade-in');
                }, 100);
            }

            Swal.fire({
                icon: 'success',
                title: 'QR Code Diperbarui',
                text: 'QR Code berhasil diperbarui',
                timer: 1500,
                showConfirmButton: false
            });
        }

        // Close modal when clicking outside
        document.getElementById('barcodeModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBarcodeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBarcodeModal();
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</div>
@endsection

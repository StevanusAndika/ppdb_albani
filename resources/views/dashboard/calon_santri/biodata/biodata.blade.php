@extends('layouts.app')

@section('title', 'Biodata Pendaftaran - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="{{ route('santri.dashboard') }}" class="text-primary hover:text-secondary font-medium">Dashboard</a>
                <a href="{{ route('santri.settings.index') }}" class="text-primary hover:text-secondary font-medium">Pengaturan</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary font-medium">Dokumen</a>
                <a href="{{ route('santri.payments.index') }}" class="text-primary hover:text-secondary font-medium">Pembayaran</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">Logout</button>
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
                <a href="{{ route('santri.dashboard') }}" class="text-primary">Dashboard</a>
                <a href="{{ route('santri.settings.index') }}" class="text-primary">Pengaturan</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary">Dokumen</a>
                <a href="{{ route('santri.payments.index') }}" class="text-primary">Pembayaran</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-primary text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-6 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-2">Formulir Biodata Pendaftaran</h1>
        <p class="text-secondary max-w-2xl mx-auto">Lengkapi data diri Anda dengan benar untuk proses pendaftaran santri baru Pondok Pesantren Bani Syahid</p>
    </header>

    <main class="max-w-6xl mx-auto py-6 px-4">
        <!-- Status Pendaftaran Info -->
        @if($registration && $registration->status_pendaftaran === 'diterima')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-yellow-600 mr-3 text-xl"></i>
                <div>
                    <h3 class="font-semibold text-yellow-800">Status Pendaftaran: DITERIMA</h3>
                    <p class="text-yellow-700 text-sm mt-1">Biodata Anda sudah terkunci dan tidak dapat diubah karena status pendaftaran sudah DITERIMA.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Alert Messages untuk fallback -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" id="successAlert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" id="errorAlert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>Terjadi kesalahan dalam pengisian form. Silakan periksa kembali data Anda.</span>
            </div>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(!$registration || $registration->status_pendaftaran !== 'diterima')
        <form id="biodataForm" action="{{ route('santri.biodata.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: Paket dan Program Unggulan -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-primary mb-4 border-b pb-2">
                    <i class="fas fa-box mr-2"></i>Paket & Program Unggulan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Paket Pendaftaran -->
                    <div>
                        <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Paket Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <select name="package_id" id="package_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Paket</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}"
                                    {{ old('package_id', $registration->package_id ?? '') == $package->id ? 'selected' : '' }}
                                    data-package="{{ $package->id }}">
                                    {{ $package->name }} - Rp {{ number_format($package->totalAmount, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Program Unggulan -->
                    <div>
                        <label for="program_unggulan_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Program Unggulan <span class="text-red-500">*</span>
                        </label>
                        <select name="program_unggulan_id" id="program_unggulan_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Program Unggulan</option>
                            @foreach($programUnggulan as $index => $program)
                                <option value="{{ $program['nama'] }}"
                                    {{ old('program_unggulan_id', $registration->program_unggulan_id ?? '') == $program['nama'] ? 'selected' : '' }}>
                                    {{ $program['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Detail Harga Paket -->
                <div id="packageDetails" class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-sm hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-blue-800 text-lg">
                            <i class="fas fa-receipt mr-2"></i>Rincian Biaya Paket
                        </h4>
                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-medium">
                            <i class="fas fa-info-circle mr-1"></i>Detail
                        </span>
                    </div>

                    <!-- Price list container -->
                    <div id="priceList" class="space-y-3 mb-4 max-h-60 overflow-y-auto pr-2"></div>

                    <!-- Total section -->
                    <div class="pt-4 border-t border-blue-300 bg-white rounded-lg p-4 shadow-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-900 font-bold text-lg">Total Biaya:</span>
                            <span id="totalAmount" class="text-green-600 font-bold text-xl">Rp 0</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            <i class="fas fa-clock mr-1"></i>Biaya dapat berubah sesuai kebijakan pesantren
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Pribadi -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-primary mb-4 border-b pb-2">
                    <i class="fas fa-user mr-2"></i>Data Pribadi Santri
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required
                            value="{{ old('nama_lengkap', $registration->nama_lengkap ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nik" id="nik" required maxlength="16"
                            value="{{ old('nik', $registration->nik ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="16 digit NIK">
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" required
                            value="{{ old('tempat_lahir', $registration->tempat_lahir ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Kota tempat lahir">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" required
                            value="{{ old('tanggal_lahir', $registration && $registration->tanggal_lahir ? $registration->tanggal_lahir->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="laki-laki" {{ old('jenis_kelamin', $registration->jenis_kelamin ?? '') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin', $registration->jenis_kelamin ?? '') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3: Alamat Tinggal -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-primary mb-4 border-b pb-2">
                    <i class="fas fa-home mr-2"></i>Alamat Tinggal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Alamat Lengkap -->
                    <div class="md:col-span-2 lg:col-span-4">
                        <label for="alamat_tinggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat_tinggal" id="alamat_tinggal" required rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Masukkan alamat lengkap">{{ old('alamat_tinggal', $registration->alamat_tinggal ?? '') }}</textarea>
                    </div>

                    <!-- RT & RW -->
                    <div class="grid grid-cols-2 gap-4 md:col-span-2 lg:col-span-4">
                        <div>
                            <label for="rt" class="block text-sm font-medium text-gray-700 mb-2">
                                RT
                            </label>
                            <input type="text" name="rt" id="rt" maxlength="3"
                                value="{{ old('rt', $registration->rt ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="000">
                        </div>
                        <div>
                            <label for="rw" class="block text-sm font-medium text-gray-700 mb-2">
                                RW
                            </label>
                            <input type="text" name="rw" id="rw" maxlength="3"
                                value="{{ old('rw', $registration->rw ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="000">
                        </div>
                    </div>

                    <!-- Kecamatan, Kelurahan, Kota, Kode Pos -->
                    <div>
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kecamatan" id="kecamatan" required
                            value="{{ old('kecamatan', $registration->kecamatan ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Nama kecamatan">
                    </div>

                    <div>
                        <label for="kelurahan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelurahan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kelurahan" id="kelurahan" required
                            value="{{ old('kelurahan', $registration->kelurahan ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Nama kelurahan">
                    </div>

                    <div>
                        <label for="kota" class="block text-sm font-medium text-gray-700 mb-2">
                            Kota <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kota" id="kota" required
                            value="{{ old('kota', $registration->kota ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Nama kota">
                    </div>

                    <div>
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Pos <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_pos" id="kode_pos" required maxlength="5"
                            value="{{ old('kode_pos', $registration->kode_pos ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="12345">
                    </div>
                </div>
            </div>

            <!-- Section 4: Data Orang Tua -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-primary mb-4 border-b pb-2">
                    <i class="fas fa-users mr-2"></i>Data Orang Tua
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Ibu -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Ibu Kandung</h3>

                        <div>
                            <label for="nama_ibu_kandung" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Ibu Kandung <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_ibu_kandung" id="nama_ibu_kandung" required
                                value="{{ old('nama_ibu_kandung', $registration->nama_ibu_kandung ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Nama lengkap ibu kandung">
                        </div>

                        <div>
                            <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700 mb-2">
                                Pekerjaan Ibu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" required
                                value="{{ old('pekerjaan_ibu', $registration->pekerjaan_ibu ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Pekerjaan ibu (jika ibu rumah tangga, isi 'Ibu Rumah Tangga')">
                        </div>

                        <div>
                            <label for="penghasilan_ibu" class="block text-sm font-medium text-gray-700 mb-2">
                                Penghasilan Ibu
                            </label>
                            <input type="number" name="penghasilan_ibu" id="penghasilan_ibu"
                                value="{{ old('penghasilan_ibu', $registration->penghasilan_ibu ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Isi 0 jika tidak memiliki penghasilan">
                        </div>
                    </div>

                    <!-- Data Ayah -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Ayah Kandung</h3>

                        <div>
                            <label for="nama_ayah_kandung" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Ayah Kandung <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_ayah_kandung" id="nama_ayah_kandung" required
                                value="{{ old('nama_ayah_kandung', $registration->nama_ayah_kandung ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Nama lengkap ayah kandung">
                        </div>

                        <div>
                            <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700 mb-2">
                                Pekerjaan Ayah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" required
                                value="{{ old('pekerjaan_ayah', $registration->pekerjaan_ayah ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Pekerjaan ayah (jika tidak bekerja, isi 'Tidak Bekerja')">
                        </div>

                        <div>
                            <label for="penghasilan_ayah" class="block text-sm font-medium text-gray-700 mb-2">
                                Penghasilan Ayah
                            </label>
                            <input type="number" name="penghasilan_ayah" id="penghasilan_ayah"
                                value="{{ old('penghasilan_ayah', $registration->penghasilan_ayah ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Isi 0 jika tidak memiliki penghasilan">
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan Orang Tua -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div>
                        <label for="nomor_telpon_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon Orang Tua <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomor_telpon_orang_tua" id="nomor_telpon_orang_tua" required
                            value="{{ old('nomor_telpon_orang_tua', $registration->nomor_telpon_orang_tua ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    <div>
                        <label for="agama" class="block text-sm font-medium text-gray-700 mb-2">
                            Agama Calon Santri <span class="text-red-500">*</span>
                        </label>
                        <select name="agama" id="agama" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Agama</option>
                            <option value="islam" {{ old('agama', $registration->agama ?? '') == 'islam' ? 'selected' : '' }}>Islam</option>
                        </select>
                    </div>

                    <div>
                        <label for="status_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Orang Tua <span class="text-red-500">*</span>
                        </label>
                        <select name="status_orang_tua" id="status_orang_tua" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Status</option>
                            <option value="lengkap" {{ old('status_orang_tua', $registration->status_orang_tua ?? '') == 'lengkap' ? 'selected' : '' }}>Lengkap</option>
                            <option value="cerai_hidup" {{ old('status_orang_tua', $registration->status_orang_tua ?? '') == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                            <option value="cerai_mati" {{ old('status_orang_tua', $registration->status_orang_tua ?? '') == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 5: Pendidikan Terakhir -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-primary mb-4 border-b pb-2">
                    <i class="fas fa-graduation-cap mr-2"></i>Pendidikan Terakhir Calon Santri
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Jenjang Pendidikan -->
                    <div>
                        <label for="jenjang_pendidikan_terakhir" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenjang Pendidikan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenjang_pendidikan_terakhir" id="jenjang_pendidikan_terakhir" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Jenjang</option>
                            @foreach($jenjangPendidikan as $jenjang)
                                <option value="{{ $jenjang }}"
                                    {{ old('jenjang_pendidikan_terakhir', $registration->jenjang_pendidikan_terakhir ?? '') == $jenjang ? 'selected' : '' }}>
                                    {{ $jenjang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- NIS/NISN/NSP -->
                    <div>
                        <label for="nis_nisn_nsp" class="block text-sm font-medium text-gray-700 mb-2">
                            NIS/NISN/NSP
                        </label>
                        <input type="text" name="nis_nisn_nsp" id="nis_nisn_nsp"
                            value="{{ old('nis_nisn_nsp', $registration->nis_nisn_nsp ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Nomor induk siswa">
                    </div>

                    <!-- Status Pernikahan -->
                    <div>
                        <label for="status_pernikahan" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Pernikahan <span class="text-red-500">*</span>
                        </label>
                        <select name="status_pernikahan" id="status_pernikahan" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Status</option>
                            <option value="menikah" {{ old('status_pernikahan', $registration->status_pernikahan ?? '') == 'menikah' ? 'selected' : '' }}>Menikah</option>
                            <option value="belum_menikah" {{ old('status_pernikahan', $registration->status_pernikahan ?? '') == 'belum_menikah' ? 'selected' : '' }}>Belum Menikah</option>
                        </select>
                    </div>

                    <!-- Nama Sekolah -->
                    <div class="md:col-span-2">
                        <label for="nama_sekolah_terakhir" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Sekolah Terakhir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_sekolah_terakhir" id="nama_sekolah_terakhir" required
                            value="{{ old('nama_sekolah_terakhir', $registration->nama_sekolah_terakhir ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Nama sekolah terakhir">
                    </div>

                    <!-- Alamat Sekolah -->
                    <div class="md:col-span-2 lg:col-span-3">
                        <label for="alamat_sekolah_terakhir" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Sekolah Terakhir <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat_sekolah_terakhir" id="alamat_sekolah_terakhir" required rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Alamat lengkap sekolah terakhir">{{ old('alamat_sekolah_terakhir', $registration->alamat_sekolah_terakhir ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 6: Data Kesehatan -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-primary mb-4 border-b pb-2">
                    <i class="fas fa-heartbeat mr-2"></i>Data Kesehatan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Golongan Darah -->
                    <div>
                        <label for="golongan_darah" class="block text-sm font-medium text-gray-700 mb-2">
                            Golongan Darah
                        </label>
                        <select name="golongan_darah" id="golongan_darah"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A" {{ old('golongan_darah', $registration->golongan_darah ?? '') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('golongan_darah', $registration->golongan_darah ?? '') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('golongan_darah', $registration->golongan_darah ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('golongan_darah', $registration->golongan_darah ?? '') == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    <!-- Kebangsaan -->
                    <div>
                        <label for="kebangsaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kebangsaan <span class="text-red-500">*</span>
                        </label>
                        <select name="kebangsaan" id="kebangsaan" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Kebangsaan</option>
                            <option value="WNI" {{ old('kebangsaan', $registration->kebangsaan ?? '') == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ old('kebangsaan', $registration->kebangsaan ?? '') == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                    </div>

                    <!-- Alergi Obat -->
                    <div class="md:col-span-2">
                        <label for="alergi_obat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alergi Obat
                        </label>
                        <input type="text" name="alergi_obat" id="alergi_obat"
                            value="{{ old('alergi_obat', $registration->alergi_obat ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Jenis alergi obat (jika ada)">
                    </div>

                    <!-- Penyakit Kronis -->
                    <div class="md:col-span-2 lg:col-span-4">
                        <label for="penyakit_kronis" class="block text-sm font-medium text-gray-700 mb-2">
                            Penyakit Kronis
                        </label>
                        <textarea name="penyakit_kronis" id="penyakit_kronis" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Riwayat penyakit kronis (jika ada)">{{ old('penyakit_kronis', $registration->penyakit_kronis ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 7: Data Wali -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-primary mb-4 border-b pb-2">
                    <i class="fas fa-user-friends mr-2"></i>Data Wali
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Wali -->
                    <div class="space-y-4">
                        <div>
                            <label for="nama_wali" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Wali <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_wali" id="nama_wali" required
                                value="{{ old('nama_wali', $registration->nama_wali ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Nama lengkap wali">
                        </div>

                        <div>
                            <label for="nomor_telpon_wali" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon Wali <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nomor_telpon_wali" id="nomor_telpon_wali" required
                                value="{{ old('nomor_telpon_wali', $registration->nomor_telpon_wali ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <!-- Alamat Wali -->
                    <div class="space-y-4">
                        <div>
                            <label for="alamat_wali" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Wali <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat_wali" id="alamat_wali" required rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Alamat lengkap wali">{{ old('alamat_wali', $registration->alamat_wali ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Detail Alamat Wali -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                    <div class="grid grid-cols-2 gap-4 md:col-span-2 lg:col-span-4">
                        <div>
                            <label for="rt_wali" class="block text-sm font-medium text-gray-700 mb-2">
                                RT Wali
                            </label>
                            <input type="text" name="rt_wali" id="rt_wali" maxlength="3"
                                value="{{ old('rt_wali', $registration->rt_wali ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="000">
                        </div>
                        <div>
                            <label for="rw_wali" class="block text-sm font-medium text-gray-700 mb-2">
                                RW Wali
                            </label>
                            <input type="text" name="rw_wali" id="rw_wali" maxlength="3"
                                value="{{ old('rw_wali', $registration->rw_wali ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="000">
                        </div>
                    </div>

                    <div>
                        <label for="kecamatan_wali" class="block text-sm font-medium text-gray-700 mb-2">
                            Kecamatan Wali <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kecamatan_wali" id="kecamatan_wali" required
                            value="{{ old('kecamatan_wali', $registration->kecamatan_wali ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Nama kecamatan">
                    </div>

                    <div>
                        <label for="kelurahan_wali" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelurahan Wali <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kelurahan_wali" id="kelurahan_wali" required
                            value="{{ old('kelurahan_wali', $registration->kelurahan_wali ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Nama kelurahan">
                    </div>

                    <div>
                        <label for="kota_wali" class="block text-sm font-medium text-gray-700 mb-2">
                            Kota Wali <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kota_wali" id="kota_wali" required
                            value="{{ old('kota_wali', $registration->kota_wali ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="Nama kota">
                    </div>

                    <div>
                        <label for="kode_pos_wali" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Pos Wali <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_pos_wali" id="kode_pos_wali" required maxlength="5"
                            value="{{ old('kode_pos_wali', $registration->kode_pos_wali ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                            placeholder="12345">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <p><span class="text-red-500">*</span> Menandakan field wajib diisi</p>
                        <p>Pastikan semua data yang Anda masukkan sudah benar sebelum menyimpan</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('santri.dashboard') }}"
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300 font-medium">
                            Kembali ke Dashboard
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-primary text-white rounded-lg hover:bg-secondary transition duration-300 font-medium flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Biodata
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @else
        <!-- Tampilan ketika status sudah diterima -->
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <div class="max-w-md mx-auto">
                <div class="text-green-500 text-6xl mb-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Status Pendaftaran: DITERIMA</h2>
                <p class="text-gray-600 mb-6">
                    Selamat! Pendaftaran Anda telah diterima. Biodata sudah terkunci dan tidak dapat diubah.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('santri.dashboard') }}"
                       class="w-full bg-primary text-white py-3 px-6 rounded-lg hover:bg-secondary transition duration-300 font-medium block">
                        Kembali ke Dashboard
                    </a>
                    <a href="{{ route('santri.biodata.show') }}"
                       class="w-full border border-primary text-primary py-3 px-6 rounded-lg hover:bg-primary hover:text-white transition duration-300 font-medium block">
                        Lihat Biodata
                    </a>
                </div>
            </div>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 px-4 mt-8">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2025 PPDB Pesantren Al-Qur'an Bani Syahid</p>
        </div>
    </footer>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript -->
<script>
    // SweetAlert untuk notifikasi
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK',
                timer: 5000,
                timerProgressBar: true
            });
            // Sembunyikan alert biasa
            document.getElementById('errorAlert')?.style.display = 'none';
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                timer: 4000,
                timerProgressBar: true
            });
            // Sembunyikan alert biasa
            document.getElementById('successAlert')?.style.display = 'none';
        @endif

        // Cek jika status sudah diterima
        @if($registration && $registration->status_pendaftaran === 'diterima')
            Swal.fire({
                icon: 'info',
                title: 'Status Diterima',
                text: 'Biodata Anda sudah terkunci dan tidak dapat diubah karena status pendaftaran sudah DITERIMA.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Mengerti'
            });
        @endif
    });

    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Package selection handler
    document.getElementById('package_id')?.addEventListener('change', function() {
        const packageId = this.value;
        const packageDetails = document.getElementById('packageDetails');
        const priceList = document.getElementById('priceList');
        const totalAmount = document.getElementById('totalAmount');

        if (packageId) {
            // Show loading
            packageDetails.classList.remove('hidden');
            priceList.innerHTML = '<div class="text-gray-500 flex items-center justify-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat detail harga...</div>';

            // Fetch package prices
            fetch(`/santri/biodata/package/${packageId}/prices`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        priceList.innerHTML = '';

                        // Check if prices array exists and has items
                        if (data.prices && data.prices.length > 0) {
                            // Tambahkan header
                            const header = document.createElement('div');
                            header.className = 'grid grid-cols-2 gap-4 text-sm font-semibold text-blue-800 border-b border-blue-300 pb-2 mb-3';
                            header.innerHTML = `
                                <span>Item Biaya</span>
                                <span class="text-right">Jumlah</span>
                            `;
                            priceList.appendChild(header);

                            data.prices.forEach(price => {
                                const priceItem = document.createElement('div');
                                priceItem.className = 'grid grid-cols-2 gap-4 text-sm py-3 border-b border-gray-200 last:border-b-0 hover:bg-blue-50 px-2 rounded';

                                // Gunakan item_name dari response
                                const itemName = price.item_name || price.name || 'Biaya';
                                const itemDescription = price.description ?
                                    `<div class="text-xs text-gray-500 mt-1">${price.description}</div>` : '';

                                priceItem.innerHTML = `
                                    <div>
                                        <div class="text-gray-700 font-medium">${itemName}</div>
                                        ${itemDescription}
                                    </div>
                                    <div class="text-right font-semibold text-green-600">${price.formatted_amount}</div>
                                `;
                                priceList.appendChild(priceItem);
                            });
                        } else {
                            priceList.innerHTML = '<div class="text-yellow-500 text-sm text-center py-4"><i class="fas fa-info-circle mr-2"></i>Tidak ada detail harga tersedia</div>';
                        }

                        // Set total amount
                        totalAmount.textContent = data.formatted_total;

                    } else {
                        priceList.innerHTML = '<div class="text-red-500 text-sm text-center py-4"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat detail harga: ' + (data.message || 'Unknown error') + '</div>';
                        totalAmount.textContent = 'Rp 0';
                    }
                })
                .catch(error => {
                    priceList.innerHTML = '<div class="text-red-500 text-sm text-center py-4"><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi kesalahan saat memuat data: ' + error.message + '</div>';
                    totalAmount.textContent = 'Rp 0';
                });
        } else {
            packageDetails.classList.add('hidden');
        }
    });

    // Format number inputs
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toLocaleString('id-ID');
            }
        });

        input.addEventListener('focus', function() {
            if (this.value) {
                this.value = this.value.replace(/\./g, '');
            }
        });
    });

    // NIK validation
    document.getElementById('nik')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });

    // Kode Pos validation
    document.getElementById('kode_pos')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 5);
    });

    document.getElementById('kode_pos_wali')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 5);
    });

    // RT/RW validation
    document.querySelectorAll('input[name="rt"], input[name="rw"], input[name="rt_wali"], input[name="rw_wali"]').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 3);
        });
    });

    // Phone number validation
    document.querySelectorAll('input[name="nomor_telpon_orang_tua"], input[name="nomor_telpon_wali"]').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 15);
        });
    });

    // Form submission handler
    document.getElementById('biodataForm')?.addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Form Tidak Lengkap',
                text: 'Harap lengkapi semua field yang wajib diisi!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
    });

    // Initialize package details if already selected
    const selectedPackage = document.getElementById('package_id');
    if (selectedPackage && selectedPackage.value) {
        selectedPackage.dispatchEvent(new Event('change'));
    }

    // Auto-format tanggal untuk placeholder
    document.getElementById('tanggal_lahir')?.addEventListener('focus', function() {
        if (!this.value) {
            this.type = 'date';
        }
    });

    document.getElementById('tanggal_lahir')?.addEventListener('blur', function() {
        if (!this.value) {
            this.type = 'text';
            this.placeholder = 'dd/mm/yyyy';
        }
    });
</script>

<style>
    .full-width-page {
        width: 100%;
        margin: 0;
        padding: 0;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        ring: 2px;
    }

    .border-red-500 {
        border-color: #ef4444;
    }
</style>
@endsection

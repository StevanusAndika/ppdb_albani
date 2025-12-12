@extends('layouts.app')

@section('title', 'Biodata Pendaftaran - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.calon_santri.navbar')

    <!-- Header -->
    <header class="py-6 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-2">Formulir Biodata Pendaftaran</h1>
        <p class="text-secondary max-w-2xl mx-auto">Lengkapi data diri Anda dengan benar untuk proses pendaftaran santri baru Pondok Pesantren Al-Qur'an Bani Syahid</p>
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
                            @foreach($programUnggulan as $program)
                                <option value="{{ $program->id }}"
                                    {{ old('program_unggulan_id', $registration->program_unggulan_id ?? '') == $program->id ? 'selected' : '' }}>
                                    {{ $program->nama_program }}
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

            <!-- Section 2: Data Pribadi Santri -->
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                            placeholder="16 digit NIK"
                            pattern="[0-9]{16}"
                            title="NIK harus 16 digit angka">
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

                    <!-- Agama (DIPINDAHKAN ke Data Pribadi Santri) -->
                    <div>
                        <label for="agama" class="block text-sm font-medium text-gray-700 mb-2">
                            Agama <span class="text-red-500">*</span>
                        </label>
                        <select name="agama" id="agama" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Agama</option>
                            <option value="islam" {{ old('agama', $registration->agama ?? '') == 'islam' ? 'selected' : '' }}>Islam</option>

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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                                placeholder="000"
                                pattern="[0-9]*"
                                title="RT harus berupa angka">
                        </div>
                        <div>
                            <label for="rw" class="block text-sm font-medium text-gray-700 mb-2">
                                RW
                            </label>
                            <input type="text" name="rw" id="rw" maxlength="3"
                                value="{{ old('rw', $registration->rw ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                                placeholder="000"
                                pattern="[0-9]*"
                                title="RW harus berupa angka">
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                            placeholder="12345"
                            pattern="[0-9]{5}"
                            title="Kode Pos harus 5 digit angka">
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
                            <input type="text" name="penghasilan_ibu" id="penghasilan_ibu"
                                value="{{ old('penghasilan_ibu', $registration->penghasilan_ibu ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 penghasilan-input"
                                placeholder="Contoh: 1.000.000,Jika Tidak bekerja tulis 0"
                                title="Penghasilan dalam rupiah (boleh menggunakan titik pemisah ribuan)">
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
                            <input type="text" name="penghasilan_ayah" id="penghasilan_ayah"
                                value="{{ old('penghasilan_ayah', $registration->penghasilan_ayah ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 penghasilan-input"
                                placeholder="Contoh: 5.000.000,Jika Tidak bekerja tulis 0"
                                title="Penghasilan dalam rupiah (boleh menggunakan titik pemisah ribuan)">
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan Orang Tua - DUA KOLOM SEJAJAR -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Kolom 1: Nomor Telepon Orang Tua -->
                    <div>
                        <label for="nomor_telpon_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon Orang Tua <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomor_telpon_orang_tua" id="nomor_telpon_orang_tua" required
                            value="{{ old('nomor_telpon_orang_tua', $registration->nomor_telpon_orang_tua ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                            placeholder="08xxxxxxxxxx"
                            pattern="[0-9]{10,15}"
                            title="Nomor telepon harus 10-15 digit angka">
                        <p class="text-xs text-gray-500 mt-1">Nomor aktif yang bisa dihubungi</p>
                    </div>

                    <!-- Kolom 2: Status Orang Tua -->
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
                        <p class="text-xs text-gray-500 mt-1">Status pernikahan orang tua</p>
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

                    <!-- Program Pendidikan -->
                    <div>
                        <label for="program_pendidikan" class="block text-sm font-medium text-gray-700 mb-2">
                            Program Pendidikan Yang Dipilih <span class="text-red-500">*</span>
                        </label>
                        <select name="program_pendidikan" id="program_pendidikan" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                            <option value="">Pilih Program</option>
                            @foreach($programPendidikan as $program)
                                <option value="{{ $program }}"
                                    {{ old('program_pendidikan', $registration->program_pendidikan ?? '') == $program ? 'selected' : '' }}
                                    data-min-age="{{ $program === 'Takhassus Al-Quran' ? 17 : 0 }}">
                                    {{ $program }}
                                </option>
                            @endforeach
                        </select>
                        <div id="age-warning" class="text-sm mt-2 hidden">
                            <!-- Pesan peringatan akan muncul di sini -->
                        </div>
                    </div>

                    <!-- NIS/NISN/NSP -->
                    <div>
                        <label for="nis_nisn_nsp" class="block text-sm font-medium text-gray-700 mb-2">
                            NIS/NISN/NSP
                        </label>
                        <input type="text" name="nis_nisn_nsp" id="nis_nisn_nsp"
                            value="{{ old('nis_nisn_nsp', $registration->nis_nisn_nsp ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                            placeholder="Nomor induk siswa"
                            pattern="[0-9]*"
                            title="NIS/NISN/NSP harus berupa angka">
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                                placeholder="08xxxxxxxxxx"
                                pattern="[0-9]{10,15}"
                                title="Nomor telepon harus 10-15 digit angka">
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                                placeholder="000"
                                pattern="[0-9]*"
                                title="RT harus berupa angka">
                        </div>
                        <div>
                            <label for="rw_wali" class="block text-sm font-medium text-gray-700 mb-2">
                                RW Wali
                            </label>
                            <input type="text" name="rw_wali" id="rw_wali" maxlength="3"
                                value="{{ old('rw_wali', $registration->rw_wali ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                                placeholder="000"
                                pattern="[0-9]*"
                                title="RW harus berupa angka">
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 numeric-input"
                            placeholder="12345"
                            pattern="[0-9]{5}"
                            title="Kode Pos harus 5 digit angka">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <p><span class="text-red-500">*</span> Menandakan field wajib diisi</p>
                        <p class="mt-1"><span class="text-blue-500"><b>PETUNJUK PENGISIAN:</b></span> </p>
                        <ul class="mt-2 text-xs text-gray-500 list-disc list-inside">
                            <li>NIK: 16 digit angka</li>
                            <li>RT/RW: maksimal 3 digit angka</li>
                            <li>Kode Pos: 5 digit angka</li>
                            <li>Nomor Telepon: 10-15 digit angka</li>
                            <li>Program Takhassus Al-Quran: minimal usia 17 tahun</li>
                            <li>Agama: Pilih agama sesuai KTP</li>
                            <li>Status Orang Tua: Pilih sesuai kondisi orang tua</li>
                            <li>NIS/NISN/NSP: angka tanpa karakter khusus</li>
                        </ul>
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
    @include('layouts.components.calon_santri.footer')
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

    // FUNGSI UTAMA: Validasi input untuk field tertentu
    function setupNumericInput(inputId, maxLength = null, allowDecimal = false) {
        const input = document.getElementById(inputId);
        if (input) {
            // Event listener untuk input
            input.addEventListener('input', function(e) {
                // Simpan posisi cursor
                const start = this.selectionStart;
                const end = this.selectionEnd;

                // Hapus semua karakter non-digit (dan titik jika allowDecimal)
                let newValue;
                if (allowDecimal) {
                    // Untuk penghasilan: hapus semua kecuali digit dan titik
                    newValue = this.value.replace(/[^\d.]/g, '');
                    // Hanya biarkan satu titik desimal
                    const parts = newValue.split('.');
                    if (parts.length > 2) {
                        newValue = parts[0] + '.' + parts.slice(1).join('');
                    }
                } else {
                    // Untuk field lainnya: hapus semua non-digit
                    newValue = this.value.replace(/\D/g, '');
                }

                // Batasi panjang jika ada maxLength
                if (maxLength && newValue.length > maxLength) {
                    newValue = newValue.substring(0, maxLength);
                }

                // Update nilai
                if (this.value !== newValue) {
                    this.value = newValue;
                    // Kembalikan posisi cursor
                    this.setSelectionRange(start, end);
                }

                // Validasi real-time
                const pattern = this.getAttribute('pattern');
                if (pattern && newValue) {
                    const regex = new RegExp(pattern);
                    if (!regex.test(newValue)) {
                        this.setCustomValidity('Harap masukkan angka yang valid');
                    } else {
                        this.setCustomValidity('');
                    }
                }
            });

            // Format penghasilan dengan titik pemisah ribuan
            if (input.classList.contains('penghasilan-input')) {
                input.addEventListener('blur', function() {
                    if (this.value) {
                        // Hapus semua titik untuk perhitungan
                        const numericValue = this.value.replace(/\./g, '');
                        if (!isNaN(numericValue) && numericValue !== '') {
                            // Format dengan titik pemisah ribuan
                            const formattedValue = parseInt(numericValue).toLocaleString('id-ID');
                            this.value = formattedValue;
                        }
                    }
                });

                input.addEventListener('focus', function() {
                    if (this.value) {
                        // Hapus format untuk editing
                        this.value = this.value.replace(/\./g, '');
                    }
                });

                // Format nilai awal jika ada
                if (input.value) {
                    const numericValue = input.value.replace(/\./g, '');
                    if (!isNaN(numericValue) && numericValue !== '') {
                        input.value = parseInt(numericValue).toLocaleString('id-ID');
                    }
                }
            }

            // Prevent paste karakter non-numeric
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                let filteredText;

                if (allowDecimal) {
                    // Untuk penghasilan: filter hanya digit dan titik
                    filteredText = pastedText.replace(/[^\d.]/g, '');
                    // Hanya biarkan satu titik
                    const parts = filteredText.split('.');
                    if (parts.length > 2) {
                        filteredText = parts[0] + '.' + parts.slice(1).join('');
                    }
                } else {
                    // Untuk field lainnya: filter hanya digit
                    filteredText = pastedText.replace(/\D/g, '');
                }

                const start = this.selectionStart;
                const end = this.selectionEnd;

                // Sisipkan teks yang sudah difilter
                const currentValue = this.value;
                const newValue = currentValue.substring(0, start) + filteredText + currentValue.substring(end);

                // Batasi panjang jika ada maxLength
                if (maxLength && newValue.length > maxLength) {
                    this.value = newValue.substring(0, maxLength);
                } else {
                    this.value = newValue;
                }

                // Posisikan cursor setelah teks yang disisipkan
                const newCursorPos = start + filteredText.length;
                this.setSelectionRange(newCursorPos, newCursorPos);
            });
        }
    }

    // FUNGSI KHUSUS UNTUK PENGHASILAN
    function setupPenghasilanInput(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            // Format otomatis saat input
            input.addEventListener('input', function(e) {
                // Simpan posisi cursor
                const start = this.selectionStart;

                // Hapus semua karakter kecuali angka
                let value = this.value.replace(/[^\d]/g, '');

                // Format dengan titik pemisah ribuan
                if (value.length > 0) {
                    const formattedValue = parseInt(value).toLocaleString('id-ID');
                    this.value = formattedValue;

                    // Hitung posisi cursor baru
                    let newCursorPos = start;
                    const originalLength = value.length;
                    const formattedLength = formattedValue.length;

                    // Adjust cursor position based on formatting
                    if (originalLength > 0 && formattedLength > 0) {
                        // Hitung jumlah titik yang ditambahkan
                        const dotCount = formattedValue.split('.').length - 1;
                        newCursorPos = start + dotCount;
                    }

                    // Set posisi cursor
                    this.setSelectionRange(newCursorPos, newCursorPos);
                }
            });

            // Saat blur, pastikan format konsisten
            input.addEventListener('blur', function() {
                if (this.value) {
                    const value = this.value.replace(/[^\d]/g, '');
                    if (value.length > 0 && !isNaN(value)) {
                        this.value = parseInt(value).toLocaleString('id-ID');
                    }
                }
            });

            // Saat focus, hapus format untuk editing mudah
            input.addEventListener('focus', function() {
                if (this.value) {
                    this.value = this.value.replace(/\./g, '');
                }
            });

            // Format nilai awal jika ada
            if (input.value) {
                const value = input.value.replace(/[^\d]/g, '');
                if (value.length > 0 && !isNaN(value)) {
                    input.value = parseInt(value).toLocaleString('id-ID');
                }
            }

            // Handle paste event
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                const numericText = pastedText.replace(/[^\d]/g, '');

                const start = this.selectionStart;
                const end = this.selectionEnd;
                const currentValue = this.value.replace(/\./g, '');

                // Sisipkan teks
                const newValue = currentValue.substring(0, start) + numericText + currentValue.substring(end);

                // Format dengan titik
                if (newValue.length > 0 && !isNaN(newValue)) {
                    this.value = parseInt(newValue).toLocaleString('id-ID');

                    // Hitung posisi cursor baru
                    const formattedValue = this.value;
                    const dotCount = formattedValue.split('.').length - 1;
                    const newCursorPos = start + numericText.length + dotCount;

                    this.setSelectionRange(newCursorPos, newCursorPos);
                }
            });
        }
    }

    // FUNGSI VALIDASI PROGRAM PENDIDIKAN TAKHASSUS
    function validateTakhassusAge() {
        const programSelect = document.getElementById('program_pendidikan');
        const tanggalLahirInput = document.getElementById('tanggal_lahir');
        const namaLengkapInput = document.getElementById('nama_lengkap');
        const warningDiv = document.getElementById('age-warning');

        // Reset warning
        warningDiv.classList.add('hidden');
        warningDiv.classList.remove('text-red-600', 'text-green-600', 'bg-red-50', 'bg-green-50', 'border-red-200', 'border-green-200');

        if (programSelect.value === 'Takhassus Al-Quran' && tanggalLahirInput.value) {
            const tanggalLahir = new Date(tanggalLahirInput.value);
            const today = new Date();

            let usia = today.getFullYear() - tanggalLahir.getFullYear();
            const monthDiff = today.getMonth() - tanggalLahir.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < tanggalLahir.getDate())) {
                usia--;
            }

            const namaLengkap = namaLengkapInput.value || 'calon santri';

            if (usia < 17) {
                warningDiv.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                        <div>
                            <strong class="text-red-600">Usia tidak memenuhi syarat:</strong><br>
                            Usia calon santri atas nama <strong>${namaLengkap}</strong> belum memenuhi untuk program Pendidikan Takhassus Al-Quran.<br>
                            <span class="text-sm">Usia saat ini: <strong>${usia} tahun</strong> | Minimal: <strong>17 tahun</strong></span>
                        </div>
                    </div>
                `;
                warningDiv.classList.remove('hidden');
                warningDiv.classList.add('text-red-600', 'bg-red-50', 'p-3', 'rounded-lg', 'border', 'border-red-200');

                return false;
            } else {
                warningDiv.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                        <div>
                            <strong class="text-green-600">Usia memenuhi syarat:</strong><br>
                            Usia calon santri atas nama <strong>${namaLengkap}</strong> memenuhi syarat untuk program Takhassus Al-Quran.<br>
                            <span class="text-sm">Usia saat ini: <strong>${usia} tahun</strong></span>
                        </div>
                    </div>
                `;
                warningDiv.classList.remove('hidden');
                warningDiv.classList.add('text-green-600', 'bg-green-50', 'p-3', 'rounded-lg', 'border', 'border-green-200');

                return true;
            }
        }

        return true;
    }

    // Setup semua input saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        // Field yang hanya menerima angka (tanpa titik)
        const strictNumericFields = [
            { id: 'nik', maxLength: 16 },
            { id: 'rt', maxLength: 3 },
            { id: 'rw', maxLength: 3 },
            { id: 'kode_pos', maxLength: 5 },
            { id: 'nomor_telpon_orang_tua', maxLength: 15 },
            { id: 'nis_nisn_nsp' },
            { id: 'nomor_telpon_wali', maxLength: 15 },
            { id: 'rt_wali', maxLength: 3 },
            { id: 'rw_wali', maxLength: 3 },
            { id: 'kode_pos_wali', maxLength: 5 }
        ];

        // Setup field numeric ketat
        strictNumericFields.forEach(field => {
            setupNumericInput(field.id, field.maxLength, false);
        });

        // Setup field penghasilan (boleh pakai titik)
        setupPenghasilanInput('penghasilan_ayah');
        setupPenghasilanInput('penghasilan_ibu');

        // Event listeners untuk validasi program pendidikan
        document.getElementById('program_pendidikan')?.addEventListener('change', validateTakhassusAge);
        document.getElementById('tanggal_lahir')?.addEventListener('change', validateTakhassusAge);
        document.getElementById('nama_lengkap')?.addEventListener('input', validateTakhassusAge);

        // Validasi form submission
        document.getElementById('biodataForm')?.addEventListener('submit', function(e) {
            let isValid = true;
            const errorMessages = [];
            const errorFields = [];

            // Validasi NIK (16 digit)
            const nik = document.getElementById('nik');
            if (nik && nik.value.length !== 16 && nik.value.length > 0) {
                isValid = false;
                nik.classList.add('border-red-500');
                errorMessages.push('NIK harus 16 digit angka');
                errorFields.push(nik);
            }

            // Validasi Kode Pos (5 digit)
            const kodePos = document.getElementById('kode_pos');
            if (kodePos && kodePos.value.length !== 5 && kodePos.value.length > 0) {
                isValid = false;
                kodePos.classList.add('border-red-500');
                errorMessages.push('Kode Pos harus 5 digit angka');
                errorFields.push(kodePos);
            }

            // Validasi Kode Pos Wali (5 digit)
            const kodePosWali = document.getElementById('kode_pos_wali');
            if (kodePosWali && kodePosWali.value.length !== 5 && kodePosWali.value.length > 0) {
                isValid = false;
                kodePosWali.classList.add('border-red-500');
                errorMessages.push('Kode Pos Wali harus 5 digit angka');
                errorFields.push(kodePosWali);
            }

            // Validasi Nomor Telepon (minimal 10 digit)
            const teleponFields = [
                { id: 'nomor_telpon_orang_tua', name: 'Nomor Telepon Orang Tua' },
                { id: 'nomor_telpon_wali', name: 'Nomor Telepon Wali' }
            ];

            teleponFields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input && input.value && input.value.length < 10) {
                    isValid = false;
                    input.classList.add('border-red-500');
                    errorMessages.push(`${field.name} minimal 10 digit`);
                    errorFields.push(input);
                }
            });

            // Validasi RT/RW (jika diisi, harus angka dan maksimal 3 digit)
            const rtRwFields = [
                { id: 'rt', name: 'RT' },
                { id: 'rw', name: 'RW' },
                { id: 'rt_wali', name: 'RT Wali' },
                { id: 'rw_wali', name: 'RW Wali' }
            ];

            rtRwFields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input && input.value) {
                    const numericValue = input.value.replace(/\D/g, '');
                    if (numericValue !== input.value || numericValue.length > 3) {
                        isValid = false;
                        input.classList.add('border-red-500');
                        errorMessages.push(`${field.name} maksimal 3 digit angka`);
                        errorFields.push(input);
                    }
                }
            });

            // Validasi Penghasilan (jika diisi, harus angka)
            const penghasilanFields = [
                { id: 'penghasilan_ayah', name: 'Penghasilan Ayah' },
                { id: 'penghasilan_ibu', name: 'Penghasilan Ibu' }
            ];

            penghasilanFields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input && input.value) {
                    const numericValue = input.value.replace(/\./g, '');
                    if (isNaN(numericValue) || numericValue === '') {
                        isValid = false;
                        input.classList.add('border-red-500');
                        errorMessages.push(`${field.name} harus berupa angka`);
                        errorFields.push(input);
                    }
                }
            });

            // Validasi Program Pendidikan Takhassus
            const programSelect = document.getElementById('program_pendidikan');
            const tanggalLahirInput = document.getElementById('tanggal_lahir');

            if (programSelect && programSelect.value === 'Takhassus Al-Quran' && tanggalLahirInput && tanggalLahirInput.value) {
                const tanggalLahir = new Date(tanggalLahirInput.value);
                const today = new Date();

                let usia = today.getFullYear() - tanggalLahir.getFullYear();
                const monthDiff = today.getMonth() - tanggalLahir.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < tanggalLahir.getDate())) {
                    usia--;
                }

                if (usia < 17) {
                    isValid = false;
                    programSelect.classList.add('border-red-500');
                    tanggalLahirInput.classList.add('border-red-500');

                    const namaLengkap = document.getElementById('nama_lengkap')?.value || 'calon santri';
                    errorMessages.push(`Program Takhassus Al-Quran: Usia ${namaLengkap} (${usia} tahun) belum memenuhi syarat (minimal 17 tahun)`);
                    errorFields.push(programSelect);
                }
            }

            // Validasi Agama
            const agamaSelect = document.getElementById('agama');
            if (agamaSelect && !agamaSelect.value) {
                isValid = false;
                agamaSelect.classList.add('border-red-500');
                errorMessages.push('Agama harus dipilih');
                errorFields.push(agamaSelect);
            }

            if (!isValid) {
                e.preventDefault();
                let errorMessage = 'Terdapat kesalahan dalam pengisian form:<br><br>';
                errorMessages.forEach((msg, index) => {
                    errorMessage += `${index + 1}. ${msg}<br>`;
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: errorMessage,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Perbaiki'
                }).then(() => {
                    // Fokus ke field pertama yang error
                    if (errorFields.length > 0) {
                        errorFields[0].focus();
                    }
                });
            } else {
                // Sebelum submit, format ulang penghasilan tanpa titik untuk backend
                penghasilanFields.forEach(field => {
                    const input = document.getElementById(field.id);
                    if (input && input.value) {
                        input.value = input.value.replace(/\./g, '');
                    }
                });

                // Tampilkan konfirmasi untuk Takhassus
                if (programSelect && programSelect.value === 'Takhassus Al-Quran') {
                    const tanggalLahir = new Date(tanggalLahirInput.value);
                    const today = new Date();

                    let usia = today.getFullYear() - tanggalLahir.getFullYear();
                    const monthDiff = today.getMonth() - tanggalLahir.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < tanggalLahir.getDate())) {
                        usia--;
                    }

                    const namaLengkap = document.getElementById('nama_lengkap')?.value || 'calon santri';

                    e.preventDefault(); // Mencegah submit langsung

                    Swal.fire({
                        icon: 'info',
                        title: 'Konfirmasi Program Takhassus',
                        html: `
                            <div class="text-left">
                                <p>Anda memilih program <strong>Takhassus Al-Quran</strong>.</p>
                                <p class="mt-2">Nama: <strong>${namaLengkap}</strong></p>
                                <p>Usia: <strong>${usia} tahun</strong> (memenuhi syarat minimal 17 tahun)</p>
                                <p class="mt-2 text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Program ini khusus untuk tahfizh dan pendalaman Al-Quran
                                </p>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Periksa Kembali',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form setelah konfirmasi
                            document.getElementById('biodataForm').submit();
                        }
                    });
                }
            }
        });

        // Hapus error styling saat user mulai mengetik
        document.querySelectorAll('.numeric-input, .penghasilan-input, select').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('border-red-500');
            });
            input.addEventListener('change', function() {
                this.classList.remove('border-red-500');
            });
        });

        // Format nilai penghasilan yang sudah ada saat load
        document.querySelectorAll('.penghasilan-input').forEach(input => {
            if (input.value) {
                const value = input.value.replace(/[^\d]/g, '');
                if (value.length > 0 && !isNaN(value)) {
                    input.value = parseInt(value).toLocaleString('id-ID');
                }
            }
        });

        // Jalankan validasi awal
        validateTakhassusAge();
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

    // Informasi program pendidikan saat hover
    const programInfo = {
        'MTS Bani Syahid': 'Madrasah Tsanawiyah untuk pendidikan menengah pertama (usia 12-15 tahun)',
        'MA Bani Syahid': 'Madrasah Aliyah untuk pendidikan menengah atas (usia 15-18 tahun)',
        'Takhassus Al-Quran': 'Program khusus tahfizh dan pendalaman Al-Quran (minimal usia 17 tahun)'
    };

    // Tooltip untuk program pendidikan
    document.getElementById('program_pendidikan')?.addEventListener('mouseenter', function(e) {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.value && programInfo[selectedOption.value]) {
            // Hapus tooltip lama jika ada
            const oldTooltip = document.getElementById('program-tooltip');
            if (oldTooltip) oldTooltip.remove();

            // Buat tooltip baru
            const tooltip = document.createElement('div');
            tooltip.id = 'program-tooltip';
            tooltip.className = 'absolute z-50 bg-gray-900 text-white text-sm px-3 py-2 rounded-lg shadow-lg max-w-xs';
            tooltip.textContent = programInfo[selectedOption.value];

            // Posisi tooltip
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top + rect.height + 5) + 'px';
            tooltip.style.left = rect.left + 'px';

            document.body.appendChild(tooltip);
        }
    });

    document.getElementById('program_pendidikan')?.addEventListener('mouseleave', function() {
        const tooltip = document.getElementById('program-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    });

    document.getElementById('program_pendidikan')?.addEventListener('change', function() {
        const tooltip = document.getElementById('program-tooltip');
        if (tooltip) {
            tooltip.remove();
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
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 1px #ef4444;
    }

    .numeric-input:invalid {
        border-color: #f87171;
    }

    .numeric-input:valid {
        border-color: #34d399;
    }

    /* Style khusus untuk input angka */
    .numeric-input {
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }

    .penghasilan-input {
        text-align: right;
        font-weight: 600;
        color: #059669;
        font-family: 'Arial', sans-serif;
    }

    .penghasilan-input::placeholder {
        color: #9ca3af;
        font-weight: normal;
        font-style: italic;
    }

    /* Animasi untuk error */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .border-red-500 {
        animation: shake 0.5s ease-in-out;
    }

    /* Tooltip untuk error messages */
    .numeric-input:invalid:focus,
    .penghasilan-input:invalid:focus,
    select:invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }

    /* Style khusus untuk input penghasilan */
    .penghasilan-input {
        background-color: #f0fdf4;
        border-color: #86efac;
    }

    .penghasilan-input:focus {
        background-color: #dcfce7;
        border-color: #4ade80;
    }

    /* Style untuk warning message */
    #age-warning {
        transition: all 0.3s ease;
    }

    /* Style untuk select option dengan warna berbeda */
    select option[value="Takhassus Al-Quran"] {
        background-color: #fef3c7;
        font-weight: 600;
    }

    select option[value="MTS Bani Syahid"] {
        background-color: #dbeafe;
    }

    select option[value="MA Bani Syahid"] {
        background-color: #e0e7ff;
    }

    /* Highlight untuk program Takhassus */
    .takhassus-highlight {
        border-left: 4px solid #f59e0b;
        background-color: #fffbeb;
    }

    /* Grid layout untuk kolom sejajar */
    .grid-cols-2 > div {
        display: flex;
        flex-direction: column;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .grid-cols-1 {
            grid-template-columns: 1fr;
        }

        .grid-cols-2 {
            grid-template-columns: 1fr;
        }

        .grid-cols-3 {
            grid-template-columns: 1fr;
        }

        .grid-cols-4 {
            grid-template-columns: 1fr;
        }

        .md\\:col-span-2 {
            grid-column: span 1;
        }

        .lg\\:col-span-3 {
            grid-column: span 1;
        }

        .lg\\:col-span-4 {
            grid-column: span 1;
        }

        /* Untuk kolom sejajar di mobile */
        .sejajar-container {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    /* Style untuk keterangan kecil */
    .text-xs.text-gray-500 {
        font-size: 0.75rem;
        line-height: 1rem;
        margin-top: 0.25rem;
    }

    /* Tooltip styling */
    #program-tooltip {
        font-size: 0.875rem;
        line-height: 1.25rem;
        pointer-events: none;
        animation: fadeIn 0.2s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Style untuk section border */
    .border-b.pb-2 {
        border-bottom-width: 2px;
        border-bottom-color: #3b82f6;
    }

    /* Style untuk required asterisk */
    .text-red-500 {
        color: #ef4444;
        font-weight: bold;
    }
</style>
@endsection

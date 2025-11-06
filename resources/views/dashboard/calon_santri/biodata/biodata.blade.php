@extends('layouts.app')

@section('title', 'Formulir Biodata Santri - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .card-container {
        @apply bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100;
    }
    .section-title {
        @apply text-xl font-bold text-primary mb-4 pb-3 border-b-2 border-primary/20 flex items-center gap-3;
    }
    .subsection-title {
        @apply text-lg font-semibold text-primary mb-3 flex items-center gap-2;
    }
    .form-grid {
        @apply grid grid-cols-1 md:grid-cols-2 gap-4;
    }
    .form-group {
        @apply space-y-2;
    }
    .form-label {
        @apply block text-sm font-semibold text-gray-700;
    }
    .form-label-required:after {
        content: " *";
        @apply text-red-500;
    }
    .form-input {
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 bg-white;
    }
    .form-select {
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 bg-white;
    }
    .form-textarea {
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 resize-none bg-white;
    }
    .package-card {
        @apply border-2 border-gray-200 rounded-xl p-5 cursor-pointer transition-all duration-300 hover:border-primary hover:shadow-lg bg-white;
    }
    .package-card.selected {
        @apply border-primary bg-primary/5 shadow-lg;
    }
    .price-item {
        @apply flex justify-between py-2 border-b border-gray-100 text-sm;
    }
    .price-total {
        @apply flex justify-between text-base font-bold mt-3 pt-3 border-t-2 border-primary/30;
    }
    .btn-primary {
        @apply bg-primary text-white px-6 py-3 rounded-xl hover:bg-secondary transition duration-300 font-semibold flex items-center justify-center gap-2;
    }
    .btn-secondary {
        @apply bg-gray-500 text-white px-6 py-3 rounded-xl hover:bg-gray-600 transition duration-300 font-semibold flex items-center justify-center gap-2;
    }
    .info-card {
        @apply bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6;
    }
    .error-message {
        @apply text-red-500 text-sm mt-1 flex items-center gap-1;
    }
    .step-indicator {
        @apply flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold;
    }
    .progress-bar {
        @apply w-full bg-gray-200 rounded-full h-2;
    }
    .progress-fill {
        @apply bg-primary h-2 rounded-full transition-all duration-500;
    }
    .step-number {
        @apply w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="#profile" class="text-primary hover:text-secondary font-medium">Profil</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary font-medium">Dokumen</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600  text-white px-4 py-1.5 rounded-full hover:bg-secondary transition duration-300">Logout</button>
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
                <a href="{{ route('santri.biodata.index') }}" class="text-primary">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary">Dokumen</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-primary text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header Hero -->
    <header class="py-6 px-4 text-center">
        <h1 class="text-2xl md:text-3xl font-extrabold text-primary mb-2">Formulir Biodata Santri</h1>
        <p class="text-secondary">Lengkapi data diri Anda dengan benar dan lengkap</p>

        <!-- Progress Steps -->
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md mt-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
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
                <div class="p-4 border-2 border-primary bg-primary/5 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="step-number bg-primary text-white">2</div>
                        <div>
                            <h4 class="font-semibold text-primary">Isi Biodata</h4>
                            <p class="text-sm text-primary">Sedang Berlangsung</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Upload Dokumen -->
                <div class="p-4 border-2 border-gray-300 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="step-number bg-gray-300 text-gray-600">3</div>
                        <div>
                            <h4 class="font-semibold text-gray-600">Upload Dokumen</h4>
                            <p class="text-sm text-gray-500">Menunggu</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Verifikasi -->
                <div class="p-4 border-2 border-gray-300 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="step-number bg-gray-300 text-gray-600">4</div>
                        <div>
                            <h4 class="font-semibold text-gray-600">Verifikasi</h4>
                            <p class="text-sm text-gray-500">Menunggu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Progress Keseluruhan</span>
                    <span id="overallProgress">25%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-primary h-3 rounded-full transition-all duration-300" style="width: 25%"></div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto py-6 px-4">
        @if($errors->any())
        <div class="info-card mb-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-blue-500 text-lg mt-0.5 mr-3"></i>
                <div>
                    <p class="font-semibold text-blue-800">Perhatian!</p>
                    <p class="text-blue-600">Terdapat kesalahan dalam pengisian form. Silakan periksa kembali data Anda.</p>
                </div>
            </div>
        </div>
        @endif

        <form id="biodataForm" method="POST" action="{{ route('santri.biodata.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Section 1: Pilihan Paket -->
            <div class="card-container">
                <h2 class="section-title">
                    <i class="fas fa-box text-primary"></i>
                    Pilihan Paket
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @foreach($packages as $package)
                    <div class="package-card {{ (old('package_id') == $package->id || ($registration && $registration->package_id == $package->id)) ? 'selected' : '' }}"
                         onclick="selectPackage({{ $package->id }})">
                        <div class="flex items-start">
                            <input type="radio" name="package_id" value="{{ $package->id }}"
                                   id="package_{{ $package->id }}"
                                   {{ (old('package_id') == $package->id || ($registration && $registration->package_id == $package->id)) ? 'checked' : '' }}
                                   class="mt-1 mr-3">
                            <div class="flex-1">
                                <label for="package_{{ $package->id }}" class="text-lg font-bold text-primary cursor-pointer block mb-2">
                                    {{ $package->name }}
                                </label>
                                <p class="text-gray-600 text-sm mb-3">{{ $package->description }}</p>
                                <span class="inline-block px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-medium">
                                    {{ $package->type_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('package_id')
                    <p class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                @enderror

                <!-- Detail Biaya -->
                <div id="priceDetails" class="bg-gray-50 rounded-xl p-4 {{ ($registration && $registration->package) ? '' : 'hidden' }}">
                    <h3 class="subsection-title">
                        <i class="fas fa-receipt text-primary"></i>
                        Detail Biaya
                    </h3>
                    <div id="priceList" class="space-y-1">
                        @if($registration && $registration->package)
                            @foreach($registration->package->activePrices as $price)
                            <div class="price-item">
                                <span class="text-gray-700">{{ $price->item_name }}</span>
                                <span class="font-semibold text-primary">Rp {{ number_format($price->amount, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <div id="priceTotal" class="price-total">
                        <span class="text-gray-800">Total Biaya</span>
                        <span class="text-primary">
                            @if($registration && $registration->package)
                                Rp {{ number_format($registration->package->total_amount, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Pribadi Santri -->
            <div class="card-container">
                <h2 class="section-title">
                    <i class="fas fa-user text-primary"></i>
                    Data Pribadi Santri
                </h2>

                <div class="form-grid">
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $registration->nama_lengkap ?? '') }}"
                               class="form-input" placeholder="Masukkan nama lengkap" required>
                        @error('nama_lengkap')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div class="form-group">
                        <label class="form-label form-label-required">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $registration->nik ?? '') }}"
                               class="form-input" placeholder="Masukkan 16 digit NIK" maxlength="16" required>
                        @error('nik')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $registration->tempat_lahir ?? '') }}"
                               class="form-input" placeholder="Kota tempat lahir" required>
                        @error('tempat_lahir')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $registration->tanggal_lahir ?? '') }}"
                               class="form-input" required>
                        @error('tanggal_lahir')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="laki-laki" {{ old('jenis_kelamin', $registration->jenis_kelamin ?? '') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin', $registration->jenis_kelamin ?? '') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Agama -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Agama</label>
                        <select name="agama" class="form-select" required>
                            <option value="">Pilih Agama</option>
                            <option value="islam" {{ old('agama', $registration->agama ?? '') == 'islam' ? 'selected' : '' }}>Islam</option>
                            <option value="kristen" {{ old('agama', $registration->agama ?? '') == 'kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="katolik" {{ old('agama', $registration->agama ?? '') == 'katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="hindu" {{ old('agama', $registration->agama ?? '') == 'hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="buddha" {{ old('agama', $registration->agama ?? '') == 'buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="konghucu" {{ old('agama', $registration->agama ?? '') == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status Pernikahan -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Status Pernikahan</label>
                        <select name="status_pernikahan" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="belum_menikah" {{ old('status_pernikahan', $registration->status_pernikahan ?? '') == 'belum_menikah' ? 'selected' : '' }}>Belum Menikah</option>
                            <option value="menikah" {{ old('status_pernikahan', $registration->status_pernikahan ?? '') == 'menikah' ? 'selected' : '' }}>Menikah</option>
                        </select>
                        @error('status_pernikahan')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kebangsaan -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kebangsaan</label>
                        <select name="kebangsaan" class="form-select" required>
                            <option value="">Pilih Kebangsaan</option>
                            <option value="WNI" {{ old('kebangsaan', $registration->kebangsaan ?? '') == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ old('kebangsaan', $registration->kebangsaan ?? '') == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                        @error('kebangsaan')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Golongan Darah -->
                    <div class="form-group">
                        <label class="form-label">Golongan Darah</label>
                        <select name="golongan_darah" class="form-select">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A" {{ old('golongan_darah', $registration->golongan_darah ?? '') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('golongan_darah', $registration->golongan_darah ?? '') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('golongan_darah', $registration->golongan_darah ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('golongan_darah', $registration->golongan_darah ?? '') == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                        @error('golongan_darah')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- NIS/NISN/NSP -->
                    <div class="form-group">
                        <label class="form-label">NIS/NISN/NSP</label>
                        <input type="text" name="nis_nisn_nsp" value="{{ old('nis_nisn_nsp', $registration->nis_nisn_nsp ?? '') }}"
                               class="form-input" placeholder="Nomor Induk Siswa">
                        @error('nis_nisn_nsp')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Jenjang Pendidikan Terakhir -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Jenjang Pendidikan Terakhir</label>
                        <input type="text" name="jenjang_pendidikan_terakhir" value="{{ old('jenjang_pendidikan_terakhir', $registration->jenjang_pendidikan_terakhir ?? '') }}"
                               class="form-input" placeholder="Contoh: SMA, SMP, SD" required>
                        @error('jenjang_pendidikan_terakhir')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nama Sekolah Terakhir -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Sekolah Terakhir</label>
                        <input type="text" name="nama_sekolah_terakhir" value="{{ old('nama_sekolah_terakhir', $registration->nama_sekolah_terakhir ?? '') }}"
                               class="form-input" placeholder="Nama sekolah terakhir" required>
                        @error('nama_sekolah_terakhir')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat Sekolah Terakhir -->
                <div class="form-group mt-4">
                    <label class="form-label form-label-required">Alamat Sekolah Terakhir</label>
                    <textarea name="alamat_sekolah_terakhir" rows="2" class="form-textarea"
                              placeholder="Alamat lengkap sekolah terakhir" required>{{ old('alamat_sekolah_terakhir', $registration->alamat_sekolah_terakhir ?? '') }}</textarea>
                    @error('alamat_sekolah_terakhir')
                        <p class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Section 3: Alamat Tinggal -->
            <div class="card-container">
                <h2 class="section-title">
                    <i class="fas fa-home text-primary"></i>
                    Alamat Tinggal
                </h2>

                <div class="form-group mb-4">
                    <label class="form-label form-label-required">Alamat Tinggal</label>
                    <textarea name="alamat_tinggal" rows="3" class="form-textarea"
                              placeholder="Alamat lengkap tempat tinggal" required>{{ old('alamat_tinggal', $registration->alamat_tinggal ?? '') }}</textarea>
                    @error('alamat_tinggal')
                        <p class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-grid">
                    <!-- RT -->
                    <div class="form-group">
                        <label class="form-label form-label-required">RT</label>
                        <input type="text" name="rt" value="{{ old('rt', $registration->rt ?? '') }}"
                               class="form-input" placeholder="001" maxlength="3" required>
                        @error('rt')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- RW -->
                    <div class="form-group">
                        <label class="form-label form-label-required">RW</label>
                        <input type="text" name="rw" value="{{ old('rw', $registration->rw ?? '') }}"
                               class="form-input" placeholder="002" maxlength="3" required>
                        @error('rw')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kelurahan -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kelurahan</label>
                        <input type="text" name="kelurahan" value="{{ old('kelurahan', $registration->kelurahan ?? '') }}"
                               class="form-input" placeholder="Nama kelurahan" required>
                        @error('kelurahan')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kecamatan -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $registration->kecamatan ?? '') }}"
                               class="form-input" placeholder="Nama kecamatan" required>
                        @error('kecamatan')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kota -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kota</label>
                        <input type="text" name="kota" value="{{ old('kota', $registration->kota ?? '') }}"
                               class="form-input" placeholder="Nama kota" required>
                        @error('kota')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kode Pos -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $registration->kode_pos ?? '') }}"
                               class="form-input" placeholder="12345" maxlength="5" required>
                        @error('kode_pos')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Data Orang Tua -->
            <div class="card-container">
                <h2 class="section-title">
                    <i class="fas fa-users text-primary"></i>
                    Data Orang Tua
                </h2>

                <div class="form-grid">
                    <!-- Nama Ayah Kandung -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Ayah Kandung</label>
                        <input type="text" name="nama_ayah_kandung" value="{{ old('nama_ayah_kandung', $registration->nama_ayah_kandung ?? '') }}"
                               class="form-input" placeholder="Nama lengkap ayah" required>
                        @error('nama_ayah_kandung')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nama Ibu Kandung -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Ibu Kandung</label>
                        <input type="text" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung', $registration->nama_ibu_kandung ?? '') }}"
                               class="form-input" placeholder="Nama lengkap ibu" required>
                        @error('nama_ibu_kandung')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Pekerjaan Ayah -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $registration->pekerjaan_ayah ?? '') }}"
                               class="form-input" placeholder="Pekerjaan ayah" required>
                        @error('pekerjaan_ayah')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Pekerjaan Ibu -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $registration->pekerjaan_ibu ?? '') }}"
                               class="form-input" placeholder="Pekerjaan ibu" required>
                        @error('pekerjaan_ibu')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Penghasilan Ayah -->
                    <div class="form-group">
                        <label class="form-label">Penghasilan Ayah (Rp)</label>
                        <input type="number" name="penghasilan_ayah" value="{{ old('penghasilan_ayah', $registration->penghasilan_ayah ?? '') }}"
                               class="form-input" placeholder="0" min="0" step="1000">
                        @error('penghasilan_ayah')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Penghasilan Ibu -->
                    <div class="form-group">
                        <label class="form-label">Penghasilan Ibu (Rp)</label>
                        <input type="number" name="penghasilan_ibu" value="{{ old('penghasilan_ibu', $registration->penghasilan_ibu ?? '') }}"
                               class="form-input" placeholder="0" min="0" step="1000">
                        @error('penghasilan_ibu')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status Orang Tua -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Status Orang Tua</label>
                        <select name="status_orang_tua" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="lengkap" {{ old('status_orang_tua', $registration->status_orang_tua ?? '') == 'lengkap' ? 'selected' : '' }}>Lengkap</option>
                            <option value="cerai_hidup" {{ old('status_orang_tua', $registration->status_orang_tua ?? '') == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                            <option value="cerai_mati" {{ old('status_orang_tua', $registration->status_orang_tua ?? '') == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                        </select>
                        @error('status_orang_tua')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon Orang Tua -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nomor Telepon Orang Tua</label>
                        <input type="text" name="nomor_telpon_orang_tua" value="{{ old('nomor_telpon_orang_tua', $registration->nomor_telpon_orang_tua ?? '') }}"
                               class="form-input" placeholder="081234567890" required>
                        @error('nomor_telpon_orang_tua')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 5: Data Kesehatan -->
            <div class="card-container">
                <h2 class="section-title">
                    <i class="fas fa-heartbeat text-primary"></i>
                    Data Kesehatan
                </h2>

                <div class="form-grid">
                    <!-- Alergi Obat -->
                    <div class="form-group">
                        <label class="form-label">Alergi Obat</label>
                        <input type="text" name="alergi_obat" value="{{ old('alergi_obat', $registration->alergi_obat ?? '') }}"
                               class="form-input" placeholder="Jenis alergi obat (jika ada)">
                        @error('alergi_obat')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Penyakit Kronis -->
                    <div class="form-group">
                        <label class="form-label">Penyakit Kronis</label>
                        <input type="text" name="penyakit_kronis" value="{{ old('penyakit_kronis', $registration->penyakit_kronis ?? '') }}"
                               class="form-input" placeholder="Penyakit kronis (jika ada)">
                        @error('penyakit_kronis')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 6: Data Wali -->
            <div class="card-container">
                <h2 class="section-title">
                    <i class="fas fa-user-shield text-primary"></i>
                    Data Wali
                </h2>

                <div class="form-grid">
                    <!-- Nama Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Wali</label>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali', $registration->nama_wali ?? '') }}"
                               class="form-input" placeholder="Nama lengkap wali" required>
                        @error('nama_wali')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nomor Telepon Wali</label>
                        <input type="text" name="nomor_telpon_wali" value="{{ old('nomor_telpon_wali', $registration->nomor_telpon_wali ?? '') }}"
                               class="form-input" placeholder="081234567890" required>
                        @error('nomor_telpon_wali')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat Wali -->
                <div class="form-group mb-4">
                    <label class="form-label form-label-required">Alamat Wali</label>
                    <textarea name="alamat_wali" rows="3" class="form-textarea"
                              placeholder="Alamat lengkap wali" required>{{ old('alamat_wali', $registration->alamat_wali ?? '') }}</textarea>
                    @error('alamat_wali')
                        <p class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-grid">
                    <!-- RT Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">RT Wali</label>
                        <input type="text" name="rt_wali" value="{{ old('rt_wali', $registration->rt_wali ?? '') }}"
                               class="form-input" placeholder="001" maxlength="3" required>
                        @error('rt_wali')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- RW Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">RW Wali</label>
                        <input type="text" name="rw_wali" value="{{ old('rw_wali', $registration->rw_wali ?? '') }}"
                               class="form-input" placeholder="002" maxlength="3" required>
                        @error('rw_wali')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kelurahan Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kelurahan Wali</label>
                        <input type="text" name="kelurahan_wali" value="{{ old('kelurahan_wali', $registration->kelurahan_wali ?? '') }}"
                               class="form-input" placeholder="Nama kelurahan" required>
                        @error('kelurahan_wali')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kecamatan Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kecamatan Wali</label>
                        <input type="text" name="kecamatan_wali" value="{{ old('kecamatan_wali', $registration->kecamatan_wali ?? '') }}"
                               class="form-input" placeholder="Nama kecamatan" required>
                        @error('kecamatan_wali')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kota Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kota Wali</label>
                        <input type="text" name="kota_wali" value="{{ old('kota_wali', $registration->kota_wali ?? '') }}"
                               class="form-input" placeholder="Nama kota" required>
                        @error('kota_wali')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kode Pos Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kode Pos Wali</label>
                        <input type="text" name="kode_pos_wali" value="{{ old('kode_pos_wali', $registration->kode_pos_wali ?? '') }}"
                               class="form-input" placeholder="12345" maxlength="5" required>
                        @error('kode_pos_wali')
                            <p class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="card-container">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t-2 border-gray-200">
                    <button type="button" onclick="window.history.back()" class="btn-secondary w-full sm:w-auto">
                        <i class="fas fa-arrow-left"></i>Kembali ke Dashboard
                    </button>
                    <button type="submit" class="btn-primary w-full sm:w-auto">
                        <i class="fas fa-save"></i>{{ $registration ? 'Perbarui Biodata' : 'Simpan Biodata' }}
                    </button>
                </div>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-6 px-4 mt-8">
        <div class="max-w-6xl mx-auto text-center">
            <p>&copy; 2025 PPDB Pesantren AI-Our'an Bani Syahid</p>
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script>
    function selectPackage(packageId) {
        // Update radio button
        document.querySelector(`input[value="${packageId}"]`).checked = true;

        // Update card styles
        document.querySelectorAll('.package-card').forEach(card => {
            card.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');

        // Load package prices
        loadPackagePrices(packageId);
    }

    function loadPackagePrices(packageId) {
        fetch(`/santri/biodata/package/${packageId}/prices`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const priceList = document.getElementById('priceList');
                    const priceTotal = document.getElementById('priceTotal');
                    const priceDetails = document.getElementById('priceDetails');

                    priceList.innerHTML = '';
                    data.prices.forEach(price => {
                        const priceItem = document.createElement('div');
                        priceItem.className = 'price-item';
                        priceItem.innerHTML = `
                            <span class="text-gray-700">${price.item_name}</span>
                            <span class="font-semibold text-primary">Rp ${new Intl.NumberFormat('id-ID').format(price.amount)}</span>
                        `;
                        priceList.appendChild(priceItem);
                    });

                    priceTotal.innerHTML = `
                        <span class="text-gray-800">Total Biaya</span>
                        <span class="text-primary">${data.formatted_total}</span>
                    `;

                    priceDetails.classList.remove('hidden');
                }
            })
            .catch(error => {
                // Handle error silently
            });
    }

    // Form submission handling
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) mobileMenu.classList.toggle('hidden');
        });

        // Form submission
        document.getElementById('biodataForm').addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            submitButton.disabled = true;
        });

        // Auto-select package if only one exists
        const packages = document.querySelectorAll('input[name="package_id"]');
        if (packages.length === 1) {
            packages[0].checked = true;
            const packageCard = packages[0].closest('.package-card');
            if (packageCard) {
                packageCard.classList.add('selected');
                loadPackagePrices(packages[0].value);
            }
        }
    });
</script>
@endsection

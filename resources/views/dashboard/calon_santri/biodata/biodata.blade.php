@extends('layouts.app')

@section('title', 'Formulir Biodata Santri - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .form-container {
        @apply bg-white rounded-2xl shadow-xl p-8 max-w-6xl mx-auto;
    }
    .section-title {
        @apply text-2xl font-bold text-gray-800 mb-6 pb-3 border-b-2 border-primary/20;
    }
    .subsection-title {
        @apply text-xl font-semibold text-primary mb-4;
    }
    .form-grid {
        @apply grid grid-cols-1 md:grid-cols-2 gap-6;
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
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200;
    }
    .form-select {
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 bg-white;
    }
    .form-textarea {
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 resize-none;
    }
    .package-card {
        @apply border-2 border-gray-200 rounded-xl p-6 cursor-pointer transition-all duration-300 hover:border-primary hover:shadow-lg;
    }
    .package-card.selected {
        @apply border-primary bg-primary/5 shadow-lg;
    }
    .price-item {
        @apply flex justify-between py-3 border-b border-gray-100;
    }
    .price-total {
        @apply flex justify-between text-lg font-bold mt-4 pt-4 border-t-2 border-primary/30;
    }
    .btn-primary {
        @apply bg-primary text-white px-8 py-3 rounded-xl hover:bg-secondary transition duration-300 font-semibold;
    }
    .btn-secondary {
        @apply bg-gray-500 text-white px-8 py-3 rounded-xl hover:bg-gray-600 transition duration-300 font-semibold;
    }
    .info-box {
        @apply bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6;
    }
    .error-message {
        @apply text-red-500 text-sm mt-1;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="form-container">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary mb-3">Formulir Biodata Santri</h1>
            <p class="text-gray-600 text-lg">Lengkapi data diri Anda dengan benar dan lengkap</p>
        </div>

        @if($errors->any())
        <div class="info-box">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-blue-500 text-xl mr-3"></i>
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
            <div class="mb-8">
                <h2 class="section-title">📦 Pilihan Paket</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @foreach($packages as $package)
                    <div class="package-card {{ (old('package_id') == $package->id || ($registration && $registration->package_id == $package->id)) ? 'selected' : '' }}"
                         onclick="selectPackage({{ $package->id }})">
                        <div class="flex items-start mb-3">
                            <input type="radio" name="package_id" value="{{ $package->id }}"
                                   id="package_{{ $package->id }}"
                                   {{ (old('package_id') == $package->id || ($registration && $registration->package_id == $package->id)) ? 'checked' : '' }}
                                   class="mt-1 mr-3">
                            <div class="flex-1">
                                <label for="package_{{ $package->id }}" class="text-lg font-bold text-primary cursor-pointer">
                                    {{ $package->name }}
                                </label>
                                <p class="text-gray-600 text-sm mt-1">{{ $package->description }}</p>
                                <span class="inline-block mt-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-medium">
                                    {{ $package->type_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('package_id')
                    <p class="error-message">{{ $message }}</p>
                @enderror

                <!-- Detail Biaya -->
                <div id="priceDetails" class="bg-gray-50 rounded-xl p-6 {{ ($registration && $registration->package) ? '' : 'hidden' }}">
                    <h3 class="subsection-title">💰 Detail Biaya</h3>
                    <div id="priceList" class="space-y-2">
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
            <div class="mb-8">
                <h2 class="section-title">👤 Data Pribadi Santri</h2>

                <div class="form-grid">
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $registration->nama_lengkap ?? '') }}"
                               class="form-input" placeholder="Masukkan nama lengkap" required>
                        @error('nama_lengkap')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div class="form-group">
                        <label class="form-label form-label-required">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $registration->nik ?? '') }}"
                               class="form-input" placeholder="Masukkan 16 digit NIK" maxlength="16" required>
                        @error('nik')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $registration->tempat_lahir ?? '') }}"
                               class="form-input" placeholder="Kota tempat lahir" required>
                        @error('tempat_lahir')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $registration->tanggal_lahir ?? '') }}"
                               class="form-input" required>
                        @error('tanggal_lahir')
                            <p class="error-message">{{ $message }}</p>
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
                            <p class="error-message">{{ $message }}</p>
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
                            <p class="error-message">{{ $message }}</p>
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
                            <p class="error-message">{{ $message }}</p>
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
                            <p class="error-message">{{ $message }}</p>
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
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIS/NISN/NSP -->
                    <div class="form-group">
                        <label class="form-label">NIS/NISN/NSP</label>
                        <input type="text" name="nis_nisn_nsp" value="{{ old('nis_nisn_nsp', $registration->nis_nisn_nsp ?? '') }}"
                               class="form-input" placeholder="Nomor Induk Siswa">
                        @error('nis_nisn_nsp')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenjang Pendidikan Terakhir -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Jenjang Pendidikan Terakhir</label>
                        <input type="text" name="jenjang_pendidikan_terakhir" value="{{ old('jenjang_pendidikan_terakhir', $registration->jenjang_pendidikan_terakhir ?? '') }}"
                               class="form-input" placeholder="Contoh: SMA, SMP, SD" required>
                        @error('jenjang_pendidikan_terakhir')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Sekolah Terakhir -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Sekolah Terakhir</label>
                        <input type="text" name="nama_sekolah_terakhir" value="{{ old('nama_sekolah_terakhir', $registration->nama_sekolah_terakhir ?? '') }}"
                               class="form-input" placeholder="Nama sekolah terakhir" required>
                        @error('nama_sekolah_terakhir')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat Sekolah Terakhir -->
                <div class="form-group mt-4">
                    <label class="form-label form-label-required">Alamat Sekolah Terakhir</label>
                    <textarea name="alamat_sekolah_terakhir" rows="2" class="form-textarea"
                              placeholder="Alamat lengkap sekolah terakhir" required>{{ old('alamat_sekolah_terakhir', $registration->alamat_sekolah_terakhir ?? '') }}</textarea>
                    @error('alamat_sekolah_terakhir')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 3: Alamat Tinggal -->
            <div class="mb-8">
                <h2 class="section-title">🏠 Alamat Tinggal</h2>

                <div class="form-group mb-4">
                    <label class="form-label form-label-required">Alamat Tinggal</label>
                    <textarea name="alamat_tinggal" rows="3" class="form-textarea"
                              placeholder="Alamat lengkap tempat tinggal" required>{{ old('alamat_tinggal', $registration->alamat_tinggal ?? '') }}</textarea>
                    @error('alamat_tinggal')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-grid">
                    <!-- RT -->
                    <div class="form-group">
                        <label class="form-label form-label-required">RT</label>
                        <input type="text" name="rt" value="{{ old('rt', $registration->rt ?? '') }}"
                               class="form-input" placeholder="001" maxlength="3" required>
                        @error('rt')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RW -->
                    <div class="form-group">
                        <label class="form-label form-label-required">RW</label>
                        <input type="text" name="rw" value="{{ old('rw', $registration->rw ?? '') }}"
                               class="form-input" placeholder="002" maxlength="3" required>
                        @error('rw')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelurahan -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kelurahan</label>
                        <input type="text" name="kelurahan" value="{{ old('kelurahan', $registration->kelurahan ?? '') }}"
                               class="form-input" placeholder="Nama kelurahan" required>
                        @error('kelurahan')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kecamatan -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $registration->kecamatan ?? '') }}"
                               class="form-input" placeholder="Nama kecamatan" required>
                        @error('kecamatan')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kota -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kota</label>
                        <input type="text" name="kota" value="{{ old('kota', $registration->kota ?? '') }}"
                               class="form-input" placeholder="Nama kota" required>
                        @error('kota')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Pos -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $registration->kode_pos ?? '') }}"
                               class="form-input" placeholder="12345" maxlength="5" required>
                        @error('kode_pos')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Data Orang Tua -->
            <div class="mb-8">
                <h2 class="section-title">👨‍👩‍👧‍👦 Data Orang Tua</h2>

                <div class="form-grid">
                    <!-- Nama Ayah Kandung -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Ayah Kandung</label>
                        <input type="text" name="nama_ayah_kandung" value="{{ old('nama_ayah_kandung', $registration->nama_ayah_kandung ?? '') }}"
                               class="form-input" placeholder="Nama lengkap ayah" required>
                        @error('nama_ayah_kandung')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Ibu Kandung -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Ibu Kandung</label>
                        <input type="text" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung', $registration->nama_ibu_kandung ?? '') }}"
                               class="form-input" placeholder="Nama lengkap ibu" required>
                        @error('nama_ibu_kandung')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan Ayah -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $registration->pekerjaan_ayah ?? '') }}"
                               class="form-input" placeholder="Pekerjaan ayah" required>
                        @error('pekerjaan_ayah')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan Ibu -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $registration->pekerjaan_ibu ?? '') }}"
                               class="form-input" placeholder="Pekerjaan ibu" required>
                        @error('pekerjaan_ibu')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penghasilan Ayah -->
                    <div class="form-group">
                        <label class="form-label">Penghasilan Ayah (Rp)</label>
                        <input type="number" name="penghasilan_ayah" value="{{ old('penghasilan_ayah', $registration->penghasilan_ayah ?? '') }}"
                               class="form-input" placeholder="0" min="0" step="1000">
                        @error('penghasilan_ayah')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penghasilan Ibu -->
                    <div class="form-group">
                        <label class="form-label">Penghasilan Ibu (Rp)</label>
                        <input type="number" name="penghasilan_ibu" value="{{ old('penghasilan_ibu', $registration->penghasilan_ibu ?? '') }}"
                               class="form-input" placeholder="0" min="0" step="1000">
                        @error('penghasilan_ibu')
                            <p class="error-message">{{ $message }}</p>
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
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon Orang Tua -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nomor Telepon Orang Tua</label>
                        <input type="text" name="nomor_telpon_orang_tua" value="{{ old('nomor_telpon_orang_tua', $registration->nomor_telpon_orang_tua ?? '') }}"
                               class="form-input" placeholder="081234567890" required>
                        @error('nomor_telpon_orang_tua')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 5: Data Kesehatan -->
            <div class="mb-8">
                <h2 class="section-title">🏥 Data Kesehatan</h2>

                <div class="form-grid">
                    <!-- Alergi Obat -->
                    <div class="form-group">
                        <label class="form-label">Alergi Obat</label>
                        <input type="text" name="alergi_obat" value="{{ old('alergi_obat', $registration->alergi_obat ?? '') }}"
                               class="form-input" placeholder="Jenis alergi obat (jika ada)">
                        @error('alergi_obat')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penyakit Kronis -->
                    <div class="form-group">
                        <label class="form-label">Penyakit Kronis</label>
                        <input type="text" name="penyakit_kronis" value="{{ old('penyakit_kronis', $registration->penyakit_kronis ?? '') }}"
                               class="form-input" placeholder="Penyakit kronis (jika ada)">
                        @error('penyakit_kronis')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 6: Data Wali -->
            <div class="mb-8">
                <h2 class="section-title">👨‍💼 Data Wali</h2>

                <div class="form-grid">
                    <!-- Nama Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nama Wali</label>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali', $registration->nama_wali ?? '') }}"
                               class="form-input" placeholder="Nama lengkap wali" required>
                        @error('nama_wali')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Nomor Telepon Wali</label>
                        <input type="text" name="nomor_telpon_wali" value="{{ old('nomor_telpon_wali', $registration->nomor_telpon_wali ?? '') }}"
                               class="form-input" placeholder="081234567890" required>
                        @error('nomor_telpon_wali')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat Wali -->
                <div class="form-group mb-4">
                    <label class="form-label form-label-required">Alamat Wali</label>
                    <textarea name="alamat_wali" rows="3" class="form-textarea"
                              placeholder="Alamat lengkap wali" required>{{ old('alamat_wali', $registration->alamat_wali ?? '') }}</textarea>
                    @error('alamat_wali')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-grid">
                    <!-- RT Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">RT Wali</label>
                        <input type="text" name="rt_wali" value="{{ old('rt_wali', $registration->rt_wali ?? '') }}"
                               class="form-input" placeholder="001" maxlength="3" required>
                        @error('rt_wali')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RW Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">RW Wali</label>
                        <input type="text" name="rw_wali" value="{{ old('rw_wali', $registration->rw_wali ?? '') }}"
                               class="form-input" placeholder="002" maxlength="3" required>
                        @error('rw_wali')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelurahan Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kelurahan Wali</label>
                        <input type="text" name="kelurahan_wali" value="{{ old('kelurahan_wali', $registration->kelurahan_wali ?? '') }}"
                               class="form-input" placeholder="Nama kelurahan" required>
                        @error('kelurahan_wali')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kecamatan Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kecamatan Wali</label>
                        <input type="text" name="kecamatan_wali" value="{{ old('kecamatan_wali', $registration->kecamatan_wali ?? '') }}"
                               class="form-input" placeholder="Nama kecamatan" required>
                        @error('kecamatan_wali')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kota Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kota Wali</label>
                        <input type="text" name="kota_wali" value="{{ old('kota_wali', $registration->kota_wali ?? '') }}"
                               class="form-input" placeholder="Nama kota" required>
                        @error('kota_wali')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Pos Wali -->
                    <div class="form-group">
                        <label class="form-label form-label-required">Kode Pos Wali</label>
                        <input type="text" name="kode_pos_wali" value="{{ old('kode_pos_wali', $registration->kode_pos_wali ?? '') }}"
                               class="form-input" placeholder="12345" maxlength="5" required>
                        @error('kode_pos_wali')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-between items-center pt-6 border-t-2 border-gray-200">
                <button type="button" onclick="window.history.back()" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>{{ $registration ? 'Perbarui Biodata' : 'Simpan Biodata' }}
                </button>
            </div>
        </form>
    </div>
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
                console.error('Error loading prices:', error);
            });
    }

    // Form submission handling
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('biodataForm').addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            submitButton.disabled = true;
        });
    });
</script>
@endsection

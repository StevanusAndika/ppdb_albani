@extends('layouts.app')

@section('title', 'Edit Biodata - ' . $user->name)

@section('content')
<div class="min-h-screen bg-gray-50 font-sans w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between max-w-6xl mx-auto">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">
                    {{ $registration ? 'Edit Biodata User' : 'Buat Biodata User' }}
                </h1>
                <p class="text-secondary">{{ $user->name }}</p>
            </div>
            <a href="{{ route('admin.manage-users.biodata.show', $user) }}" 
               class="mt-4 md:mt-0 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition duration-200 flex items-center gap-2 justify-center md:justify-start">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto py-6 px-4">
        <!-- Alert Messages -->
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <h3 class="text-red-800 font-semibold mb-2">Terjadi Kesalahan!</h3>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.manage-users.biodata.save-registration', $user) }}" method="POST" class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            @csrf

            <!-- Informasi Dasar Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                    <i class="fas fa-user text-primary mr-2"></i>
                    Informasi Dasar
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $registration->nama_lengkap ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nama_lengkap') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap" required>
                        @error('nama_lengkap') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIK *</label>
                        <input type="text" name="nik" value="{{ old('nik', $registration->nik ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nik') border-red-500 @enderror"
                               placeholder="Masukkan NIK" required>
                        @error('nik') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir *</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $registration->tempat_lahir ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('tempat_lahir') border-red-500 @enderror"
                               placeholder="Masukkan tempat lahir" required>
                        @error('tempat_lahir') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $registration->tanggal_lahir ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('tanggal_lahir') border-red-500 @enderror"
                               required>
                        @error('tanggal_lahir') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('jenis_kelamin') border-red-500 @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="laki-laki" {{ old('jenis_kelamin', $registration->jenis_kelamin ?? '') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin', $registration->jenis_kelamin ?? '') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Agama -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Agama *</label>
                        <input type="text" name="agama" value="{{ old('agama', $registration->agama ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('agama') border-red-500 @enderror"
                               placeholder="Masukkan agama" required>
                        @error('agama') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Alamat Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                    Alamat Tinggal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea name="alamat_tinggal" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('alamat_tinggal') border-red-500 @enderror"
                                  placeholder="Masukkan alamat lengkap" required>{{ old('alamat_tinggal', $registration->alamat_tinggal ?? '') }}</textarea>
                        @error('alamat_tinggal') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- RT -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">RT *</label>
                        <input type="text" name="rt" value="{{ old('rt', $registration->rt ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('rt') border-red-500 @enderror"
                               placeholder="Masukkan RT" required>
                        @error('rt') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- RW -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">RW *</label>
                        <input type="text" name="rw" value="{{ old('rw', $registration->rw ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('rw') border-red-500 @enderror"
                               placeholder="Masukkan RW" required>
                        @error('rw') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kelurahan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kelurahan *</label>
                        <input type="text" name="kelurahan" value="{{ old('kelurahan', $registration->kelurahan ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('kelurahan') border-red-500 @enderror"
                               placeholder="Masukkan kelurahan" required>
                        @error('kelurahan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan *</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $registration->kecamatan ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('kecamatan') border-red-500 @enderror"
                               placeholder="Masukkan kecamatan" required>
                        @error('kecamatan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kota -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kota *</label>
                        <input type="text" name="kota" value="{{ old('kota', $registration->kota ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('kota') border-red-500 @enderror"
                               placeholder="Masukkan kota" required>
                        @error('kota') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Program Pendidikan Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                    <i class="fas fa-graduation-cap text-primary mr-2"></i>
                    Program Pendidikan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Program Pendidikan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Program Pendidikan *</label>
                        <select name="program_pendidikan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('program_pendidikan') border-red-500 @enderror" required>
                            <option value="">-- Pilih Program --</option>
                            <option value="tahfidz" {{ old('program_pendidikan', $registration->program_pendidikan ?? '') == 'tahfidz' ? 'selected' : '' }}>Tahfidz</option>
                            <option value="umum" {{ old('program_pendidikan', $registration->program_pendidikan ?? '') == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="plus" {{ old('program_pendidikan', $registration->program_pendidikan ?? '') == 'plus' ? 'selected' : '' }}>Plus</option>
                        </select>
                        @error('program_pendidikan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Package -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Paket Pendaftaran</label>
                        <select name="package_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('package_id') border-red-500 @enderror">
                            <option value="">-- Pilih Paket --</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ old('package_id', $registration->package_id ?? '') == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('package_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Sekolah Terakhir -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Sekolah Terakhir *</label>
                        <input type="text" name="nama_sekolah_terakhir" value="{{ old('nama_sekolah_terakhir', $registration->nama_sekolah_terakhir ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nama_sekolah_terakhir') border-red-500 @enderror"
                               placeholder="Masukkan nama sekolah terakhir" required>
                        @error('nama_sekolah_terakhir') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                    <i class="fas fa-check-circle text-primary mr-2"></i>
                    Status
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Pendaftaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pendaftaran *</label>
                        <select name="status_pendaftaran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('status_pendaftaran') border-red-500 @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="proses" {{ old('status_pendaftaran', $registration->status_pendaftaran ?? '') == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="diterima" {{ old('status_pendaftaran', $registration->status_pendaftaran ?? '') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ old('status_pendaftaran', $registration->status_pendaftaran ?? '') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status_pendaftaran') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end pt-6 border-t border-gray-200">
                <a href="{{ route('admin.manage-users.biodata.show', $user) }}" 
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-primary hover:bg-secondary text-white rounded-lg transition duration-200 font-semibold">
                    <i class="fas fa-save mr-2"></i>
                    {{ $registration ? 'Perbarui Data' : 'Buat Data Pendaftaran' }}
                </button>
            </div>
        </form>
    </main>

    @include('layouts.components.admin.footer')
</div>

@endsection

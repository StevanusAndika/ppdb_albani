@extends('layouts.app')

@section('title', 'Biodata User - ' . $user->name)

@section('content')
<div class="min-h-screen bg-gray-50 font-sans w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between max-w-6xl mx-auto">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Biodata User</h1>
                <p class="text-secondary">{{ $user->name }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2 flex-wrap justify-center md:justify-start">
                <a href="{{ route('admin.manage-users.edit', $user) }}" 
                   class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded-lg transition duration-200 flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Edit User
                </a>
                @if($registration)
                    <a href="{{ route('admin.manage-users.biodata.edit-registration', $user) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center gap-2">
                        <i class="fas fa-pencil-alt"></i>
                        Edit Biodata
                    </a>
                    <a href="{{ route('admin.manage-users.biodata.edit-documents', $user) }}" 
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200 flex items-center gap-2">
                        <i class="fas fa-file-upload"></i>
                        Upload Dokumen
                    </a>
                @else
                    <a href="{{ route('admin.manage-users.biodata.edit-registration', $user) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Buat Biodata
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto py-6 px-4">
        <!-- User Info Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                <i class="fas fa-user text-primary mr-2"></i>
                Informasi Dasar
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Email</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Nomor Telepon</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->phone_number ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Role</p>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-medium text-sm">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Data Section -->
        @if($registration)
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                <i class="fas fa-document-alt text-primary mr-2"></i>
                Data Pendaftaran
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->nama_lengkap }}</p>
                </div>

                <!-- NIK -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">NIK</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->nik }}</p>
                </div>

                <!-- Tempat Lahir -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Tempat Lahir</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->tempat_lahir }}</p>
                </div>

                <!-- Tanggal Lahir -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Tanggal Lahir</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $registration->tanggal_lahir ? \Carbon\Carbon::parse($registration->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>

                <!-- Jenis Kelamin -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Jenis Kelamin</p>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($registration->jenis_kelamin) }}</p>
                </div>

                <!-- Agama -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Agama</p>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($registration->agama) }}</p>
                </div>

                <!-- Alamat -->
                <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Alamat Tinggal</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $registration->alamat_tinggal }}, RT {{ $registration->rt }}, RW {{ $registration->rw }},
                        {{ $registration->kelurahan }}, {{ $registration->kecamatan }}, {{ $registration->kota }}
                    </p>
                </div>

                <!-- Program Pendidikan -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Program Pendidikan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $registration->program_pendidikan)) }}</p>
                </div>

                <!-- Nama Sekolah Terakhir -->
                <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Nama Sekolah Terakhir</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->nama_sekolah_terakhir }}</p>
                </div>

                <!-- Status Pendaftaran -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Status Pendaftaran</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                        {{ $registration->status_pendaftaran === 'diterima' ? 'bg-green-100 text-green-800' : 
                           ($registration->status_pendaftaran === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $registration->status_pendaftaran)) }}
                    </span>
                </div>

                <!-- Package -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Paket</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->package->name ?? '-' }}</p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <i class="fas fa-info-circle text-blue-600 text-2xl flex-shrink-0 mt-1"></i>
                <div class="flex-1">
                    <p class="text-blue-800 font-medium">User ini belum memiliki data pendaftaran</p>
                    <p class="text-blue-700 text-sm mt-1">Silakan buat data pendaftaran terlebih dahulu agar dokumen dapat diupload. Klik tombol "Buat Biodata" di atas untuk memulai.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Documents Section -->
        @if($registration)
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-file-alt text-primary mr-2"></i>
                    Dokumen Pendaftaran
                </h2>
                @php
                    $uploadedDocs = 0;
                    if ($registration->kartu_keluaga_path) $uploadedDocs++;
                    if ($registration->ijazah_path) $uploadedDocs++;
                    if ($registration->akta_kelahiran_path) $uploadedDocs++;
                    if ($registration->pas_foto_path) $uploadedDocs++;
                @endphp
                <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ $uploadedDocs }}/4 Dokumen
                </span>
            </div>

            @php
                $documents = [
                    'kartu_keluaga_path' => ['name' => 'Kartu Keluarga', 'icon' => 'fa-users', 'color' => 'blue'],
                    'ijazah_path' => ['name' => 'Ijazah', 'icon' => 'fa-graduation-cap', 'color' => 'green'],
                    'akta_kelahiran_path' => ['name' => 'Akta Kelahiran', 'icon' => 'fa-birthday-cake', 'color' => 'purple'],
                    'pas_foto_path' => ['name' => 'Pas Foto', 'icon' => 'fa-camera', 'color' => 'orange'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($documents as $fieldName => $docInfo)
                    @php
                        $filePath = $registration->{$fieldName};
                        $isUploaded = !empty($filePath);
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-{{ $docInfo['color'] }}-100 rounded-full flex items-center justify-center">
                                    <i class="fas {{ $docInfo['icon'] }} text-{{ $docInfo['color'] }}-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $docInfo['name'] }}</h4>
                                    @if($isUploaded)
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($registration->updated_at)->translatedFormat('d F Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $isUploaded ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $isUploaded ? '✓ Ada' : '✗ Belum' }}
                            </span>
                        </div>

                        @if($isUploaded)
                            <div class="flex gap-2">
                                <a href="{{ route('admin.manage-users.biodata.download-document', [$user, $fieldName]) }}" 
                                   class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 py-2 px-3 rounded text-center text-sm font-medium transition duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-download"></i>
                                    Download
                                </a>
                                <form action="{{ route('admin.manage-users.biodata.delete-document', [$user, $fieldName]) }}" 
                                      method="POST" 
                                      style="display: inline; flex: 1;"
                                      onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-100 hover:bg-red-200 text-red-800 py-2 px-3 rounded text-sm font-medium transition duration-200 flex items-center justify-center gap-2">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 text-center py-2">Dokumen belum diupload</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Upload Guide -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex gap-4">
                    <i class="fas fa-info-circle text-blue-600 text-xl flex-shrink-0 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-2">Cara Upload Dokumen</h4>
                        <ol class="text-blue-800 text-sm space-y-1 list-decimal list-inside">
                            <li>Klik tombol "Upload Dokumen" di atas</li>
                            <li>Pilih tipe dokumen yang ingin diupload</li>
                            <li>Drag & drop atau pilih file (format PDF/JPG/PNG, max 10MB)</li>
                            <li>Dokumen akan langsung tersimpan dan bisa didownload</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </main>

    @include('layouts.components.admin.footer')
</div>

<script>
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
</script>
@endsection

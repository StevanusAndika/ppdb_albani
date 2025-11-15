<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Calon Santri - {{ $registration->id_pendaftaran }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Pondok Pesantren Bani Syahid</h1>
            <p class="text-white/90">Informasi Calon Santri</p>
        </div>

        <!-- Main Card -->
        <div class="card-glass rounded-2xl shadow-2xl p-6 mb-6">
            <!-- ID Pendaftaran & Status -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $registration->nama_lengkap }}</h2>
                    <p class="text-gray-600">ID: {{ $registration->id_pendaftaran }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        {{ $registration->status_pendaftaran == 'diterima' ? 'bg-green-100 text-green-800' :
                           ($registration->status_pendaftaran == 'ditolak' ? 'bg-red-100 text-red-800' :
                           'bg-yellow-100 text-yellow-800') }}">
                        {{ $registration->status_label }}
                    </span>
                    <p class="text-sm text-gray-500 mt-1">{{ $registration->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Barcode Section -->
            <div class="text-center border-2 border-dashed border-gray-300 rounded-xl p-6 mb-6 bg-white">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Barcode Pendaftaran</h3>
                <div class="flex justify-center mb-4">
                    <img src="{{ route('barcode.image', $registration->id_pendaftaran) }}"
                         alt="Barcode {{ $registration->id_pendaftaran }}"
                         class="h-32 mx-auto">
                </div>
                <p class="text-sm text-gray-600 mb-4">Scan barcode untuk melihat informasi ini</p>

                <!-- Download Button -->
                <a href="{{ route('barcode.download', $registration->id_pendaftaran) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-download mr-2"></i>
                    Download Barcode
                </a>
            </div>

            <!-- Information Grid -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Data Pribadi -->
                {{-- <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Data Pribadi
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">NIK</label>
                            <p class="font-medium">{{ $registration->nik }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Tempat, Tanggal Lahir</label>
                            <p class="font-medium">{{ $registration->tempat_lahir }}, {{ $registration->tanggal_lahir->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Jenis Kelamin</label>
                            <p class="font-medium">{{ ucfirst($registration->jenis_kelamin) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Agama</label>
                            <p class="font-medium">{{ ucfirst($registration->agama) }}</p>
                        </div>
                    </div>
                </div> --}}

                <!-- Kontak & Alamat -->
                {{-- <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                        <i class="fas fa-address-book mr-2 text-green-600"></i>Kontak & Alamat
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">Alamat</label>
                            <p class="font-medium">{{ $registration->alamat_tinggal }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Kecamatan, Kota</label>
                            <p class="font-medium">{{ $registration->kecamatan }}, {{ $registration->kota }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Telpon Orang Tua</label>
                            <p class="font-medium">{{ $registration->nomor_telpon_orang_tua }}</p>
                        </div>
                    </div>
                </div> --}}

                <!-- Data Orang Tua -->
                {{-- <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                        <i class="fas fa-users mr-2 text-purple-600"></i>Data Orang Tua
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">Nama Ayah</label>
                            <p class="font-medium">{{ $registration->nama_ayah_kandung }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Pekerjaan Ayah</label>
                            <p class="font-medium">{{ $registration->pekerjaan_ayah }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Nama Ibu</label>
                            <p class="font-medium">{{ $registration->nama_ibu_kandung }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Pekerjaan Ibu</label>
                            <p class="font-medium">{{ $registration->pekerjaan_ibu }}</p>
                        </div>
                    </div>
                </div> --}}

                <!-- Pendidikan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                        <i class="fas fa-graduation-cap mr-2 text-orange-600"></i>Pendidikan
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">Jenjang Pendidikan</label>
                            <p class="font-medium">{{ $registration->jenjang_pendidikan_terakhir }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Sekolah Terakhir</label>
                            <p class="font-medium">{{ $registration->nama_sekolah_terakhir }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Alamat Sekolah</label>
                            <p class="font-medium">{{ $registration->alamat_sekolah_terakhir }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Information -->
            @if($registration->package)
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">
                    <i class="fas fa-cube mr-2"></i>Paket Pendaftaran
                </h3>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-blue-900">{{ $registration->package->name }}</p>
                        <p class="text-blue-700">{{ $registration->formatted_total_biaya }}</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        {{ $registration->program_unggulan_id }}
                    </span>
                </div>
            </div>
            @endif

            <!-- Catatan Admin -->
            @if($registration->catatan_admin)
            <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <h4 class="font-semibold text-yellow-800 mb-2">
                    <i class="fas fa-exclamation-circle mr-2"></i>Catatan Admin
                </h4>
                <p class="text-yellow-700">{{ $registration->catatan_admin }}</p>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center text-white/80">
            <p>© 2025 Pondok Pesantren Bani Syahid. All rights reserved.</p>
            <p class="text-sm mt-1">Halaman ini dapat diakses dengan scan barcode</p>
        </div>
    </div>
</body>
</html>

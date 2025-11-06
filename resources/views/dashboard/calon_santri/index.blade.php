@extends('layouts.app')

@section('title', 'Dashboard Santri - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar (in-page for styling parity with welcome) -->
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
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Dashboard Santri</h1>
        <p class="text-secondary">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span> — Selamat datang di panel pendaftaran.</p>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md mt-6">
            <div class="flex flex-col md:flex-row items-center md:items-start md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-primary">Status Akun</h2>
                    <p class="text-secondary mt-1">Anda login sebagai <span class="font-semibold text-green-600">Calon Santri</span></p>
                    @if($registration)
                    <p class="text-sm text-gray-600 mt-1">ID Pendaftaran: <span class="font-mono font-bold">{{ $registration->id_pendaftaran }}</span></p>
                    @endif
                </div>

                <div class="flex gap-3">
                    @if($registration)
                        <a href="{{ route('santri.biodata.index') }}" class="bg-primary text-white px-4 py-1.5 rounded-full hover:bg-secondary transition duration-300 flex items-center justify-center">
                            Lihat Biodata
                        </a>
                    @else
                        <a href="{{ route('santri.biodata.index') }}" class="bg-emerald-600 text-white px-4 py-1.5 rounded-full hover:bg-emerald-700 transition duration-300 flex items-center justify-center">
                            Mulai Pendaftaran
                        </a>
                    @endif

                    @if($registration && $registration->hasAllDocuments())
                        <a href="{{ route('santri.documents.index') }}" class="bg-green-600 text-white px-4 py-1.5 rounded-full hover:bg-green-700 transition duration-300 flex items-center justify-center">
                            Dokumen Lengkap
                        </a>
                    @elseif($registration)
                        <a href="{{ route('santri.documents.index') }}" class="bg-orange-500 text-white px-4 py-1.5 rounded-full hover:bg-orange-600 transition duration-300 flex items-center justify-center">
                            Lengkapi Dokumen
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Profile Card -->
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
                        <span class="font-medium text-primary">{{ $registration->package->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Biaya</span>
                        <span class="font-medium text-primary">{{ $registration->formatted_total_biaya }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex gap-3">
                    @if($registration)
                        <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                            Edit Biodata
                        </a>
                        <a href="{{ route('santri.documents.index') }}" class="w-full text-center bg-secondary text-white py-2 rounded-full hover:bg-gray-600 transition duration-300">
                            Kelola Dokumen
                        </a>
                    @else
                        <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                            Isi Biodata
                        </a>
                    @endif
                </div>

                <!-- Download All Documents Button -->
                @if($registration && $registration->hasAllDocuments())
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <button onclick="downloadAllDocuments()" class="w-full bg-purple-600 text-white py-2 rounded-full hover:bg-purple-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-file-archive mr-2"></i> Download Semua Dokumen (ZIP)
                    </button>
                    <p class="text-xs text-gray-500 text-center mt-2">Download semua dokumen dalam satu file ZIP</p>
                </div>
                @endif
            </div>

            <!-- Middle: Status & Steps -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Pendaftaran -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-primary mb-3">Status Pendaftaran</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <p class="text-secondary items-center">Status saat ini:</p>
                            <div>
                                @if($registration)
                                    @php
                                        $statusColors = [
                                            'belum_mendaftar' => 'bg-gray-100 text-gray-800',
                                            'telah_mengisi' => 'bg-blue-100 text-blue-800',
                                            'telah_dilihat' => 'bg-yellow-100 text-yellow-800',
                                            'menunggu_diverifikasi' => 'bg-orange-100 text-orange-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                            'diterima' => 'bg-green-100 text-green-800'
                                        ];
                                        $statusIcons = [
                                            'belum_mendaftar' => 'fa-clock',
                                            'telah_mengisi' => 'fa-edit',
                                            'telah_dilihat' => 'fa-eye',
                                            'menunggu_diverifikasi' => 'fa-hourglass-half',
                                            'ditolak' => 'fa-times-circle',
                                            'diterima' => 'fa-check-circle'
                                        ];
                                    @endphp
                                    <div class="px-3 py-2 rounded-full text-sm font-medium {{ $statusColors[$registration->status_pendaftaran] ?? 'bg-gray-100 text-gray-800' }} items-center">
                                        <i class="fas {{ $statusIcons[$registration->status_pendaftaran] ?? 'fa-question-circle' }} mr-2"></i>
                                        {{ $registration->status_label }}
                                    </div>
                                @else
                                    <div class="px-3 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 items-center">
                                        <i class="fas fa-clock mr-2"></i>Belum Mendaftar
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if($registration && $registration->status_pendaftaran == 'ditolak')
                                <a href="{{ route('santri.biodata.index') }}" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition duration-300">
                                    Perbaiki Data
                                </a>
                            @elseif(!$registration)
                                <a href="{{ route('santri.biodata.index') }}" class="bg-primary text-white px-4 py-2 rounded-full hover:bg-secondary transition duration-300">
                                    Mulai Pendaftaran
                                </a>
                            @else
                                <a href="{{ route('santri.biodata.index') }}" class="bg-primary text-white px-4 py-2 rounded-full hover:bg-secondary transition duration-300">
                                    Lihat Detail
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($registration && $registration->status_pendaftaran == 'ditolak' && $registration->catatan_admin)
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
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

                <!-- Progress Pendaftaran -->
                <div id="pendaftaran" class="bg-white rounded-xl shadow-md p-6">
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
                                            {{ $documentProgress }}% Selesai
                                        @else
                                            Belum
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Verifikasi -->
                        <div class="p-4 border-2 {{ $registration && $registration->status_pendaftaran == 'diterima' ? 'border-green-500 bg-green-50' : 'border-gray-300' }} rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="step-number {{ $registration && $registration->status_pendaftaran == 'diterima' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">4</div>
                                <div>
                                    <h4 class="font-semibold {{ $registration && $registration->status_pendaftaran == 'diterima' ? 'text-green-800' : 'text-gray-600' }}">Verifikasi</h4>
                                    <p class="text-sm {{ $registration && $registration->status_pendaftaran == 'diterima' ? 'text-green-600' : 'text-gray-500' }}">
                                        @if($registration)
                                            {{ $registration->status_label }}
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
                            <span>
                                @php
                                    $totalProgress = 25; // Step 1 always complete
                                    if ($registration) $totalProgress += 25; // Step 2
                                    if ($registration->hasAllDocuments()) $totalProgress += 25; // Step 3
                                    if ($registration->status_pendaftaran == 'diterima') $totalProgress += 25; // Step 4
                                @endphp
                                {{ $totalProgress }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-primary h-3 rounded-full transition-all duration-300"
                                 style="width: {{ $totalProgress }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Dokumen Section -->
                <div id="dokumen" class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-xl font-bold text-primary">Kelengkapan Dokumen</h3>
                        <div class="flex gap-2">
                            @if($registration && $registration->hasAllDocuments())
                            <button onclick="downloadAllDocuments()" class="bg-purple-600 text-white px-3 py-1 rounded-full hover:bg-purple-700 transition duration-300 text-sm flex items-center">
                                <i class="fas fa-file-archive mr-1"></i> Download ZIP
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
                            @if($doc['uploaded'])
                            <div class="mt-2 flex justify-center gap-1">
                                <a href="{{ route('santri.documents.file', $type) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <span class="text-gray-300">|</span>
                                <button onclick="downloadSingleDocument('{{ $type }}')" class="text-green-600 hover:text-green-800 text-xs">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Document Progress -->
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progress Dokumen</span>
                            <span>{{ $documentProgress }}%</span>
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
                        <a href="{{ route('santri.biodata.index') }}" class="inline-block mt-3 bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition duration-300">
                            Isi Biodata Sekarang
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                @if($registration)
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
                    </div>

                  
                @endif
            </div>
        </div>
    </main>

    <!-- Footer (simple) -->
    <footer class="bg-primary text-white py-8 px-4 mt-6">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2025 PPDB Pesantren AI-Our'an Bani Syahid</p>
        </div>
    </footer>

    <style>
        .step-number {
            @apply w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm;
        }
    </style>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) mobileMenu.classList.toggle('hidden');
        });

        // Download single document
        function downloadSingleDocument(documentType) {
            const link = document.createElement('a');
            link.href = `/santri/documents/download/${documentType}`;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Download all documents as ZIP
        function downloadAllDocuments() {
            // Show loading
            Swal.fire({
                title: 'Mempersiapkan File...',
                text: 'Sedang membuat file ZIP dari semua dokumen',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create a temporary link to trigger download
            const link = document.createElement('a');
            link.href = `/santri/documents/download-all`;
            link.target = '_blank';

            // Try download with fetch for error handling
            fetch(`/santri/documents/download-all`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                Swal.close();

                if (!response.ok) {
                    // If response not ok, try to parse error message
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Download gagal');
                    });
                }

                // If response ok, trigger download
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
    </script>
</div>
@endsection

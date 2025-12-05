<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Beasiswa - Pondok Pesantren Al-Qur'an Bani Syahid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#057572',
                        'secondary': '#5B5B5B',
                        'accent': '#9D9D9D',
                        'white': '#FFFFFF'
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #057572 0%, #0a9a8c 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .beasiswa-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .icon-qurani {
            background-color: rgba(37, 99, 235, 0.1);
            color: #2563eb;
        }
        .icon-pemberdayaan {
            background-color: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        .whatsapp-btn {
            background-color: #25D366;
            transition: all 0.3s ease;
        }
        .whatsapp-btn:hover {
            background-color: #128C7E;
            transform: scale(1.05);
        }
        .contact-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        .contact-card-male {
            border-left-color: #059669;
        }
        .contact-card-female {
            border-left-color: #DB2777;
        }
        .contact-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .info-card {
            border-radius: 16px;
            overflow: hidden;
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        /* Navbar styles */
        .nav-container {
            padding: 0.75rem 1rem;
        }
        .nav-logo {
            font-size: 1.1rem;
        }
        .mobile-menu-button {
            padding: 0.5rem;
        }
        .mobile-menu {
            border-radius: 1rem;
            margin-top: 0.75rem;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .mobile-menu-item {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 0.25rem;
        }
        /* Perbaikan untuk navbar mobile */
        @media (max-width: 767px) {
            .nav-container {
                padding: 0.75rem 1rem;
            }
            .nav-logo {
                font-size: 1.1rem;
            }
            .mobile-menu-button {
                padding: 0.5rem;
            }
            .mobile-menu {
                border-radius: 1rem;
                margin-top: 0.75rem;
                padding: 1rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }
            .mobile-menu-item {
                padding: 0.75rem 1rem;
                border-radius: 0.75rem;
                margin-bottom: 0.25rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-lg md:text-xl font-bold text-primary nav-logo">Ponpes Al Bani</a>

            <!-- Desktop menu -->
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium transition duration-300">Beranda</a>
                <a href="{{ url('/') }}#visi-misi" class="text-primary hover:text-secondary font-medium transition duration-300">Visi & Misi</a>
                <a href="{{ url('/') }}#program" class="text-primary hover:text-secondary font-medium transition duration-300">Program</a>
                <a href="{{ route('beasiswa') }}" class="text-primary hover:text-secondary font-medium transition duration-300">Info Beasiswa</a>
                <a href="{{ url('/') }}#kegiatan" class="text-primary hover:text-secondary font-medium transition duration-300">Kegiatan</a>
                <a href="{{ url('/') }}#alur-pendaftaran" class="text-primary hover:text-secondary font-medium transition duration-300">Alur Pendaftaran</a>
                <a href="{{ url('/') }}#biaya" class="text-primary hover:text-secondary font-medium transition duration-300">Biaya</a>
                <a href="{{ url('/') }}#persyaratan" class="text-primary hover:text-secondary font-medium transition duration-300">Persyaratan</a>
                <a href="{{ url('/') }}#faq" class="text-primary hover:text-secondary font-medium transition duration-300">FAQ</a>
                <a href="#" class="text-primary hover:text-secondary font-medium transition duration-300">Kontak</a>

                @auth
                    <!-- Jika user sudah login - hanya tombol logout -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-1.5 rounded-full hover:bg-red-600 transition duration-300">
                            Logout
                        </button>
                    </form>
                @else
                    <!-- Jika user belum login - hanya tombol login -->
                    <a href="{{ route('login') }}">
                        <button class="bg-primary text-white px-4 py-1.5 rounded-full hover:bg-secondary transition duration-300 ml-2">
                            Login
                        </button>
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-primary focus:outline-none mobile-menu-button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-2 mobile-menu bg-white">
            <div class="flex flex-col space-y-1">
                <a href="{{ url('/') }}" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Beranda</a>
                <a href="{{ url('/') }}#visi-misi" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Visi & Misi</a>
                <a href="{{ url('/') }}#program" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Program</a>
                <a href="{{ route('beasiswa') }}" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Info Beasiswa</a>
                <a href="{{ url('/') }}#kegiatan" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Kegiatan</a>
                <a href="{{ url('/') }}#alur-pendaftaran" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Alur Pendaftaran</a>
                <a href="{{ url('/') }}#biaya" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Biaya</a>
                <a href="{{ url('/') }}#persyaratan" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Persyaratan</a>
                <a href="{{ url('/') }}#faq" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">FAQ</a>
                <a href="#" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Kontak</a>

                @auth
                    <!-- Jika user sudah login (mobile) - hanya tombol logout -->
                    <form method="POST" action="{{ route('logout') }}" class="mobile-menu-item">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-full hover:bg-red-600 transition duration-300">
                            Logout
                        </button>
                    </form>
                @else
                    <!-- Jika user belum login (mobile) - hanya tombol login -->
                    <a href="{{ route('login') }}" class="mobile-menu-item">
                        <button class="w-full bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                            Login
                        </button>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <div class="gradient-bg text-white py-12 px-4">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $beasiswaData['judul'] }}</h1>
            <h2 class="text-xl md:text-2xl font-semibold mb-6">{{ $beasiswaData['subjudul'] }}</h2>
            <p class="text-lg opacity-90 max-w-3xl mx-auto">
                Program beasiswa untuk mendukung calon santri berprestasi dan membutuhkan dalam mengenyam pendidikan di Pondok Pesantren Al-Qur'an Bani Syahid
            </p>
            <div class="mt-8">
                <span class="inline-block bg-white/20 px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-calendar-alt mr-2"></i>Tahun Ajaran 2025/2026
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-16 px-4">
        <div class="container mx-auto">
            <!-- Header Program Beasiswa -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-primary mb-4">PROGRAM BEASISWA</h2>
                <div class="w-24 h-1 bg-primary mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Pilih program beasiswa yang sesuai dengan kondisi dan prestasi Anda
                </p>
            </div>

            <!-- Program Beasiswa -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                @foreach($beasiswaData['programs'] as $index => $program)
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border border-gray-100">
                    <div class="flex flex-col items-center text-center mb-6">
                        <div class="beasiswa-icon {{ $index === 0 ? 'icon-qurani' : 'icon-pemberdayaan' }} pulse-animation">
                            <i class="fas {{ $index === 0 ? 'fa-quran text-2xl' : 'fa-hands-helping text-2xl' }}"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-primary mb-3">{{ $program['nama'] }}</h3>
                        <p class="text-gray-600 mb-6">{{ $program['deskripsi'] }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-clipboard-check mr-2 text-primary"></i>
                            <span>Syarat Pendaftaran:</span>
                        </h4>
                        <ul class="space-y-3">
                            @foreach($program['syarat'] as $syarat)
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                <span class="text-gray-700">{{ $syarat }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($index === 0)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <p class="text-sm text-blue-800 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="font-semibold">Catatan:</span> MI = Madrasah Ibtidaiyah, MA = Madrasah Aliyah
                        </p>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Proses seleksi: 1-2 minggu setelah berkas lengkap</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Garis Pembatas -->
            <div class="flex items-center justify-center my-12">
                <div class="w-full border-t border-dashed border-gray-300"></div>
                <div class="px-4">
                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                </div>
                <div class="w-full border-t border-dashed border-gray-300"></div>
            </div>



            <!-- Informasi Penting -->
            <div class="mt-16 max-w-4xl mx-auto">
                <div class="info-card bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 p-8">
                    <h3 class="text-2xl font-bold text-blue-800 mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i> Informasi Penting & Tata Cara Pendaftaran
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-file-signature text-blue-600 mr-2"></i>
                                Dokumen yang Harus Disiapkan
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                    <span>Formulir pendaftaran beasiswa (diambil di pesantren)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                    <span>Fotokopi akta kelahiran dan KK</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                    <span>Pas foto 3x4 (4 lembar) background merah</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                    <span>Fotokopi rapor terakhir</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                    <span>Sertifikat prestasi (jika ada)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                    <span>Surat keterangan tidak mampu (untuk beasiswa pemberdayaan)</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                                Proses Seleksi
                            </h4>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">1</div>
                                    <div>
                                        <p class="font-medium text-gray-800">Pengumpulan Berkas</p>
                                        <p class="text-sm text-gray-600">Sampai dengan 31 Desember 2025</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">2</div>
                                    <div>
                                        <p class="font-medium text-gray-800">Verifikasi Dokumen</p>
                                        <p class="text-sm text-gray-600">1-7 hari kerja setelah pengumpulan</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">3</div>
                                    <div>
                                        <p class="font-medium text-gray-800">Tes Komitmen & Integritas</p>
                                        <p class="text-sm text-gray-600">Calon santri dan wali santri</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0">4</div>
                                    <div>
                                        <p class="font-medium text-gray-800">Pengumuman Hasil</p>
                                        <p class="text-sm text-gray-600">Maksimal 14 hari setelah tes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-blue-200">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3 mt-1"></i>
                                <div>
                                    <p class="font-semibold text-yellow-800">Perhatian!</p>
                                    <p class="text-yellow-700">Pastikan dokumen yang dikirimkan sudah lengkap dan valid. Dokumen palsu atau tidak lengkap akan menyebabkan pendaftaran ditolak.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-center text-primary mb-8">Pertanyaan yang Sering Diajukan</h3>
                <div class="max-w-3xl mx-auto">
                    <div class="space-y-4">
                        <!-- FAQ 1 -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-50">
                                <span class="font-medium text-gray-800">Apakah beasiswa menanggung biaya hidup di pesantren?</span>
                                <i class="fas fa-chevron-down text-primary"></i>
                            </button>
                            <div class="faq-answer hidden p-4 bg-gray-50 border-t">
                                <p class="text-gray-600">Beasiswa Cendekia Qurani dan Pemberdayaan & Kemandirian menanggung biaya pendidikan pokok. Biaya hidup (makan, perlengkapan pribadi) tetap ditanggung oleh wali santri sesuai kemampuan.</p>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-50">
                                <span class="font-medium text-gray-800">Berapa lama masa berlaku beasiswa?</span>
                                <i class="fas fa-chevron-down text-primary"></i>
                            </button>
                            <div class="faq-answer hidden p-4 bg-gray-50 border-t">
                                <p class="text-gray-600">Beasiswa berlaku selama 1 tahun ajaran dan dapat diperpanjang setiap tahun dengan evaluasi prestasi akademik dan non-akademik serta kedisiplinan selama di pesantren.</p>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-50">
                                <span class="font-medium text-gray-800">Apakah bisa mendaftar kedua program beasiswa sekaligus?</span>
                                <i class="fas fa-chevron-down text-primary"></i>
                            </button>
                            <div class="faq-answer hidden p-4 bg-gray-50 border-t">
                                <p class="text-gray-600">Tidak, calon santri hanya bisa memilih satu program beasiswa sesuai dengan kondisi dan kualifikasi yang dimiliki.</p>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-50">
                                <span class="font-medium text-gray-800">Kapan jadwal tes komitmen dan integritas?</span>
                                <i class="fas fa-chevron-down text-primary"></i>
                            </button>
                            <div class="faq-answer hidden p-4 bg-gray-50 border-t">
                                <p class="text-gray-600">Jadwal tes akan diinformasikan via WhatsApp atau telepon setelah dokumen pendaftaran dinyatakan lengkap oleh pihak pesantren.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-primary to-secondary text-white py-12 px-4">
        <div class="container mx-auto text-center">
            <h3 class="text-2xl font-bold mb-4">Segera Daftarkan Diri Anda!</h3>
            <p class="text-lg mb-6 max-w-2xl mx-auto">
                Jangan lewatkan kesempatan untuk mendapatkan pendidikan terbaik dengan dukungan beasiswa dari Pondok Pesantren Al-Qur'an Bani Syahid.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ url('/') }}"
                   class="bg-white text-primary px-8 py-3 rounded-full hover:bg-gray-100 transition duration-300 font-semibold flex items-center">
                    <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                </a>
                <a href="https://wa.me/6289510279293?text=Halo,%20saya%20ingin%20daftar%20program%20beasiswa"
                   target="_blank"
                   class="whatsapp-btn px-8 py-3 rounded-full font-semibold flex items-center">
                    <i class="fab fa-whatsapp mr-2 text-xl"></i> Daftar Sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- Footer (Sama seperti di welcome.blade.php) -->
    <footer class="bg-primary text-white py-12 px-4">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Menu 1 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Tentang Kami</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}#visi-misi" class="hover:text-accent transition duration-300">Visi & Misi</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Galeri</a></li>
                    </ul>
                </div>

                <!-- Menu 2 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Program</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}#program" class="hover:text-accent transition duration-300">Tahfidz Al-Qur'an</a></li>
                        <li><a href="{{ route('beasiswa') }}" class="hover:text-accent transition duration-300">Program Beasiswa</a></li>
                    </ul>
                </div>

                <!-- Menu 3 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Informasi</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}#alur-pendaftaran" class="hover:text-accent transition duration-300">Alur Pendaftaran</a></li>
                        <li><a href="{{ url('/') }}#biaya" class="hover:text-accent transition duration-300">Biaya Pendidikan</a></li>
                        <li><a href="{{ url('/') }}#persyaratan" class="hover:text-accent transition duration-300">Persyaratan</a></li>
                        <li><a href="{{ url('/') }}#faq" class="hover:text-accent transition duration-300">FAQ</a></li>
                    </ul>
                </div>

                <!-- Menu 4 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="https://www.google.com/maps/place/Pondok+Pesantren+Al-Qur'an+Bani+Syahid/@-6.3676771,106.8696904,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69ed654ce6786b:0x1019880ca4f9403b!8m2!3d-6.3676824!4d106.8722707!16s%2Fg%2F11f6m9qmmr?hl=id"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="hover:text-accent transition duration-300">
                               Alamat
                            </a>
                        </li>
                       <li><a href="https://wa.me/6287748115931" class="hover:text-accent transition duration-300">WhatsApp Developers</a></li>
                        <li><a href="https://wa.me/6289510279293" target="_blank" class="hover:text-accent transition duration-300">WhatsApp Admin PPDB Putra</a></li>
                        <li><a href="https://wa.me/6282183953533" target="_blank" class="hover:text-accent transition duration-300">WhatsApp Admin PPDB Putri</a></li>
                        <li><a href="https://banisyahid.bio.link/" class="hover:text-accent transition duration-300">Sosial Media</a></li>
                    </ul>
                </div>
            </div>

           <div class="border-t border-white/20 mt-8 pt-8 text-center">
            <p>&copy; <?php echo date('Y'); ?>
                <a
                    href="https://www.instagram.com/unip_rpl/?igsh=MTFnbGF0ZzltcnNqNw%3D%3D"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-white hover:text-yellow-300 underline transition-colors duration-200"
                >
                    Pondok Pesantren Al-Qur'an Bani Syahid
                </a>. All rights reserved.
            </p>
        </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle - FIXED
        document.getElementById('mobile-menu-button').addEventListener('click', function(e) {
            e.stopPropagation(); // Mencegah event bubbling
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Tutup mobile menu saat klik di luar
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuButton = document.getElementById('mobile-menu-button');

            if (mobileMenu && !mobileMenu.contains(event.target) && !menuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                // Cek apakah link mengarah ke halaman lain atau anchor di halaman sama
                if (href.includes('#') && !href.startsWith('{{ url("/") }}')) {
                    e.preventDefault();
                    const targetId = href.split('#')[1];
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                        // Tutup mobile menu jika terbuka
                        const mobileMenu = document.getElementById('mobile-menu');
                        if (mobileMenu) {
                            mobileMenu.classList.add('hidden');
                        }
                    }
                }
            });
        });

        // FAQ Toggle Function
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const answer = button.nextElementSibling;
                const icon = button.querySelector('i');

                // Toggle current FAQ
                answer.classList.toggle('hidden');
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');

                // Close other FAQs
                document.querySelectorAll('.faq-question').forEach(otherButton => {
                    if (otherButton !== button) {
                        const otherAnswer = otherButton.nextElementSibling;
                        const otherIcon = otherButton.querySelector('i');
                        otherAnswer.classList.add('hidden');
                        otherIcon.classList.remove('fa-chevron-up');
                        otherIcon.classList.add('fa-chevron-down');
                    }
                });
            });
        });

        // WhatsApp Button Animation
        document.querySelectorAll('.whatsapp-btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Auto open first FAQ
        document.addEventListener('DOMContentLoaded', function() {
            const firstFaq = document.querySelector('.faq-question');
            if (firstFaq) {
                firstFaq.click();
            }

            // Tambahkan event listener untuk semua link mobile menu
            document.querySelectorAll('.mobile-menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>

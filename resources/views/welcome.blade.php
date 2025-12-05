<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $contentSettings->judul ?? 'PPDB Pesantren AI-Our\'an Bani Syahid 2025' }}</title>
     <title>{{ $contentSettings->judul ?? 'PPDB Pesantren AI-Our\'an Bani Syahid 2025 - Pendaftaran Santri Baru' }}</title>
    <meta name="title" content="{{ $contentSettings->judul_seo ?? 'PPDB Pesantren AI-Our\'an Bani Syahid 2025 - Pendaftaran Santri Baru' }}">
    <meta name="description" content="{{ $contentSettings->meta_deskripsi ?? 'Sistem Penerimaan Peserta Didik Baru Pesantren AI-Our\'an Bani Syahid 2025. Program Tahfidz, Qiraat Sab\'ah, dan Pendidikan Islam Berbasis Al-Qur\'an.' }}">
    <meta name="keywords" content="{{ $contentSettings->meta_keywords ?? 'PPDB Pesantren, Pesantren Al-Qur\'an, Tahfidz, Santri Baru, Pendidikan Islam, Bani Syahid, 2025' }}">
    <meta name="author" content="Pesantren AI-Our'an Bani Syahid">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="language" content="Indonesian">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $contentSettings->judul_og ?? 'PPDB Pesantren AI-Our\'an Bani Syahid 2025' }}">
    <meta property="og:description" content="{{ $contentSettings->og_deskripsi ?? 'Bergabunglah menjadi santri Pesantren AI-Our\'an Bani Syahid. Program Tahfidz dan Pendidikan Islam Terpadu.' }}">
    <meta property="og:image" content="{{ $contentSettings->og_image ?? '/images/ppdb-og-image.jpg' }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="PPDB Pesantren AI-Our'an Bani Syahid 2025">
    <meta property="og:site_name" content="Pesantren AI-Our'an Bani Syahid">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $contentSettings->judul_twitter ?? 'PPDB Pesantren AI-Our\'an Bani Syahid 2025' }}">
    <meta property="twitter:description" content="{{ $contentSettings->twitter_deskripsi ?? 'Pendaftaran Santri Baru Pesantren AI-Our\'an Bani Syahid. Program Unggulan Tahfidz dan Qiraat.' }}">
    <meta property="twitter:image" content="{{ $contentSettings->twitter_image ?? '/images/ppdb-twitter-image.jpg' }}">

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
        .icon-bg {
            background-color: rgba(5, 117, 114, 0.1);
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #057572;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .faq-accordion {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .faq-item {
            border-bottom: 1px solid #e5e7eb;
        }
        .faq-item:last-child {
            border-bottom: none;
        }
        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: between;
            align-items: center;
            transition: all 0.3s ease;
        }
        .faq-question:hover {
            background-color: #f8fafc;
        }
        .faq-question.active {
            background-color: #f0f9ff;
            border-left: 4px solid #057572;
        }
        .faq-number {
            background: #057572;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.875rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        .faq-text {
            flex: 1;
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
        }
        .faq-icon {
            transition: transform 0.3s ease;
            color: #057572;
        }
        .faq-question.active .faq-icon {
            transform: rotate(180deg);
        }
        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }
        .faq-answer.active {
            padding: 1.5rem;
            max-height: 500px;
        }
        .faq-answer-content {
            color: #4b5563;
            line-height: 1.6;
            border-left: 3px solid #d1d5db;
            padding-left: 1rem;
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
            .faq-question {
                padding: 1rem;
            }
            .faq-text {
                font-size: 0.9rem;
            }
            .faq-answer.active {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
<!-- Navbar -->
<nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-lg md:text-xl font-bold text-primary nav-logo">Ponpes Al Bani</div>

        <!-- Desktop menu -->
        <div class="hidden md:flex space-x-6 items-center">
            <a href="#" class="text-primary hover:text-secondary font-medium transition duration-300">Beranda</a>
            <a href="#visi-misi" class="text-primary hover:text-secondary font-medium transition duration-300">Visi & Misi</a>
            <a href="#program" class="text-primary hover:text-secondary font-medium transition duration-300">Program</a>
            <a href="#kegiatan" class="text-primary hover:text-secondary font-medium transition duration-300">Kegiatan</a>
            <a href="#alur-pendaftaran" class="text-primary hover:text-secondary font-medium transition duration-300">Alur Pendaftaran</a>
            <a href="#biaya" class="text-primary hover:text-secondary font-medium transition duration-300">Biaya</a>
            <a href="#persyaratan" class="text-primary hover:text-secondary font-medium transition duration-300">Persyaratan</a>
            <a href="#faq" class="text-primary hover:text-secondary font-medium transition duration-300">FAQ</a>
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
            <a href="#" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Beranda</a>
            <a href="#visi-misi" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Visi & Misi</a>
            <a href="#program" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Program</a>
            <a href="#kegiatan" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Kegiatan</a>
            <a href="#alur-pendaftaran" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Alur Pendaftaran</a>
            <a href="#biaya" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Biaya</a>
            <a href="#persyaratan" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">Persyaratan</a>
            <a href="#faq" class="mobile-menu-item text-primary hover:bg-primary/10 hover:text-secondary transition duration-300">FAQ</a>
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
    <header class="py-12 px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-primary mb-4">
            {{ $contentSettings->judul ?? 'PPDB' }}
        </h1>
        <h2 class="text-2xl md:text-3xl font-semibold text-primary mb-8">
            {{ $contentSettings->tagline ?? 'Pesantren AI-Our\'an Bani Syahid 2025' }}
        </h2>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md mb-8">
            <p class="text-secondary text-lg mb-4">
                {{ $contentSettings->deskripsi ?? 'Sistem Penerimaan Peserta Didik Baru yang modern, mudah, dan terpercaya. Untuk Masa Depan Yang Lebih Baik' }}
            </p>

            @auth
                <!-- Jika user sudah login -->
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">
                        <button class="bg-primary text-white px-6 py-2.5 rounded-full hover:bg-secondary transition duration-300 max-w-xs mx-auto my-3 font-semibold">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                        </button>
                    </a>
                @elseif(auth()->user()->isCalonSantri() || auth()->user()->role === 'santri')
                    <a href="{{ route('santri.dashboard') }}">
                        <button class="bg-primary text-white px-6 py-2.5 rounded-full hover:bg-secondary transition duration-300 max-w-xs mx-auto my-3 font-semibold">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Santri
                        </button>
                    </a>
                @else
                    <!-- Untuk role lainnya -->
                    <a href="{{ route('dashboard') }}">
                        <button class="bg-primary text-white px-6 py-2.5 rounded-full hover:bg-secondary transition duration-300 max-w-xs mx-auto my-3 font-semibold">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </button>
                    </a>
                @endif
            @else
                <!-- Jika user belum login -->

                    <a href="https://wa.me/6287748115931?text=Halo,%20saya%20ingin%20mendaftar"
                    class="bg-green-600 text-white px-6 py-2.5 rounded-full hover:bg-green-700 transition duration-300 font-semibold inline-flex items-center">
                        <i class="fab fa-whatsapp mr-2"></i>Tanya via WhatsApp
                    </a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Visi & Misi Section -->
    <section id="visi-misi" class="py-16 px-4 bg-gradient-to-r from-primary/10 to-primary/20">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-12">Visi & Misi</h2>

            <div class="max-w-4xl mx-auto">
                <!-- Visi -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-8 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-6">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-eye text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-primary">
                            {{ $contentSettings->visi_judul ?? 'Visi' }}
                        </h3>
                    </div>
                    <p class="text-lg text-secondary leading-relaxed">
                        {{ $contentSettings->visi_deskripsi ?? 'Menjadi pusat pendidikan Al-Qur\'an yang unggul dalam pembacaan, penghafalan, dan pemahaman Al-Qur\'an, dengan tetap istiqamah di atas manhaj dan warisan kelimuan para guru kami.' }}
                    </p>
                </div>

                <!-- Misi -->
                <div class="bg-white rounded-xl shadow-lg p-8 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-6">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-bullseye text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-primary">
                            {{ $contentSettings->misi_judul ?? 'Misi' }}
                        </h3>
                    </div>
                    <div class="text-lg text-secondary leading-relaxed">
                        {!! nl2br(e($contentSettings->misi_deskripsi ?? 'Menanamkan keointaan terhadap Al-Qur\'an sejak dini melalui pembelajaran yang menyeturuh: tilawah, tahtida, dan tafsir.
Membina santri menjadi haftar dan haftarah yang mutqin (kokoh hafalamya) dan berakhlak Qur\'ani.
Mengajarkan metode pembacaan Al-Qur\'an yang sesuai dengan tapivid dan qira\'at yang mu\'tabarah.
Mengembangkan sistem pendidikan yang berlandaskan pada nilai-nilai warisan guru dan ulama terdanulu.
Menumbuhkan semangat dakwah dan pengabdian di tengah masyarakat melalui nilai-nilai Al-Qur\'an.
Menjaga dan melestarikan sanad kelimuan dalam pembelajaran Al-Qur\'an dan ilmu-ilmu keislaman lainnya.')) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Program Pendidikan Section -->
<section id="program-pendidikan" class="py-16 px-4 bg-gradient-to-b from-white to-blue-50">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center text-primary mb-4">Program Pendidikan</h2>
        <p class="text-center text-secondary mb-12 max-w-3xl mx-auto">
            Pilih program pendidikan yang sesuai dengan usia dan jenjang pendidikan Anda. Setiap program memiliki keunggulan dan spesialisasi tersendiri.
        </p>

        <!-- Program Pendidikan Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            @foreach($programPendidikan as $program => $data)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl border border-gray-100">
                <!-- Card Header -->
                <div class="{{ $data['color'] }} text-white p-6 text-center relative overflow-hidden">
                    <!-- Decorative Element -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-white/30"></div>
                    <div class="absolute -top-10 -right-10 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-10 -left-10 w-20 h-20 bg-white/10 rounded-full"></div>

                    <!-- Icon -->
                    <div class="relative z-10 flex justify-center mb-4">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="{{ $data['icon'] }} text-3xl"></i>
                        </div>
                    </div>

                    <!-- Program Name -->
                    <h3 class="text-xl font-bold mb-2 relative z-10">{{ $program }}</h3>
                    <p class="text-sm opacity-90 relative z-10">{{ $data['description'] }}</p>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <!-- Quick Info -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center bg-gray-50 rounded-lg p-3">
                            <div class="text-sm text-gray-500 mb-1">
                                <i class="fas fa-user-clock mr-1"></i> Usia
                            </div>
                            <div class="font-bold text-primary text-lg">{{ $data['usia'] }}</div>
                        </div>
                        <div class="text-center bg-gray-50 rounded-lg p-3">
                            <div class="text-sm text-gray-500 mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i> Durasi
                            </div>
                            <div class="font-bold text-primary text-lg">{{ $data['duration'] }}</div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                            Keunggulan Program
                        </h4>
                        <ul class="space-y-3">
                            @foreach($data['features'] as $feature)
                            <li class="flex items-start group">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-600">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Additional Info for Takhassus -->
                    @if($program == 'Takhassus Al-Quran')
                    <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-amber-500 text-xl mt-1 mr-3"></i>
                            </div>
                            <div>
                                <h5 class="font-semibold text-amber-800 mb-1">Persyaratan Khusus</h5>
                                <p class="text-sm text-amber-700">
                                    <strong>Usia Minimal:</strong> 17 tahun<br>
                                    <strong>Komitmen:</strong> Program intensif tahfizh<br>
                                    <strong>Evaluasi:</strong> Tes masuk khusus
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Card Footer -->

            </div>
            @endforeach
        </div>

        <!-- Statistics Section -->
      

        <!-- Info Box -->

    </div>
</section>


    <!-- Program Unggulan Section -->
    <section id="program" class="py-16 px-4">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-4">Program Unggulan</h2>
            <p class="text-center text-secondary mb-12">
                {{ $contentSettings->program_unggulan_deskripsi ?? 'Program-program unggulan yang ditawarkan oleh Pesantren AI-Our\'an Bani Syahid' }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @php
                    $programs = $contentSettings->program_unggulan ?? [];
                    $defaultPrograms = [
                        [
                            'nama' => 'Tahfıdzul Qur\'an',
                            'target' => 'Hafal 30 Juz dalam waktu 3-5 tahun',
                            'metode' => 'Talaqqi dan muraja\'ah harian bersama mu\'allim/ah',
                            'evaluasi' => 'Setoran harian, tasmî\' mingguan, dan ujian tahunan'
                        ],
                        [
                            'nama' => 'Qiraat Sab\'ah',
                            'target' => 'Menguasai tujuh qira\'at mutawatir sesuai riwayat yang sahih',
                            'metode' => 'Teori dan praktik qira\'at berdasarkan matan "Al-Syatibiyyah"',
                            'evaluasi' => 'Santri memahami perbedaan qiraat dan mampu membacanya dengan tepat'
                        ],
                        [
                            'nama' => 'Nagham',
                            'target' => 'Meningkatkan kualitas bacaan santri dengan irama yang sesuai kaidah tajwid dan nagham',
                            'metode' => 'Latihan rutin, lomba internal, dan pembinaan untuk Musabaqah Tilawatil Qur\'an (MTQ)',
                            'evaluasi' => 'Penguasaan berbagai jenis nagham: Bayati, Shoba, Hijaz, Mahawan, Bast, Sika, Jiranka'
                        ],
                        [
                            'nama' => 'Kajian Kitab Ulama Klasik (Turats)',
                            'target' => 'Santri memahami dasar-dasar ilmu Islam dari sumber klasik',
                            'metode' => 'Talaqqi (pengajian langsung) dan diskusi kitab kuning',
                            'evaluasi' => 'Pemahaman kitab turats dan aplikasi dalam kehidupan sehari-hari'
                        ]
                    ];

                    $displayPrograms = !empty($programs) ? $programs : $defaultPrograms;
                    $icons = ['fas fa-quran', 'fas fa-book-open', 'fas fa-music', 'fas fa-graduation-cap'];
                @endphp

                @foreach($displayPrograms as $index => $program)
                    <div class="bg-white rounded-xl shadow-lg p-6 transform transition duration-300 hover:scale-105">
                        <div class="flex items-center mb-4">
                            <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                                <i class="{{ $icons[$index % count($icons)] }} text-2xl text-primary"></i>
                            </div>
                            <h3 class="text-xl font-bold text-primary">{{ $program['nama'] ?? 'Program Unggulan' }}</h3>
                        </div>
                        @if(isset($program['deskripsi']))
                            <p class="text-secondary mb-4">{{ $program['deskripsi'] }}</p>
                        @endif
                        <ul class="text-secondary space-y-2">
                            @if(isset($program['target']))
                                <li><span class="font-semibold">Target:</span> {{ $program['target'] }}</li>
                            @endif
                            @if(isset($program['metode']))
                                <li><span class="font-semibold">Metode:</span> {{ $program['metode'] }}</li>
                            @endif
                            @if(isset($program['evaluasi']))
                                <li><span class="font-semibold">Evaluasi:</span> {{ $program['evaluasi'] }}</li>
                            @endif
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Kegiatan Pesantren Section -->
    <section id="kegiatan" class="py-16 px-4 bg-gradient-to-r from-primary/10 to-primary/20">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-4">Kegiatan Harian Pesantren</h2>
            <p class="text-center text-secondary mb-12">
                Jadwal rutin harian yang membentuk disiplin dan karakter Qur'ani santri
            </p>

            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    @php
                        $kegiatan = $contentSettings->kegiatan_pesantren ?? [];
                    @endphp

                    @if(count($kegiatan) > 0)
                        @foreach(array_slice($kegiatan, 0, 5) as $index => $item)
                        <div class="border-b border-gray-200 last:border-b-0">
                            <div class="p-6 hover:bg-gray-50 transition duration-300">
                                <div class="flex items-start">
                                    <div class="bg-primary text-white rounded-lg px-4 py-2 text-sm font-semibold mr-4 flex-shrink-0">
                                        {{ $item['waktu'] }}
                                    </div>
                                    <div class="flex-1">
                                        <ul class="space-y-2">
                                            @foreach($item['kegiatan'] as $kegiatanItem)
                                            <li class="flex items-start text-secondary">
                                                <i class="fas fa-circle text-primary text-xs mt-2 mr-3"></i>
                                                <span>{{ $kegiatanItem }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-500">Belum Ada Jadwal Kegiatan</h3>
                            <p class="text-gray-400 mt-2">Jadwal kegiatan sedang dalam proses persiapan</p>
                        </div>
                    @endif
                </div>

                <div class="mt-8 text-center">
                    <p class="text-secondary mb-4">Ingin melihat jadwal lengkap kegiatan harian? Login Terlebih Dahulu </p>
                    @auth
                        @if(auth()->user()->isCalonSantri() || auth()->user()->role === 'santri')
                        <a href="{{ route('santri.kegiatan.index') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300 inline-flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i> Lihat Jadwal Lengkap
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300 inline-flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Melihat Jadwal
                        </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300 inline-flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Melihat Jadwal
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 px-4 bg-white">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-4">Pertanyaan yang Sering Diajukan</h2>
            <p class="text-center text-secondary mb-12">
                Temukan jawaban untuk pertanyaan umum seputar pendaftaran dan kehidupan di pesantren
            </p>

            <div class="max-w-4xl mx-auto">
                <div class="faq-accordion" id="faqAccordion">
                    @php
                        $faqs = $contentSettings->faq ?? [];
                        $defaultFaqs = [
                            [
                                'pertanyaan' => 'Apa saja persyaratan pendaftaran santri baru?',
                                'jawaban' => 'Persyaratan pendaftaran meliputi: fotokopi akta kelahiran, kartu keluarga, ijazah terakhir, pas foto, dan mengisi formulir pendaftaran.'
                            ],
                            [
                                'pertanyaan' => 'Berapa biaya masuk pondok pesantren?',
                                'jawaban' => 'Biaya masuk berbeda-beda sesuai program yang dipilih. Silakan hubungi admin untuk informasi detail mengenai biaya.'
                            ],
                            [
                                'pertanyaan' => 'Apakah ada beasiswa untuk santri berprestasi?',
                                'jawaban' => 'Ya, kami menyediakan beasiswa untuk santri yang berprestasi baik akademik maupun non-akademik.'
                            ],
                            [
                                'pertanyaan' => 'Bagaimana sistem pembelajaran di pesantren?',
                                'jawaban' => 'Sistem pembelajaran menggunakan metode talaqqi (pengajian langsung) dan diskusi kitab kuning, dengan fokus pada tahfidz Al-Qur\'an dan penguasaan ilmu agama.'
                            ]
                        ];
                        $displayFaqs = !empty($faqs) ? $faqs : $defaultFaqs;
                    @endphp

                    @if(count($displayFaqs) > 0)
                        @foreach($displayFaqs as $index => $faq)
                        <div class="faq-item" data-faq-index="{{ $index }}">
                            <div class="faq-question" onclick="toggleFAQ({{ $index }})">
                                <div class="faq-number">{{ $index + 1 }}</div>
                                <div class="faq-text">{{ $faq['pertanyaan'] ?? 'Pertanyaan tidak tersedia' }}</div>
                                <div class="faq-icon">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="faq-answer" id="answer-{{ $index }}">
                                <div class="faq-answer-content">
                                    {!! nl2br(e($faq['jawaban'] ?? 'Jawaban tidak tersedia')) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada FAQ</h3>
                            <p class="text-gray-500">Belum ada pertanyaan yang tersedia saat ini.</p>
                        </div>
                    @endif
                </div>

                <!-- FAQ Call to Action -->
                <div class="mt-8 text-center">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center justify-center mb-4">
                            <i class="fas fa-question-circle text-blue-500 text-2xl mr-3"></i>
                            <h3 class="text-lg font-semibold text-blue-800">Masih Punya Pertanyaan?</h3>
                        </div>
                        <p class="text-blue-700 mb-4">Jika pertanyaan Anda tidak terjawab di FAQ, jangan ragu untuk menghubungi kami.</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">


                            @auth
                                @if(auth()->user()->isCalonSantri() || auth()->user()->role === 'santri')
                                <a href="{{ route('santri.faq.index') }}" class="bg-indigo-500 text-white px-6 py-2 rounded-lg hover:bg-indigo-600 transition duration-300 inline-flex items-center justify-center">
                                    <i class="fas fa-list mr-2"></i> FAQ Lengkap
                                </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Pendaftaran Section -->
    <section id="alur-pendaftaran" class="py-20 px-6 bg-gradient-to-b from-primary/10 via-white to-primary/5">
        <div class="container mx-auto">
            <h2 class="text-4xl font-extrabold text-center text-primary mb-4 tracking-wide">
                {{ $contentSettings->alur_pendaftaran_judul ?? 'Alur Pendaftaran' }}
            </h2>
            <p class="text-center text-gray-600 mb-16">
                {{ $contentSettings->alur_pendaftaran_deskripsi ?? 'Tahapan pendaftaran PPDB Pesantren Al-Qur\'an Bani Syahid' }}
            </p>

            <div class="relative max-w-5xl mx-auto">
                <!-- Garis penghubung vertikal -->
                <div class="absolute left-8 top-0 h-full w-1 bg-gradient-to-b from-primary/50 to-secondary/50 rounded-full"></div>

                <!-- Step Template -->
                <div class="space-y-12">
                    @php
                        $defaultSteps = [
                            'Membuat Akun' => 'Membuat akun pada website PPDB Pondok Pesantren Al Bani Syahid.',
                            'Isi Biodata' => 'Login kembali ke website PPDB Al Bani Syahid, lengkapi biodata dan kirim berkas.',
                            'Pembayaran' => 'Lakukan pembayaran melalui metode yang disediakan atau langsung ke pesantren.',
                            'Cetak Kartu Peserta' => 'Cetak kartu peserta yang berisi barcode dan informasi peserta.',
                            'Tes dan Wawancara' => 'Calon santri akan dipanggil oleh pihak pesantren untuk tes dan wawancara.',
                            'Pengumuman Kelulusan' => 'Calon santri dapat melihat hasil kelulusan di website PPDB Pondok Pesantren Al Bani Syahid.'
                        ];
                    @endphp

                    @foreach($defaultSteps as $stepNumber => $stepDescription)
                        <div class="relative flex items-start gap-6 group">
                            <div class="z-10 flex items-center justify-center w-14 h-14 bg-primary text-white font-bold rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">
                                {{ $loop->iteration }}
                            </div>
                            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex-1 border border-primary/10">
                                <h3 class="text-2xl font-semibold text-primary mb-2 group-hover:text-primary/90">{{ $stepNumber }}</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $stepDescription }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Informasi Biaya Takhossus Pesantren Plus Sekolah Section -->
    <section id="biaya"  class="py-16 px-4 bg-gradient-to-r from-primary/10 to-primary/20">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-4">Informasi Biaya Pondok Pesantren Al Bani</h2>
            <p class="text-center text-secondary mb-12">Informasi biaya lengkap untuk program Takhossus Pesantren dan Plus Sekolah</p>

            @forelse($packages as $package)
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-center text-primary mb-8">{{ $package->name }} - {{ $package->type_label }}</h3>

                @if($package->description)
                <p class="text-center text-secondary mb-6 max-w-2xl mx-auto">{{ $package->description }}</p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $colorClasses = [
                            'bg-gradient-to-br from-primary to-primary/80',
                            'bg-gradient-to-br from-secondary to-secondary/80',
                            'bg-gradient-to-br from-accent to-accent/80',
                            'bg-gradient-to-br from-primary to-accent',
                            'bg-gradient-to-br from-green-500 to-green-400',
                            'bg-gradient-to-br from-purple-500 to-purple-400',
                            'bg-gradient-to-br from-blue-500 to-blue-400',
                            'bg-gradient-to-br from-red-500 to-red-400',
                            'bg-gradient-to-br from-yellow-500 to-yellow-400',
                            'bg-gradient-to-br from-indigo-500 to-indigo-400'
                        ];
                    @endphp

                    @foreach($package->prices as $index => $price)
                    <div class="{{ $colorClasses[$index % count($colorClasses)] }} rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                        <div class="flex items-center mb-4">
                            @php
                                $icons = [
                                    'fas fa-money-bill-wave',
                                    'fas fa-graduation-cap',
                                    'fas fa-percentage',
                                    'fas fa-chart-line',
                                    'fas fa-book',
                                    'fas fa-tshirt',
                                    'fas fa-home',
                                    'fas fa-utensils',
                                    'fas fa-bus',
                                    'fas fa-user-graduate'
                                ];
                            @endphp
                            <i class="{{ $icons[$index % count($icons)] }} text-2xl mr-3"></i>
                            <div class="text-xl font-bold">{{ $price->item_name }}</div>
                        </div>
                        <div class="text-2xl font-bold mb-2">{{ $price->formatted_amount }}</div>
                        @if($price->description)
                        <div class="mt-4 pt-4 border-t border-white/30">
                            <p class="text-sm opacity-90">{{ $price->description }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            @if(!$loop->last)
            <div class="border-t border-gray-300 my-12"></div>
            @endif

            @empty
            <div class="text-center py-12">
                <i class="fas fa-info-circle text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-500 mb-2">Belum Ada Informasi Biaya</h3>
                <p class="text-gray-400">Informasi biaya sedang dalam proses persiapan</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Persyaratan Dokumen Section -->
    <section id="persyaratan" class="py-16 bg-gradient-to-b from-[#f7fafc] to-[#e6f0ea]">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-primary mb-4">
                {{ $contentSettings->persyaratan_dokumen_judul ?? 'Persyaratan Dokumen' }}
            </h2>
            <p class="text-center text-secondary mb-8 md:mb-12">
                {{ $contentSettings->persyaratan_dokumen_deskripsi ?? 'Dokumen-dokumen yang diperlukan untuk pendaftaran santri baru' }}
            </p>

            <div class="flex flex-wrap justify-center gap-4 md:gap-6 lg:gap-8">
                <!-- Card 1 - Formulir -->
                <div class="bg-white rounded-xl shadow-md border border-primary/20 hover:border-primary/40 hover:shadow-lg transition-all duration-300 w-full max-w-xs p-6 flex flex-col items-center">
                    <div class="icon-bg w-20 h-20 rounded-full flex items-center justify-center mb-4">
                        <img src="{{ $contentSettings->getFilePath('formulir') }}" alt="Formulir" class="w-12 h-12 object-contain">
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2 text-center">Formulir</h3>
                    <p class="text-secondary text-sm text-center">Bisa melalui offline atau website.</p>
                </div>

                <!-- Card 2 - Pas Foto -->
                <div class="bg-white rounded-xl shadow-md border border-primary/20 hover:border-primary/40 hover:shadow-lg transition-all duration-300 w-full max-w-xs p-6 flex flex-col items-center">
                    <div class="icon-bg w-20 h-20 rounded-full flex items-center justify-center mb-4">
                        <img src="{{ $contentSettings->getFilePath('pasfoto') }}" alt="Pas Foto" class="w-12 h-12 object-contain">
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2 text-center">Pas Foto 3x4</h3>
                    <p class="text-secondary text-sm text-center">Sebanyak 4 lembar.</p>
                </div>

                <!-- Card 3 - Akte Kelahiran -->
                <div class="bg-white rounded-xl shadow-md border border-primary/20 hover:border-primary/40 hover:shadow-lg transition-all duration-300 w-full max-w-xs p-6 flex flex-col items-center">
                    <div class="icon-bg w-20 h-20 rounded-full flex items-center justify-center mb-4">
                        <img src="{{ $contentSettings->getFilePath('akte') }}" alt="Akte Kelahiran" class="w-12 h-12 object-contain">
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2 text-center">Akte Kelahiran</h3>
                    <p class="text-secondary text-sm text-center">Dalam bentuk fotokopi.</p>
                </div>

                <!-- Card 4 - Kartu Keluarga -->
                <div class="bg-white rounded-xl shadow-md border border-primary/20 hover:border-primary/40 hover:shadow-lg transition-all duration-300 w-full max-w-xs p-6 flex flex-col items-center">
                    <div class="icon-bg w-20 h-20 rounded-full flex items-center justify-center mb-4">
                        <img src="{{ $contentSettings->getFilePath('kk') }}" alt="Kartu Keluarga" class="w-12 h-12 object-contain">
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2 text-center">Kartu Keluarga</h3>
                    <p class="text-secondary text-sm text-center">Fotokopi Kartu Keluarga.</p>
                </div>

                <!-- Card 5 - Ijazah -->
                <div class="bg-white rounded-xl shadow-md border border-primary/20 hover:border-primary/40 hover:shadow-lg transition-all duration-300 w-full max-w-xs p-6 flex flex-col items-center">
                    <div class="icon-bg w-20 h-20 rounded-full flex items-center justify-center mb-4">
                        <img src="{{ $contentSettings->getFilePath('ijazah') }}" alt="Ijazah" class="w-12 h-12 object-contain">
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2 text-center">SKL atau Ijazah</h3>
                    <p class="text-secondary text-sm text-center">SKL/Ijazah SD,SMP dan SMA, atau rapor terakhir bagi yang belum lulus.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white py-12 px-4">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Menu 1 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Tentang Kami</h3>
                    <ul class="space-y-2">
                        <li><a href="#visi-misi" class="hover:text-accent transition duration-300">Visi & Misi</a></li>
                        <li><a href="{{ route('beasiswa') }}" class="hover:text-accent transition duration-300">info Beasiswa</a></li>

                    </ul>
                </div>

                <!-- Menu 2 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Program</h3>
                    <ul class="space-y-2">
                        <li><a href="#program" class="hover:text-accent transition duration-300">Tahfidz Al-Qur'an</a></li>

                    </ul>
                </div>

                <!-- Menu 3 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Informasi</h3>
                    <ul class="space-y-2">
                        <li><a href="#alur-pendaftaran" class="hover:text-accent transition duration-300">Alur Pendaftaran</a></li>
                        <li><a href="#biaya" class="hover:text-accent transition duration-300">Biaya Pendidikan</a></li>
                        <li><a href="#persyaratan" class="hover:text-accent transition duration-300">Persyaratan</a></li>
                        <li><a href="#faq" class="hover:text-accent transition duration-300">FAQ</a></li>
                    </ul>
                </div>

                <!-- Menu 4 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <ul class="space-y-2">
                        <a href="https://www.google.com/maps/place/Pondok+Pesantren+Al-Qur'an+Bani+Syahid/@-6.3676771,106.8696904,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69ed654ce6786b:0x1019880ca4f9403b!8m2!3d-6.3676824!4d106.8722707!16s%2Fg%2F11f6m9qmmr?hl=id"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="hover:text-accent transition duration-300">
                           Alamat
                        </a>
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
                    href="https://www.instagram.com/ponpesalquranbanisyahid_/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-white hover:text-yellow-300 underline transition-colors duration-200"
                >
                    {{ $contentSettings->judul ?? 'Software Engineering Student' }}
                </a>. All rights reserved.
            </p>
        </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // FAQ Accordion Functionality
        function toggleFAQ(index) {
            const answer = document.getElementById(`answer-${index}`);
            const question = document.querySelector(`[data-faq-index="${index}"] .faq-question`);

            // Toggle active class
            question.classList.toggle('active');
            answer.classList.toggle('active');

            // Close other FAQs
            document.querySelectorAll('.faq-item').forEach((item, i) => {
                if (i !== index) {
                    const otherAnswer = document.getElementById(`answer-${i}`);
                    const otherQuestion = item.querySelector('.faq-question');
                    otherQuestion.classList.remove('active');
                    otherAnswer.classList.remove('active');
                }
            });
        }

        // Auto-open first FAQ on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Open first FAQ by default
            const firstFAQ = document.querySelector('[data-faq-index="0"]');
            if (firstFAQ) {
                toggleFAQ(0);
            }
        });

        // Smooth scroll for navigation
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
</body>
</html>

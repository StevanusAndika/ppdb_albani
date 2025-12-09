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
        .faq-item { border-bottom: 1px solid #e5e7eb; }
        .faq-item:last-child { border-bottom: none; }
        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: between;
            align-items: center;
            transition: all 0.3s ease;
        }
        .faq-question:hover { background-color: #f8fafc; }
        .faq-question.active { background-color: #f0f9ff; border-left: 4px solid #057572; }
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
        .faq-text { flex: 1; font-weight: 600; color: #1f2937; font-size: 1rem; }
        .faq-icon { transition: transform 0.3s ease; color: #057572; }
        .faq-question.active .faq-icon { transform: rotate(180deg); }
        .faq-answer { padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: all 0.3s ease; background-color: #fafafa; }
        .faq-answer.active { padding: 1.5rem; max-height: 500px; }
        .faq-answer-content { color: #4b5563; line-height: 1.6; border-left: 3px solid #d1d5db; padding-left: 1rem; }
        @media (max-width: 767px) {
            .nav-container { padding: 0.75rem 1rem; }
            .nav-logo { font-size: 1.1rem; }
            .mobile-menu-button { padding: 0.5rem; }
            .mobile-menu { border-radius: 1rem; margin-top: 0.75rem; padding: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
            .mobile-menu-item { padding: 0.75rem 1rem; border-radius: 0.75rem; margin-bottom: 0.25rem; }
            .faq-question { padding: 1rem; }
            .faq-text { font-size: 0.9rem; }
            .faq-answer.active { padding: 1rem; }
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
            {{ $hero['title'] ?? $contentSettings->judul ?? 'PPDB' }}
        </h1>
        <h2 class="text-2xl md:text-3xl font-semibold text-primary mb-8">
            {{ $hero['tagline'] ?? $contentSettings->tagline ?? 'Pesantren AI-Our\'an Bani Syahid 2025' }}
        </h2>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md mb-8">
            <p class="text-secondary text-lg mb-4">
                {{ $hero['description'] ?? $contentSettings->deskripsi ?? 'Sistem Penerimaan Peserta Didik Baru yang modern, mudah, dan terpercaya. Untuk Masa Depan Yang Lebih Baik' }}
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

    {{-- Visi & Misi: gunakan komponen agar konten bersumber dari DB atau default --}}
    @include('layouts.components.visi-misi-section', ['visiMisi' => $visiMisi ?? []])

    {{-- Program Pendidikan: include component yang mendukung data DB atau default --}}
    @include('layouts.components.program-pendidikan-section', ['programs' => $programs ?? $programPendidikan ?? []])


    {{-- Program Unggulan (komponen terpisah) --}}
    @include('layouts.components.program-unggulan-section', ['programs' => $programUnggulan ?? []])

    {{-- Kegiatan Pesantren (komponen terpisah) --}}
    @include('layouts.components.kegiatan-section', ['kegiatan' => $kegiatan ?? []])

    {{-- FAQ (komponen terpisah) --}}
    @include('layouts.components.faq-section', ['faqs' => $faqs ?? []])

    {{-- Alur Pendaftaran (komponen terpisah) --}}
    @include('layouts.components.alur-pendaftaran-section', ['alur' => $alur ?? []])

    {{-- Informasi Biaya (komponen terpisah) --}}
    @include('layouts.components.biaya-section', ['packages' => $packages ?? []])

    {{-- Persyaratan Dokumen (komponen terpisah) --}}
    @include('layouts.components.persyaratan-section', ['persyaratan' => $persyaratan ?? []])

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

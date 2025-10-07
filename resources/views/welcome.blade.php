<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Pesantren AI-Our'an Bani Syahid 2025</title>
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
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-6 rounded-full mx-4 mt-4 sticky top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-xl font-bold text-primary">PPDB 2025</div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-primary focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Desktop menu -->
            <div class="hidden md:flex space-x-8">
                <a href="#" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="#statistik" class="text-primary hover:text-secondary font-medium">Statistik</a>
                <a href="#biaya" class="text-primary hover:text-secondary font-medium">Biaya</a>
                <a href="#" class="text-primary hover:text-secondary font-medium">Biaya</a>
                <a href="#" class="text-primary hover:text-secondary font-medium">Kontak</a>
            </div>

            <div class="hidden md:block">
               <a href="{{ route('login') }}">
            <button class="bg-primary text-white px-6 py-2 rounded-full hover:bg-secondary transition duration-300">
                Login
            </button>
            </a>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4">
            <a href="#" class="block py-2 text-primary hover:text-secondary">Beranda</a>
            <a href="#statistik" class="block py-2 text-primary hover:text-secondary">Statistik</a>
            <a href="#biaya" class="block py-2 text-primary hover:text-secondary">Biaya</a>
            <a href="#biaya" class="block py-2 text-primary hover:text-secondary">Biaya</a>
            <a href="#" class="block py-2 text-primary hover:text-secondary">Kontak</a>
            <a href="{{ route('login') }}">
            <button class="bg-primary text-white px-6 py-2 rounded-full hover:bg-secondary transition duration-300">
                Login
            </button>
            </a>

        </div>
    </nav>

    <!-- Header Section -->
    <header class="py-12 px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-primary mb-4">PPDB</h1>
        <h2 class="text-2xl md:text-3xl font-semibold text-primary mb-8">Pesantren AI-Our'an Bani Syahid 2025</h2>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md mb-8">
            <p class="text-secondary text-lg mb-4">
               Sistem Penerimaan Peserta Didik Baru yang modern, mudah, dan terpercaya.
                <br>Untuk Masa Depan Yang Lebih Baik
            </p>
            <p class="text-accent italic">

            </p>
                <a href="{{ route('register') }}">
            <button class="bg-primary text-white px-4 py-1.5 rounded-full hover:bg-secondary transition duration-300 max-w-xs mx-auto my-3">
                Daftar Sekarang
            </button>
        </a>
        </div>


    </header>

    <!-- Statistik Section -->
    <section class="py-12 px-4 bg-gradient-to-r from-primary/10 to-primary/20">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-12">Statistik PPDB 2025</h2>
            <p class="text-center text-secondary mb-10">Data  terkini penerimaan peserta didik baru</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Stat 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center transform transition duration-300 hover:scale-105">
                    <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-school text-2xl text-primary"></i>
                    </div>
                    <div class="text-5xl font-bold text-primary mb-2">150 +</div>
                    <div class="text-xl font-medium text-secondary">Sekolah Terdaftar</div>
                </div>

                <!-- Stat 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center transform transition duration-300 hover:scale-105">
                    <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-graduate text-2xl text-primary"></i>
                    </div>
                    <div class="text-5xl font-bold text-primary mb-2">1,200</div>
                    <div class="text-xl font-medium text-secondary">Santri Terdaftar</div>
                </div>

                <!-- Stat 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center transform transition duration-300 hover:scale-105">
                    <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-2xl text-primary"></i>
                    </div>
                    <div class="text-5xl font-bold text-primary mb-2">95.5 %</div>
                    <div class="text-xl font-medium text-secondary">Tingkat Penerimaan</div>
                </div>

                <!-- Stat 4 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center transform transition duration-300 hover:scale-105">
                    <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-smile text-2xl text-primary"></i>
                    </div>
                    <div class="text-5xl font-bold text-primary mb-2">99.9%</div>
                    <div class="text-xl font-medium text-secondary">Kepuasan Pengguna</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Informasi Biaya Section -->
    <section class="py-16 px-4">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-4">Informasi Biaya</h2>
            <p class="text-center text-secondary mb-12">Informasi terkini penerimaan peserta didik baru</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-gradient-to-br from-primary to-primary/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-money-bill-wave text-2xl mr-3"></i>
                        <div class="text-4xl font-bold">150 +</div>
                    </div>
                    <div class="text-lg font-medium">Becken Tonszino</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">Detail informasi biaya untuk program ini.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-gradient-to-br from-secondary to-secondary/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-graduation-cap text-2xl mr-3"></i>
                        <div class="text-4xl font-bold">1,200</div>
                    </div>
                    <div class="text-lg font-medium">Smart Tonszins</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">Detail informasi biaya untuk program ini.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-gradient-to-br from-accent to-accent/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-percentage text-2xl mr-3"></i>
                        <div class="text-4xl font-bold">95.5 %</div>
                    </div>
                    <div class="text-lg font-medium">Tropical Presum</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">Detail informasi biaya untuk program ini.</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-gradient-to-br from-primary to-accent rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-chart-line text-2xl mr-3"></i>
                        <div class="text-4xl font-bold">99.9%</div>
                    </div>
                    <div class="text-lg font-medium">Kipsoosa Prospyxis</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">Detail informasi biaya untuk program ini.</p>
                    </div>
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
                        <li><a href="#" class="hover:text-accent transition duration-300">Sejarah</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Visi & Misi</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Struktur Organisasi</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Fasilitas</a></li>
                    </ul>
                </div>

                <!-- Menu 2 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Program</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-accent transition duration-300">Tahfidz AI-Our'an</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Pendidikan Formal</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Keterampilan Digital</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Ekstrakurikuler</a></li>
                    </ul>
                </div>

                <!-- Menu 3 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Informasi</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-accent transition duration-300">Berita & Acara</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Pengumuman</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Galeri</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Blog</a></li>
                    </ul>
                </div>

                <!-- Menu 4 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-accent transition duration-300">Alamat</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Telepon</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Email</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Sosial Media</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/20 mt-8 pt-8 text-center">
                <p>&copy; 2025 PPDB Pesantren AI-Our'an Bani Syahid. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>

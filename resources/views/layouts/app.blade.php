<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB PESANTREN AL-GURAN BANI SYAHID 2025')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mosque text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">PPDB PESANTREN AL-GURAN BANI SYAHID</h1>
                            <p class="text-sm text-gray-600">2025</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors {{ request()->is('/') ? 'text-blue-600 font-semibold' : '' }}">
                        Beranda
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Pendaftaran
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Informasi
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Kontak
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors {{ request()->is('dashboard*') ? 'text-blue-600 font-semibold' : '' }}">
                            Dashboard
                        </a>
                    @endauth
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- User Menu -->
                        <div class="flex items-center space-x-3">
                            <div class="hidden sm:flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-gray-700 font-medium hidden md:block">
                                    {{ Auth::user()->name }}
                                </span>
                            </div>

                            <!-- Dashboard Button -->
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                <span class="hidden sm:block">Dashboard</span>
                            </a>

                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-blue-600 font-medium transition-colors flex items-center">
                                    <i class="fas fa-sign-out-alt mr-1"></i>
                                    <span class="hidden sm:block">Logout</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Login & Register Buttons -->
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors {{ request()->is('login') ? 'text-blue-600 font-semibold' : '' }}">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Daftar
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="md:hidden mt-4 hidden" id="mobile-menu">
                <div class="flex flex-col space-y-3">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 font-medium py-2 border-b border-gray-200 {{ request()->is('/') ? 'text-blue-600 font-semibold' : '' }}">
                        Beranda
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium py-2 border-b border-gray-200">
                        Pendaftaran
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium py-2 border-b border-gray-200">
                        Informasi
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium py-2 border-b border-gray-200">
                        Kontak
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium py-2 border-b border-gray-200 {{ request()->is('dashboard*') ? 'text-blue-600 font-semibold' : '' }}">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium py-2 border-b border-gray-200 {{ request()->is('login') ? 'text-blue-600 font-semibold' : '' }}">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium text-center">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 flex-1">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>Terjadi kesalahan. Silakan periksa form Anda.</span>
                </div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo & Description -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mosque text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">PPDB PESANTREN AL-GURAN BANI SYAHID</h3>
                            <p class="text-sm text-gray-600">Tahun Ajaran 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Sistem Penerimaan Peserta Didik Baru Pesantren Al-Guran Bani Syahid.
                        Membentuk generasi yang berakhlak mulia, berilmu, dan bermanfaat bagi umat.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ url('/') }}" class="text-gray-600 hover:text-blue-600 transition-colors text-sm">
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors text-sm">
                                Jadwal Pendaftaran
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors text-sm">
                                Persyaratan
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors text-sm">
                                Biaya Pendidikan
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4">Kontak</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-2 text-gray-600 text-sm">
                            <i class="fas fa-map-marker-alt text-blue-600"></i>
                            <span>Jl. Pesantren No. 123, Bani Syahid</span>
                        </li>
                        <li class="flex items-center space-x-2 text-gray-600 text-sm">
                            <i class="fas fa-phone text-blue-600"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center space-x-2 text-gray-600 text-sm">
                            <i class="fas fa-envelope text-blue-600"></i>
                            <span>info@alguranbanisyahid.sch.id</span>
                        </li>
                    </ul>

                    <!-- Social Media -->
                    <div class="flex space-x-3 mt-4">
                        <a href="#" class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-pink-600 text-white rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                            <i class="fab fa-whatsapp text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-200 mt-8 pt-6 text-center">
                <p class="text-gray-600 text-sm">
                    &copy; 2025 PPDB Pesantren Al-Guran Bani Syahid. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const mobileMenuButton = document.querySelector('button[class*="md:hidden"]');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Auto-hide flash messages after 5 seconds
            const flashMessages = document.querySelectorAll('.bg-green-100, .bg-red-100, .bg-yellow-100');
            flashMessages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        message.remove();
                    }, 300);
                }, 5000);
            });

            // Add smooth scrolling to all links
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
        });
    </script>
</body>
</html>

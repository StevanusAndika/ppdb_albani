<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | PPDB Pesantren AI-Our'an Bani Syahid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#ef4444',
                        'secondary': '#5B5B5B',
                        'accent': '#9D9D9D',
                        'white': '#FFFFFF'
                    }
                }
            }
        }
    </script>
    <style>
        .error-bg-403 {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .shake-animation {
            animation: shake 0.8s ease-in-out infinite alternate;
        }
        @keyframes shake {
            0% { transform: translateX(-8px); }
            100% { transform: translateX(8px); }
        }
        .text-shadow {
            text-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        .slide-up {
            animation: slideUp 1s ease-out 0.5s both;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .error-container {
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="error-bg-403 error-container flex flex-col">
        <!-- Header Error -->
        <div class="flex-grow flex items-center justify-center p-4">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Error Icon -->
                <div class="mb-8">
                    <div class="inline-block p-6 rounded-full bg-white/20 backdrop-blur-sm">
                        <i class="fas fa-ban text-white text-5xl"></i>
                    </div>
                </div>

                <!-- Error Code -->
                <div class="shake-animation mb-6">
                    <div class="text-[150px] md:text-[250px] lg:text-[300px] font-bold text-white text-shadow">
                        403
                    </div>
                </div>

                <!-- Error Message -->
                <div class="slide-up">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Akses Ditolak
                    </h1>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 mb-8">
                        <p class="text-white text-lg mb-4">
                            Anda tidak memiliki izin untuk mengakses halaman ini.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-6">
                            <a href="/" class="bg-white text-red-600 px-6 py-3 rounded-full hover:bg-gray-100 transition duration-300 font-semibold inline-flex items-center justify-center">
                                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                            </a>
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="bg-red-600 text-white px-6 py-3 rounded-full hover:bg-red-700 transition duration-300 font-semibold inline-flex items-center justify-center">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard Admin
                                    </a>
                                @elseif(auth()->user()->isCalonSantri() || auth()->user()->role === 'santri')
                                    <a href="{{ route('santri.dashboard') }}" class="bg-red-600 text-white px-6 py-3 rounded-full hover:bg-red-700 transition duration-300 font-semibold inline-flex items-center justify-center">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard Santri
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-red-900 text-white py-8 px-4">
            <div class="container mx-auto">
                <div class="text-center">
                    <p class="text-sm md:text-base">
                        <i class="fas fa-shield-alt mr-2"></i>
                        &copy; <?php echo date('Y'); ?> PPDB Pesantren AI-Our'an Bani Syahid. All rights reserved.
                    </p>
                    <p class="text-xs text-white/70 mt-1">
                        Error 403 • <?php echo date('H:i:s'); ?>
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

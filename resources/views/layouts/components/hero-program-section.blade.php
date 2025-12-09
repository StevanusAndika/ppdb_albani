<!-- Hero Section for Program Pendidikan -->
<section id="hero-program" class="relative min-h-screen bg-gradient-to-br from-primary via-teal-600 to-blue-700 overflow-hidden flex items-center py-12 md:py-0">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white opacity-5 rounded-full -ml-32 -mb-32"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-blue-400 opacity-10 rounded-full blur-3xl"></div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
            <!-- Left Content -->
            <div class="text-white">
                <div class="inline-block bg-white/20 backdrop-blur-md px-4 py-2 rounded-full mb-6 border border-white/30">
                    <span class="text-sm font-semibold">🎓 Program Pendidikan Unggulan</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Raih Masa Depan 
                    <span class="block text-yellow-300 drop-shadow-lg">Cemerlang Bersama Kami</span>
                </h1>
                
                <p class="text-lg md:text-xl text-white/90 mb-8 leading-relaxed max-w-lg">
                    Pesantren AI-Our'an Bani Syahid menawarkan program pendidikan terpadu yang menggabungkan keilmuan duniawi dengan pendalaman Al-Qur'an untuk membangun generasi yang cerdas, berkarakter, dan religius.
                </p>

                <!-- Key Features -->
                <div class="space-y-3 mb-8">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-yellow-300 text-xl"></i>
                        <span class="text-white">Program MTS, MA, dan Takhassus Al-Qur'an</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-yellow-300 text-xl"></i>
                        <span class="text-white">Kurikulum Nasional dengan Pendekatan Diniyah</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-yellow-300 text-xl"></i>
                        <span class="text-white">Pengajar Berpengalaman dan Bersertifikat</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-yellow-300 text-xl"></i>
                        <span class="text-white">Asrama Penuh dengan Fasilitas Lengkap</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="inline-block bg-yellow-400 text-primary font-bold px-8 py-3 rounded-lg hover:bg-yellow-300 transition duration-300 text-center shadow-lg hover:shadow-xl">
                        <i class="fas fa-arrow-right mr-2"></i> Daftar Sekarang
                    </a>
                    <a href="#program-pendidikan" class="inline-block bg-white/20 backdrop-blur-md text-white font-bold px-8 py-3 rounded-lg border-2 border-white hover:bg-white/30 transition duration-300 text-center">
                        <i class="fas fa-arrow-down mr-2"></i> Lihat Program
                    </a>
                </div>

                <!-- Trust Badge -->
                <div class="mt-8 flex items-center gap-4 text-white/80 text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-yellow-300"></i>
                        <span>1000+ Santri Aktif</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-medal text-yellow-300"></i>
                        <span>30+ Tahun Berdiri</span>
                    </div>
                </div>
            </div>

            <!-- Right Image/Illustration -->
            <div class="relative h-96 md:h-full flex items-center justify-center">
                <!-- Floating Cards Container -->
                <div class="relative w-full h-96">
                    <!-- Card 1 - MTS -->
                    <div class="absolute top-0 left-0 w-72 bg-white rounded-xl shadow-2xl p-6 transform hover:scale-105 transition duration-300 hover:shadow-3xl border-2 border-yellow-300/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-primary">MTS Bani Syahid</h3>
                            <i class="fas fa-school text-3xl text-blue-500"></i>
                        </div>
                        <p class="text-gray-700 text-sm mb-4">Pendidikan Menengah Pertama dengan Fokus Tahfidz</p>
                        <div class="flex gap-2">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">12-15 Tahun</span>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">3 Tahun</span>
                        </div>
                    </div>

                    <!-- Card 2 - MA -->
                    <div class="absolute top-32 right-0 w-72 bg-white rounded-xl shadow-2xl p-6 transform hover:scale-105 transition duration-300 hover:shadow-3xl border-2 border-purple-300/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-primary">MA Bani Syahid</h3>
                            <i class="fas fa-graduation-cap text-3xl text-purple-500"></i>
                        </div>
                        <p class="text-gray-700 text-sm mb-4">Pendidikan Menengah Atas Program IPA/IPS</p>
                        <div class="flex gap-2">
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">15-18 Tahun</span>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">3 Tahun</span>
                        </div>
                    </div>

                    <!-- Card 3 - Takhassus -->
                    <div class="absolute bottom-0 left-1/4 w-72 bg-white rounded-xl shadow-2xl p-6 transform hover:scale-105 transition duration-300 hover:shadow-3xl border-2 border-amber-300/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-primary">Takhassus Al-Qur'an</h3>
                            <i class="fas fa-book-quran text-3xl text-amber-500"></i>
                        </div>
                        <p class="text-gray-700 text-sm mb-4">Program Khusus Tahfidz & Qiraat Sab'ah</p>
                        <div class="flex gap-2">
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-semibold">17+ Tahun</span>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">3-5 Tahun</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="absolute bottom-0 left-0 right-0 h-24 bg-white" style="clip-path: polygon(0 40%, 100% 0, 100% 100%, 0 100%);"></div>
</section>

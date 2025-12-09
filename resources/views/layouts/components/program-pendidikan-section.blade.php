<!-- Program Pendidikan Section -->
<section id="program-pendidikan" class="py-16 px-4 bg-gradient-to-b from-white to-blue-50">
    <div class="container mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">📚 Program Pendidikan</h2>
            <p class="text-center text-gray-700 text-lg mb-2 max-w-3xl mx-auto">
                Pilih program pendidikan yang sesuai dengan usia dan jenjang pendidikan Anda
            </p>
            <p class="text-center text-gray-600 max-w-3xl mx-auto">
                Setiap program dirancang khusus untuk mengembangkan potensi akademik dan spiritual santri dengan pendekatan holistik dan berbasis Al-Qur'an
            </p>
        </div>

        <!-- Program Pendidikan Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            @forelse($programs ?? [] as $key => $program)
                @php
                    // Normalize different possible shapes for program data
                    // $program can be an associative array with keys like 'title' or 'nama',
                    // or the parent array key can be the title (old format in welcome.blade.php)
                    $title = null;
                    $description = null;
                    $duration = null;
                    $advantages = [];
                    $image = null;

                    if (is_array($program)) {
                        $title = $program['title'] ?? $program['nama'] ?? $program['name'] ?? null;
                        $description = $program['description'] ?? $program['deskripsi'] ?? $program['target'] ?? $program['metode'] ?? null;
                        $duration = $program['duration'] ?? $program['durasi'] ?? null;
                        if (!empty($program['advantages'])) {
                            $advantages = (array) $program['advantages'];
                        } elseif (!empty($program['features'])) {
                            $advantages = (array) $program['features'];
                        } elseif (!empty($program['keunggulan'])) {
                            $advantages = (array) $program['keunggulan'];
                        }
                        $image = $program['image'] ?? $program['gambar'] ?? null;
                    } else {
                        // If program is not array (unlikely), cast to string
                        $title = (string) $program;
                    }

                    // If no title found in value, fall back to the key from parent array
                    if (empty($title) && !empty($key) && is_string($key)) {
                        $title = $key;
                    }

                    // Final sensible defaults
                    $title = $title ?? 'Program';
                    $description = $description ?? ($program['description'] ?? null) ?? '';
                @endphp

                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl border border-gray-100">
                    <!-- Card Header with Image or Color -->
                    <div class="relative h-40 bg-gradient-to-br from-primary to-teal-700 text-white p-6 text-center overflow-hidden">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-white/30"></div>
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white/10 rounded-full"></div>

                        <!-- Program Image if available -->
                        @if(!empty($image))
                            <img src="{{ asset($image) }}" alt="{{ $title }}" class="absolute inset-0 w-full h-full object-cover opacity-40">
                        @else
                            <!-- Icon Fallback -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                <i class="fas fa-book-open text-6xl"></i>
                            </div>
                        @endif

                        <!-- Header Content -->
                            <div class="relative z-10 flex flex-col justify-between h-full">
                            <div>
                                <h3 class="text-2xl font-bold mb-1">{{ $title }}</h3>
                                <p class="text-sm opacity-95">{{ $description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <!-- Quick Info Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="text-center bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200">
                                <div class="text-xs text-gray-600 mb-1 font-semibold">
                                    <i class="fas fa-users mr-1 text-blue-600"></i> JENJANG
                                </div>
                                <div class="font-bold text-primary text-sm">{{ $program['title'] ?? 'Program' }}</div>
                            </div>
                            <div class="text-center bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                                <div class="text-xs text-gray-600 mb-1 font-semibold">
                                    <i class="fas fa-calendar-alt mr-1 text-green-600"></i> DURASI
                                </div>
                                <div class="font-bold text-primary text-sm">{{ $program['duration'] ?? '3 Tahun' }}</div>
                            </div>
                        </div>

                        <!-- Keunggulan Program -->
                        @if(!empty($advantages))
                            <div class="mb-6">
                                <h4 class="font-bold text-gray-800 mb-3 flex items-center text-sm">
                                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                                    KEUNGGULAN PROGRAM
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($advantages as $advantage)
                                        <li class="flex items-start group text-sm">
                                            <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2 group-hover:scale-110 transition-transform flex-shrink-0"></i>
                                            <span class="text-gray-700">{{ $advantage }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- CTA Button -->
                        <div class="pt-4 border-t border-gray-200">
                            <a href="{{ route('register') }}" class="block w-full text-center bg-gradient-to-r from-primary to-teal-700 text-white py-2 px-4 rounded-lg font-semibold hover:shadow-lg transition duration-300 text-sm">
                                <i class="fas fa-arrow-right mr-2"></i> Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback to default programs if database is empty -->
                @php
                    $defaultPrograms = [
                        [
                            'title' => 'MTS Bani Syahid',
                            'description' => 'Madrasah Tsanawiyah untuk pendidikan menengah pertama',
                            'duration' => '3 Tahun',
                            'advantages' => ['Kurikulum Diniyah Terpadu', 'Tahfizh Juz 30', 'Penguatan Bahasa Arab', 'Pendidikan Karakter Islami'],
                            'image' => null
                        ],
                        [
                            'title' => 'MA Bani Syahid',
                            'description' => 'Madrasah Aliyah untuk pendidikan menengah atas',
                            'duration' => '3 Tahun',
                            'advantages' => ['Program IPA/IPS', 'Tahfizh Minimal 5 Juz', 'Bahasa Arab & Inggris', 'Persiapan Kuliah'],
                            'image' => null
                        ],
                        [
                            'title' => 'Takhassus Al-Quran',
                            'description' => 'Program khusus tahfizh dan pendalaman Al-Quran',
                            'duration' => '3-5 Tahun',
                            'advantages' => ['Target Hafal 30 Juz', 'Qiraat Sab\'ah', 'Ilmu Tajwid Mendalam', 'Sanad Keilmuan'],
                            'image' => null
                        ]
                    ];
                @endphp
                
                @foreach($defaultPrograms as $program)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl border border-gray-100">
                        <!-- Card Header -->
                        <div class="relative h-40 bg-gradient-to-br from-primary to-teal-700 text-white p-6 text-center overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-white/30"></div>
                            <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full"></div>
                            <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white/10 rounded-full"></div>
                            
                            <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                <i class="fas fa-book-open text-6xl"></i>
                            </div>

                            <div class="relative z-10 flex flex-col justify-between h-full">
                                <div>
                                    <h3 class="text-2xl font-bold mb-1">{{ $program['title'] }}</h3>
                                    <p class="text-sm opacity-95">{{ $program['description'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="text-center bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200">
                                    <div class="text-xs text-gray-600 mb-1 font-semibold">
                                        <i class="fas fa-users mr-1 text-blue-600"></i> JENJANG
                                    </div>
                                    <div class="font-bold text-primary text-sm">{{ $program['title'] }}</div>
                                </div>
                                <div class="text-center bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                                    <div class="text-xs text-gray-600 mb-1 font-semibold">
                                        <i class="fas fa-calendar-alt mr-1 text-green-600"></i> DURASI
                                    </div>
                                    <div class="font-bold text-primary text-sm">{{ $program['duration'] }}</div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h4 class="font-bold text-gray-800 mb-3 flex items-center text-sm">
                                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                                    KEUNGGULAN PROGRAM
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($program['advantages'] as $advantage)
                                        <li class="flex items-start group text-sm">
                                            <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2 group-hover:scale-110 transition-transform flex-shrink-0"></i>
                                            <span class="text-gray-700">{{ $advantage }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <a href="{{ route('register') }}" class="block w-full text-center bg-gradient-to-r from-primary to-teal-700 text-white py-2 px-4 rounded-lg font-semibold hover:shadow-lg transition duration-300 text-sm">
                                    <i class="fas fa-arrow-right mr-2"></i> Daftar Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <!-- Info Box -->
        <div class="bg-gradient-to-r from-blue-50 to-teal-50 border-2 border-primary rounded-xl p-8 mt-12">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-lightbulb text-primary text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary mb-2">💡 Memilih Program yang Tepat</h3>
                    <p class="text-gray-700 mb-3">
                        Setiap program dirancang untuk memenuhi kebutuhan pendidikan santri dengan fokus pada pembangunan karakter islami, penguasaan Al-Qur'an, dan kecakapan akademik. Tim konselor kami siap membantu Anda memilih program yang paling sesuai dengan potensi dan minat santri.
                    </p>
                    <a href="https://wa.me/628123456789?text=Saya%20ingin%20bertanya%20tentang%20program%20pendidikan" target="_blank" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition duration-300">
                        <i class="fab fa-whatsapp mr-2"></i> Konsultasi Gratis
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

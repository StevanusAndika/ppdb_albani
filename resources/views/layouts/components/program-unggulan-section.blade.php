<!-- Program Unggulan Section -->
<section id="program" class="py-16 px-4">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center text-primary mb-4">Program Unggulan</h2>
        <p class="text-center text-secondary mb-12">
            {{ $contentSettings->program_unggulan_deskripsi ?? 'Program-program unggulan yang ditawarkan oleh Pesantren AI-Our\'an Bani Syahid' }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @php
                $displayPrograms = !empty($programs) ? $programs : ($contentSettings->program_unggulan ?? []);
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

                if (empty($displayPrograms)) {
                    $displayPrograms = $defaultPrograms;
                }

                $icons = ['fas fa-quran', 'fas fa-book-open', 'fas fa-music', 'fas fa-graduation-cap'];
            @endphp

            @foreach($displayPrograms as $index => $program)
                @php
                    $pname = $program['nama'] ?? $program['name'] ?? $program['title'] ?? 'Program Unggulan';
                    $pdesc = $program['deskripsi'] ?? $program['deskripsi'] ?? $program['description'] ?? ($program['target'] ?? null);
                @endphp
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="{{ $icons[$index % count($icons)] }} text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold text-primary">{{ $pname }}</h3>
                    </div>
                    @if(!empty($pdesc))
                        <p class="text-secondary mb-4">{{ $pdesc }}</p>
                    @endif
                    <ul class="text-secondary space-y-2">
                        @if(!empty($program['target']))
                            <li><span class="font-semibold">Target:</span> {{ $program['target'] }}</li>
                        @endif
                        @if(!empty($program['metode']))
                            <li><span class="font-semibold">Metode:</span> {{ $program['metode'] }}</li>
                        @endif
                        @if(!empty($program['evaluasi']))
                            <li><span class="font-semibold">Evaluasi:</span> {{ $program['evaluasi'] }}</li>
                        @endif
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>

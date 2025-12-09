<!-- FAQ Section -->
<section id="faq" class="py-16 px-4 bg-white">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center text-primary mb-4">Pertanyaan yang Sering Diajukan</h2>
        <p class="text-center text-secondary mb-12">Temukan jawaban untuk pertanyaan umum seputar pendaftaran dan kehidupan di pesantren</p>

        <div class="max-w-4xl mx-auto">
            <div class="faq-accordion" id="faqAccordion">
                @php
                    $displayFaqs = !empty($faqs) ? $faqs : ($contentSettings->faq ?? []);
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
                    if (empty($displayFaqs)) { $displayFaqs = $defaultFaqs; }
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

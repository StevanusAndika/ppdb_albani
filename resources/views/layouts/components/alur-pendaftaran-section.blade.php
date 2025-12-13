<!-- Alur Pendaftaran Section -->
<section id="alur-pendaftaran" class="py-20 px-6 bg-gradient-to-b from-primary/10 via-white to-primary/5">
    <div class="container mx-auto">
        <h2 class="text-4xl font-extrabold text-center text-primary mb-4 tracking-wide">{{ $contentSettings->alur_pendaftaran_judul ?? 'Alur Pendaftaran' }}</h2>
        <p class="text-center text-gray-600 mb-16">{{ $contentSettings->alur_pendaftaran_deskripsi ?? 'Tahapan pendaftaran PPDB Pesantren Al-Qur\'an Bani Syahid' }}</p>

        <div class="relative max-w-5xl mx-auto">
            <div class="absolute left-8 top-0 h-full w-1 bg-gradient-to-b from-primary/50 to-secondary/50 rounded-full"></div>

            <div class="space-y-12">
                @php
                    $steps = [];
                    if (!empty($alur) && is_array($alur)) {
                        // Handle format baru dari database: [['judul' => '...', 'deskripsi' => '...']]
                        foreach ($alur as $item) {
                            if (is_array($item) && isset($item['judul']) && isset($item['deskripsi'])) {
                                $steps[] = [
                                    'judul' => $item['judul'],
                                    'deskripsi' => $item['deskripsi']
                                ];
                            } elseif (is_string($item)) {
                                // Handle format string langsung
                                $steps[] = [
                                    'judul' => 'Langkah ' . (count($steps) + 1),
                                    'deskripsi' => $item
                                ];
                            }
                        }
                    }
                    
                    // Fallback jika tidak ada data
                    if (empty($steps)) {
                        $steps = [
                            ['judul' => 'Membuat Akun', 'deskripsi' => 'Membuat akun pada website PPDB Pondok Pesantren Al Bani Syahid.'],
                            ['judul' => 'Isi Biodata', 'deskripsi' => 'Login kembali ke website PPDB Al Bani Syahid, lengkapi biodata dan kirim berkas.'],
                            ['judul' => 'Pembayaran', 'deskripsi' => 'Lakukan pembayaran melalui metode yang disediakan atau langsung ke pesantren.'],
                            ['judul' => 'Cetak Kartu Peserta', 'deskripsi' => 'Cetak kartu peserta yang berisi barcode dan informasi peserta.'],
                            ['judul' => 'Tes dan Wawancara', 'deskripsi' => 'Calon santri akan dipanggil oleh pihak pesantren untuk tes dan wawancara.'],
                            ['judul' => 'Pengumuman Kelulusan', 'deskripsi' => 'Calon santri dapat melihat hasil kelulusan di website PPDB Pondok Pesantren Al Bani Syahid.']
                        ];
                    }
                @endphp

                @foreach($steps as $step)
                    <div class="relative flex items-start gap-6 group">
                        <div class="z-10 flex items-center justify-center w-14 h-14 bg-primary text-white font-bold rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">{{ $loop->iteration }}</div>
                        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex-1 border border-primary/10">
                            <h3 class="text-2xl font-semibold text-primary mb-2 group-hover:text-primary/90">{{ $step['judul'] ?? 'Langkah ' . $loop->iteration }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $step['deskripsi'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Visi & Misi Section (Restyled) -->
<section id="visi-misi" class="py-16 px-4 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-primary mb-3">Visi & Misi</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Komitmen kami membentuk generasi Qur'ani yang cerdas, berakhlak, dan berdedikasi.</p>
        </div>

        <div class="grid grid-cols-1 gap-8 items-center justify-center">
            <!-- Visi Card -->
            <div class="col-span-1 mx-auto w-full">
                <div class="bg-white rounded-2xl shadow-md p-6 h-full border border-primary/10 w-full">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center mr-4">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="text-xl font-bold text-primary">Visi</h3>
                    </div>
                    <div class="text-gray-700">
                        @if(!empty($visiMisi['visi']))
                            <p class="leading-relaxed">{!! nl2br(e($visiMisi['visi'])) !!}</p>
                        @else
                            <p class="leading-relaxed">Menjadi lembaga pendidikan Islam terpadu yang menghasilkan generasi Qur'ani yang cerdas, berkarakter mulia, dan berdedikasi tinggi dalam mengabdi kepada Islam dan masyarakat.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Misi List Card -->
            <div class="col-span-1 md:col-span-2">
                <div class="bg-white rounded-2xl shadow-md p-6 border border-primary/10">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center mr-4">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="text-xl font-bold text-primary">Misi</h3>
                    </div>

                    <div>
                        @if(!empty($visiMisi['misi']) && count($visiMisi['misi'])>0)
                            <ul class="space-y-3">
                                @foreach($visiMisi['misi'] as $index => $misi)
                                    @if(!empty($misi))
                                        <li class="flex items-start gap-4">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-semibold">{{ $index+1 }}</div>
                                            <div class="text-gray-700">{{ $misi }}</div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <ul class="space-y-3">
                                <li class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-semibold">1</div>
                                    <div class="text-gray-700">Menyelenggarakan pendidikan yang mengintegrasikan ilmu pengetahuan dan nilai-nilai Islam</div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-semibold">2</div>
                                    <div class="text-gray-700">Membimbing siswa untuk menghafal Al-Qur'an dengan tartil dan pemahaman mendalam</div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-semibold">3</div>
                                    <div class="text-gray-700">Mengembangkan karakter islami yang kuat dan berakhlak mulia</div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-semibold">4</div>
                                    <div class="text-gray-700">Menciptakan lingkungan belajar yang kondusif, aman, dan penuh kasih sayang</div>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Values -->
        <div class="mt-12">
            <h4 class="text-lg font-bold text-primary mb-6 text-center">Nilai-Nilai Kami</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 mx-auto rounded-lg bg-blue-500 text-white flex items-center justify-center mb-3">
                        <i class="fas fa-book-quran"></i>
                    </div>
                    <h5 class="font-semibold text-primary mb-1">Qur'ani</h5>
                    <p class="text-sm text-gray-600">Al-Qur'an sebagai pusat pembelajaran</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 mx-auto rounded-lg bg-green-500 text-white flex items-center justify-center mb-3">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h5 class="font-semibold text-primary mb-1">Amanah</h5>
                    <p class="text-sm text-gray-600">Integritas dan tanggung jawab</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 mx-auto rounded-lg bg-yellow-500 text-white flex items-center justify-center mb-3">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h5 class="font-semibold text-primary mb-1">Cerdas</h5>
                    <p class="text-sm text-gray-600">Keseimbangan akademik dan spiritual</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 mx-auto rounded-lg bg-purple-500 text-white flex items-center justify-center mb-3">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h5 class="font-semibold text-primary mb-1">Bermartabat</h5>
                    <p class="text-sm text-gray-600">Menjunjung harkat dan martabat</p>
                </div>
            </div>
        </div>
    </div>
</section>

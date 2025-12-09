<!-- Kegiatan Pesantren Section -->
<section id="kegiatan" class="py-16 px-4 bg-gradient-to-r from-primary/10 to-primary/20">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center text-primary mb-4">Kegiatan Harian Pesantren</h2>
        <p class="text-center text-secondary mb-12">Jadwal rutin harian yang membentuk disiplin dan karakter Qur'ani santri</p>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                @php
                    $displayKegiatan = !empty($kegiatan) ? $kegiatan : ($contentSettings->kegiatan_pesantren ?? []);
                @endphp

                @if(count($displayKegiatan) > 0)
                    @foreach(array_slice($displayKegiatan, 0, 5) as $index => $item)
                    <div class="border-b border-gray-200 last:border-b-0">
                        <div class="p-6 hover:bg-gray-50 transition duration-300">
                            <div class="flex items-start">
                                <div class="bg-primary text-white rounded-lg px-4 py-2 text-sm font-semibold mr-4 flex-shrink-0">
                                    {{ $item['waktu'] ?? 'Waktu' }}
                                </div>
                                <div class="flex-1">
                                    <ul class="space-y-2">
                                        @foreach($item['kegiatan'] ?? [] as $kegiatanItem)
                                        <li class="flex items-start text-secondary">
                                            <i class="fas fa-circle text-primary text-xs mt-2 mr-3"></i>
                                            <span>{{ $kegiatanItem }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-500">Belum Ada Jadwal Kegiatan</h3>
                        <p class="text-gray-400 mt-2">Jadwal kegiatan sedang dalam proses persiapan</p>
                    </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                <p class="text-secondary mb-4">Ingin melihat jadwal lengkap kegiatan harian? Login Terlebih Dahulu </p>
                @auth
                    @if(auth()->user()->isCalonSantri() || auth()->user()->role === 'santri')
                    <a href="{{ route('santri.kegiatan.index') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300 inline-flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i> Lihat Jadwal Lengkap
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300 inline-flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Melihat Jadwal
                    </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300 inline-flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Melihat Jadwal
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Persyaratan Dokumen Section -->
<section id="persyaratan" class="py-16 bg-gradient-to-b from-[#f7fafc] to-[#e6f0ea]">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-primary mb-4">{{ $contentSettings->persyaratan_dokumen_judul ?? 'Persyaratan Dokumen' }}</h2>
        <p class="text-center text-secondary mb-8 md:mb-12">{{ $contentSettings->persyaratan_dokumen_deskripsi ?? 'Dokumen-dokumen yang diperlukan untuk pendaftaran santri baru' }}</p>

        <div class="flex flex-wrap justify-center gap-4 md:gap-6 lg:gap-8">
            @php $displayPersyaratan = !empty($persyaratan) ? $persyaratan : [];
            if(empty($displayPersyaratan)) {
                // fallback to existing contentSettings images
                $displayPersyaratan = [
                    ['key' => 'formulir', 'title' => 'Formulir', 'img' => $contentSettings->getFilePath('formulir')],
                    ['key' => 'pasfoto', 'title' => 'Pas Foto 3x4', 'img' => $contentSettings->getFilePath('pasfoto')],
                    ['key' => 'akte', 'title' => 'Akte Kelahiran', 'img' => $contentSettings->getFilePath('akte')],
                    ['key' => 'kk', 'title' => 'Kartu Keluarga', 'img' => $contentSettings->getFilePath('kk')],
                    ['key' => 'ijazah', 'title' => 'SKL atau Ijazah', 'img' => $contentSettings->getFilePath('ijazah')]
                ];
            }
            @endphp

            @foreach($displayPersyaratan as $item)
                <div class="bg-white rounded-xl shadow-md border border-primary/20 hover:border-primary/40 hover:shadow-lg transition-all duration-300 w-full max-w-xs p-6 flex flex-col items-center">
                    <div class="icon-bg w-20 h-20 rounded-full flex items-center justify-center mb-4">
                        @if(!empty($item['img']))
                            <img src="{{ asset($item['img']) }}" alt="{{ $item['title'] ?? 'Dokumen' }}" class="w-12 h-12 object-contain" onerror="this.onerror=null; this.src='{{ asset('images/default/' . ($item['key'] ?? 'document') . '.png') }}';">
                        @elseif(!empty($item['key']) && method_exists($contentSettings, 'getFilePath'))
                            <img src="{{ asset($contentSettings->getFilePath($item['key'])) }}" alt="{{ $item['title'] ?? 'Dokumen' }}" class="w-12 h-12 object-contain">
                        @endif
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2 text-center">{{ $item['title'] ?? 'Dokumen' }}</h3>
                    <p class="text-secondary text-sm text-center">{{ $item['note'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

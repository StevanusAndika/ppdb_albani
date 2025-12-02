<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-primary">Kelengkapan Dokumen</h3>
        <div class="flex gap-2">
            @if($registration)
            @if($registration && $registration->hasAllDocuments())
            <button onclick="downloadAllDocuments()" class="bg-purple-600 text-white px-3 py-1 rounded-full hover:bg-purple-700 transition duration-300 text-sm flex items-center">
                <i class="fas fa-file-archive mr-1"></i> Download Semua Data ZIP
            </button>
            @endif
            <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary text-sm font-medium bg-gray-100 px-3 py-1 rounded-full">
                Kelola Dokumen
            </a>
            @endif
        </div>
    </div>

    @if($registration)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $documents = [
                'kartu_keluarga' => [
                    'name' => 'Kartu Keluarga',
                    'uploaded' => !empty($registration->kartu_keluaga_path),
                    'icon' => 'fas fa-id-card',
                    'color' => 'blue'
                ],
                'ijazah' => [
                    'name' => 'Ijazah',
                    'uploaded' => !empty($registration->ijazah_path),
                    'icon' => 'fas fa-graduation-cap',
                    'color' => 'green'
                ],
                'akta_kelahiran' => [
                    'name' => 'Akta Kelahiran',
                    'uploaded' => !empty($registration->akta_kelahiran_path),
                    'icon' => 'fas fa-birthday-cake',
                    'color' => 'purple'
                ],
                'pas_foto' => [
                    'name' => 'Pas Foto',
                    'uploaded' => !empty($registration->pas_foto_path),
                    'icon' => 'fas fa-camera',
                    'color' => 'orange'
                ]
            ];
        @endphp

        @foreach($documents as $type => $doc)
        <div class="p-4 rounded-lg border-2 {{ $doc['uploaded'] ? 'border-green-500 bg-green-50' : 'border-gray-300' }} text-center transition duration-300 hover:shadow-md">
            <i class="{{ $doc['icon'] }} text-2xl {{ $doc['uploaded'] ? 'text-green-500' : 'text-gray-400' }} mb-2"></i>
            <div class="text-sm font-semibold {{ $doc['uploaded'] ? 'text-green-700' : 'text-gray-600' }}">
                {{ $doc['name'] }}
            </div>
            <div class="text-xs mt-1 {{ $doc['uploaded'] ? 'text-green-600' : 'text-gray-500' }}">
                {{ $doc['uploaded'] ? '✓ Terunggah' : 'Belum diunggah' }}
            </div>
        </div>
        @endforeach
    </div>

    <!-- Document Progress -->
    <div class="mt-4">
        <div class="flex justify-between text-sm text-gray-600 mb-1">
            <span>Progress Dokumen</span>
            <span>{{ round($documentProgress) }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-primary h-2 rounded-full transition-all duration-300"
                 style="width: {{ $documentProgress }}%"></div>
        </div>
    </div>
    @else
    <div class="text-center py-6">
        <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">Silakan isi biodata terlebih dahulu untuk mengunggah dokumen</p>
        <a href="{{ route('santri.biodata.index') }}" class="inline-block mt-3 bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
            Isi Biodata Sekarang
        </a>
    </div>
    @endif
</div>

<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex justify-between mb-4 w-full flex-col gap-2 mb-4">
        <h3 class="text-xl font-bold text-primary">Status Pendaftaran</h3>
        <!-- <div class="flex justify-center gap-2 mt-4">
            @if($registration)
                <div class="text-sm text-gray-600 w-full">
                    ID: <span class="font-mono font-bold">{{ $registration->id_pendaftaran }}</span>
                </div>
            @else
                <div class="text-sm text-gray-600">
                    ID: <span class="font-mono font-bold">Belum Mendaftar</span>
                </div>
            @endif
        </div> -->
    </div>

    <div class="flex items-center gap-4 w-full">
        @if($registration)
            @php
                $statusColors = [
                    'belum_mendaftar' => 'bg-gray-100 text-gray-800',
                    'telah_mengisi' => 'bg-blue-100 text-blue-800',
                    'telah_dilihat' => 'bg-yellow-100 text-yellow-800',
                    'menunggu_diverifikasi' => 'bg-orange-100 text-orange-800',
                    'ditolak' => 'bg-red-100 text-red-800',
                    'diterima' => 'bg-green-100 text-green-800',
                    'perlu_review' => 'bg-purple-100 text-purple-800'
                ];
                $statusIcons = [
                    'belum_mendaftar' => 'fa-clock',
                    'telah_mengisi' => 'fa-edit',
                    'telah_dilihat' => 'fa-eye',
                    'menunggu_diverifikasi' => 'fa-hourglass-half',
                    'ditolak' => 'fa-times-circle',
                    'diterima' => 'fa-check-circle',
                    'perlu_review' => 'fa-search-plus'
                ];
            @endphp
            <div class="w-full px-4 py-3 rounded-full text-base font-medium {{ $statusColors[$registration->status_pendaftaran] ?? 'bg-gray-100 text-gray-800' }} flex items-center">
                <i class="fas {{ $statusIcons[$registration->status_pendaftaran] ?? 'fa-question-circle' }} mr-3"></i>
                {{ $registration->status_label }}
            </div>
            
            <!-- @if($registration)
                <div class="text-sm text-gray-600">
                    ID: <span class="font-mono font-bold">{{ $registration->id_pendaftaran }}</span>
                </div> -->
            @endif
        @else
            <div class="px-4 py-3 rounded-full text-base font-medium bg-yellow-100 text-yellow-800 flex items-center">
                <i class="fas fa-clock mr-3"></i>Belum Mendaftar
            </div>
        @endif
        
                <!-- Action Button -->
                @if($registration)
                    @if($registration->status_pendaftaran == 'belum_mendaftar')
                    <!-- button untuk mulai pendaftaran -->
                    <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                        Mulai Pendaftaran
                    </a>
                    @elseif($registration->status_pendaftaran == 'telah_mengisi')

                    <!-- button untuk edit biodata -->
                    <a href="{{ route('santri.biodata.index') }}" class="w-full text-center border-2 border-primary text-primary py-2 rounded-full hover:bg-primary hover:text-white transition duration-300">
                        Edit Biodata
                    </a>

                    <!-- button untuk upload dokumen -->
                    <a href="{{ route('santri.documents.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                        Upload Dokumen
                    </a>
                    @elseif($registration->status_pendaftaran == 'menunggu_diverifikasi')
                    <!-- Button untuk perbaiki data -->
                    <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                        Edit Biodata
                    </a>
                    <!-- perbaiki dokumen -->
                    <a href="{{ route('santri.documents.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                        Edit Dokumen
                    </a>
                    <!-- button untuk bayar -->
                    <a href="{{ route('santri.payments.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                        Pembayaran
                    </a>
                    @endif
                @else
                    <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                        Mulai Pendaftaran
                    </a>
                @endif
            
    </div>
    <div class="flex justify-center gap-2 w-full mt-4">
        @if($registration)
            <div class="text-sm text-gray-600 w-full text-center">
                ID: <span class="font-mono font-bold">{{ $registration->id_pendaftaran }}</span>
            </div>
        @else
            <div class="text-sm text-gray-600">
                ID: <span class="font-mono font-bold">Belum Mendaftar</span>
            </div>
        @endif
    </div>

    @if($registration && $registration->status_pendaftaran == 'ditolak' && $registration->catatan_admin)
    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-3"></i>
            <div>
                <p class="font-medium text-red-800">Catatan dari Admin:</p>
                <p class="text-red-600 text-sm mt-1">{{ $registration->catatan_admin }}</p>
            </div>
        </div>
    </div>
    @endif
</div>

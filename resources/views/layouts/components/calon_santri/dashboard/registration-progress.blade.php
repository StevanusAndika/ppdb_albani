<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h3 class="text-xl font-bold text-primary mb-4">Progress Pendaftaran</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <!-- Step 1: Buat Akun -->
        <div class="p-2 border-2 border-green-500 bg-green-50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="step-number bg-green-500 text-white">1</div>
                <div>
                    <h4 class="font-semibold text-green-800">Buat Akun</h4>
                    <p class="text-sm text-green-600">Selesai</p>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="p-2 border-2 {{ $registration ? 'border-green-500 bg-green-50' : 'border-gray-300' }} rounded-xl">
            <div class="flex items-center gap-3">
                <div class="step-number {{ $registration ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">2</div>
                <div>
                    <h4 class="font-semibold {{ $registration ? 'text-green-800' : 'text-gray-600' }}">Isi Biodata</h4>
                    <p class="text-sm {{ $registration ? 'text-green-600' : 'text-gray-500' }}">
                        {{ $registration ? 'Selesai' : 'Belum' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="p-2 border-2 {{ $registration && $registration->hasAllDocuments() ? 'border-green-500 bg-green-50' : 'border-gray-300' }} rounded-xl">
            <div class="flex items-center gap-3">
                <div class="step-number {{ $registration && $registration->hasAllDocuments() ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">3</div>
                <div>
                    <h4 class="font-semibold {{ $registration && $registration->hasAllDocuments() ? 'text-green-800' : 'text-gray-600' }}">Upload Dokumen</h4>
                    <p class="text-sm {{ $registration && $registration->hasAllDocuments() ? 'text-green-600' : 'text-gray-500' }}">
                        @if($registration && $registration->hasAllDocuments())
                            Lengkap
                        @elseif($registration)
                            {{ round($documentProgress) }}%
                        @else
                            Belum
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="p-2 border-2 {{ $hasSuccessfulPayment ? 'border-green-500 bg-green-50' : 'border-gray-300' }} rounded-xl">
            <div class="flex items-center gap-3">
                <div class="step-number {{ $hasSuccessfulPayment ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">4</div>
                <div>
                    <h4 class="font-semibold {{ $hasSuccessfulPayment ? 'text-green-800' : 'text-gray-600' }}">Pembayaran</h4>
                    <p class="text-sm {{ $hasSuccessfulPayment ? 'text-green-600' : 'text-gray-500' }}">
                        @if($hasSuccessfulPayment)
                            Lunas
                        @elseif($registration && $registration->hasAllDocuments())
                            Siap Bayar
                        @else
                            Menunggu
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 5 -->
        <div class="p-2 border-2 {{ $registration && in_array($registration->status_pendaftaran, ['diterima', 'perlu_review']) ? 'border-green-500 bg-green-50' : 'border-gray-300' }} rounded-xl">
            <div class="flex items-center gap-3">
                <div class="step-number {{ $registration && in_array($registration->status_pendaftaran, ['diterima', 'perlu_review']) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">5</div>
                <div>
                    <h4 class="font-semibold {{ $registration && in_array($registration->status_pendaftaran, ['diterima', 'perlu_review']) ? 'text-green-800' : 'text-gray-600' }}">Wawancara</h4>
                    <p class="text-sm {{ $registration && in_array($registration->status_pendaftaran, ['diterima', 'perlu_review']) ? 'text-green-600' : 'text-gray-500' }}">
                        @if($registration && in_array($registration->status_pendaftaran, ['diterima', 'perlu_review']))
                            Selesai
                        @else
                            Menunggu
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    @if($registration)
        <div class="mt-6">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>Progress Keseluruhan</span>
                <span>{{ $totalProgress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-primary h-3 rounded-full transition-all duration-300"
                    style="width: {{ $totalProgress }}%"></div>
            </div>
        </div>
    @endif
</div>

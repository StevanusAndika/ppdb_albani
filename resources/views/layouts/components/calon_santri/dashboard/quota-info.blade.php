<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-primary">Informasi Kuota Kapasitas Pendaftaran</h3>
    </div>

    @if($quota)
        <!-- Progress Bar Kuota -->
        <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>Sisa Kuota kapasitas: {{ $quota->sisa }} dari {{ $quota->kuota }}</span>
                <span>Terisi: {{ number_format($quota->persentase_terpakai, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-300
                    {{ $quota->persentase_terpakai >= 80 ? 'bg-red-500' : ($quota->persentase_terpakai >= 60 ? 'bg-yellow-500' : 'bg-green-500') }}"
                    style="width: {{ min($quota->persentase_terpakai, 100) }}%">
                </div>
            </div>
        </div>

        <!-- Status Informasi -->
        <div class="p-4 rounded-lg {{ $quotaAvailable ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
            <div class="flex items-start">
                <i class="fas {{ $quotaAvailable ? 'fa-check-circle text-green-500' : 'fa-exclamation-triangle text-red-500' }} mt-1 mr-3 text-lg"></i>
                <div>
                    <p class="font-semibold {{ $quotaAvailable ? 'text-green-800' : 'text-red-800' }}">
                        {{ $quotaAvailable ? 'Kuota Masih Tersedia' : 'Kuota Sudah Penuh' }}
                    </p>
                    <p class="text-sm {{ $quotaAvailable ? 'text-green-600' : 'text-red-600' }} mt-1">
                        @if($quotaAvailable)
                            Saat ini masih tersedia {{ $quota->sisa }} kuota dari total {{ $quota->kuota }} kuota kapasitas pendaftaran.
                            @if($quota->sisa <= 5)
                                <strong class="block mt-1">Segera lakukan pembayaran sebelum kuota habis!</strong>
                            @endif
                        @else
                            Maaf, kuota pendaftaran untuk periode ini sudah penuh. Silakan menunggu periode pendaftaran berikutnya.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="mt-4 text-xs text-gray-500">
            <p><i class="fas fa-info-circle mr-1"></i>
                Tahun Akademik: <strong>{{ $quota->tahun_akademik }}</strong>
            </p>
            <p class="mt-1"><i class="fas fa-clock mr-1"></i>
                Terakhir diperbarui: {{ $quota->updated_at->translatedFormat('d F Y H:i') }}
            </p>
        </div>
    @else
        <div class="text-center py-6">
            <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Belum ada kuota pendaftaran yang tersedia</p>
            <p class="text-sm text-gray-400 mt-1">Silakan hubungi admin untuk informasi lebih lanjut</p>
        </div>
    @endif
</div>

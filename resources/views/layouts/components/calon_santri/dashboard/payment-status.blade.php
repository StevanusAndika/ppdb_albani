@if($registration && $registration->hasAllDocuments())
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-primary">Status Pembayaran</h3>
        <div class="flex gap-2">
            <a href="{{ route('santri.payments.index') }}" class="text-primary hover:text-secondary text-sm font-medium bg-gray-100 px-3 py-1 rounded-full">
                Riwayat Pembayaran
            </a>
            @if(!$hasSuccessfulPayment && $quotaAvailable)
            <a href="{{ route('santri.payments.create') }}" class="bg-primary text-white px-3 py-1 rounded-full hover:bg-secondary transition duration-300 text-sm">
                Bayar Sekarang
            </a>
            @endif
        </div>
    </div>

    @if($latestPayment)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-blue-800">Pembayaran Terakhir</h4>
                <p class="text-blue-600 text-sm">Kode: {{ $latestPayment->payment_code }}</p>
                <p class="text-blue-600 text-sm">Jumlah: {{ $latestPayment->formatted_amount }}</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $latestPayment->status_color }}">
                    {{ $latestPayment->status_label }}
                </span>
                <p class="text-blue-600 text-sm mt-1">{{ $latestPayment->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-6">
        <i class="fas fa-credit-card text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">Belum ada pembayaran</p>
        @if($quotaAvailable)
        <a href="{{ route('santri.payments.create') }}" class="inline-block mt-3 bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
           Bayar Sekarang
        </a>
        @else
        <p class="text-sm text-red-500 mt-2">Tidak dapat melakukan pembayaran karena kuota sudah penuh</p>
        @endif
    </div>
    @endif
</div>
@endif

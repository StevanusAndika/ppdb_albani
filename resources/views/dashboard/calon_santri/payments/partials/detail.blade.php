<div class="space-y-4">
    <!-- Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Kode Pembayaran</label>
            <p class="font-mono font-semibold text-gray-800">{{ $payment->payment_code }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Status</label>
            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $payment->status_color }}">
                {{ $payment->status_label }}
            </span>
        </div>
    </div>

    <!-- Amount & Method -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Jumlah</label>
            <p class="text-2xl font-bold text-primary">{{ $payment->formatted_amount }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Metode Pembayaran</label>
            <p class="font-semibold text-gray-800">
                @if($payment->payment_method === 'cash')
                <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Cash
                @else
                <i class="fas fa-credit-card text-blue-500 mr-2"></i>Online
                @endif
            </p>
        </div>
    </div>

    <!-- Dates -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Dibuat</label>
            <p class="text-gray-800">{{ $payment->created_at->translatedFormat('d F Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">
                @if($payment->isPaid())
                Dibayar
                @elseif($payment->expired_at)
                Kedaluarsa
                @else
                Estimasi Selesai
                @endif
            </label>
            <p class="text-gray-800">
                @if($payment->isPaid())
                {{ $payment->paid_at->translatedFormat('d F Y H:i') }}
                @elseif($payment->expired_at)
                {{ $payment->expired_at->translatedFormat('d F Y H:i') }}
                @else
                -
                @endif
            </p>
        </div>
    </div>

    <!-- Package Info -->
    <div class="border-t pt-4">
        <label class="block text-sm font-medium text-gray-500 mb-2">Paket Pendaftaran</label>
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="font-semibold text-gray-800">{{ $payment->registration->package->name }}</p>
            <p class="text-sm text-gray-600">{{ $payment->registration->package->description }}</p>
        </div>
    </div>

    <!-- Xendit Info (if online payment) -->
    @if($payment->payment_method === 'xendit' && $payment->xendit_response)
    <div class="border-t pt-4">
        <label class="block text-sm font-medium text-gray-500 mb-2">Informasi Xendit</label>
        <div class="bg-blue-50 p-3 rounded-lg">
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>
                    <span class="text-gray-600">Invoice ID:</span>
                    <p class="font-mono text-xs">{{ $payment->xendit_id }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Status Xendit:</span>
                    <p class="font-semibold">{{ $payment->xendit_response['status'] ?? '-' }}</p>
                </div>
                @if($payment->xendit_response['payment_method'] ?? false)
                <div class="col-span-2">
                    <span class="text-gray-600">Metode:</span>
                    <p>{{ $payment->xendit_response['payment_method'] }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="border-t pt-4 flex gap-3">
        @if($payment->payment_method === 'xendit' && $payment->isPending() && $payment->xendit_response)
        <a href="{{ $payment->xendit_response['invoice_url'] }}"
           target="_blank"
           class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300 text-center">
            <i class="fas fa-external-link-alt mr-2"></i>Lanjutkan Bayar
        </a>
        @endif

        @if($payment->isPaid())
        <a href="{{ route('santri.payments.download-invoice', $payment->payment_code) }}"
           target="_blank"
           class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition duration-300 text-center">
            <i class="fas fa-download mr-2"></i>Download Invoice
        </a>
        @endif
    </div>

    <!-- Admin Notes -->
    @if($payment->admin_notes)
    <div class="border-t pt-4">
        <label class="block text-sm font-medium text-gray-500 mb-2">Catatan Admin</label>
        <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
            <p class="text-sm text-yellow-800">{{ $payment->admin_notes }}</p>
        </div>
    </div>
    @endif
</div>

<div class="bg-white rounded-xl shadow-md p-6">
    <h4 class="font-semibold text-gray-700 mb-3">Ringkasan Progress</h4>
    <div class="space-y-3">
        @php
            $progressItems = [
                'Biodata' => [
                    'completed' => (bool)$registration,
                    'route' => route('santri.biodata.index'),
                    'color' => 'blue'
                ],
                'Dokumen' => [
                    'completed' => $registration && $registration->hasAllDocuments(),
                    'progress' => $documentProgress ?? 0,
                    'route' => route('santri.documents.index'),
                    'color' => 'green'
                ],
                'Pembayaran' => [
                    'completed' => $hasSuccessfulPayment ?? false,
                    'route' => route('santri.payments.index'),
                    'color' => 'purple'
                ],
                'QR Code' => [
                    'completed' => $registration && $barcodeUrl,
                    'route' => 'javascript:void(0)',
                    'onclick' => 'showBarcodeModal()',
                    'color' => 'indigo'
                ],
                'Pengaturan' => [
                    'completed' => true,
                    'route' => route('santri.settings.index'),
                    'color' => 'gray'
                ]
            ];
        @endphp

        @foreach($progressItems as $label => $item)
        <a href="{{ $item['route'] }}"
           @if(isset($item['onclick'])) onclick="{{ $item['onclick'] }}" @endif
           class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition duration-300">
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-{{ $item['color'] }}-500 mr-3"></div>
                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
            </div>
            <div class="flex items-center">
                @if(isset($item['progress']))
                    <span class="text-xs text-gray-500 mr-2">{{ $item['progress'] }}%</span>
                @endif
                @if($item['completed'])
                    <i class="fas fa-check-circle text-green-500 text-sm"></i>
                @else
                    <i class="fas fa-clock text-yellow-500 text-sm"></i>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
    <!-- Biodata Card -->
    <div class="bg-white rounded-xl shadow-md p-3 flex items-center">
        <div class="flex items-center space-x-4 w-full">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                <i class="fas fa-user-edit text-white text-xl"></i>
            </div>
            <div class="flex flex-col justify-center flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-600 break-words">Biodata</p>
                @if($registration)
                    <p class="text-md font-semibold text-gray-900 break-words truncate">Lengkap</p>
                @else
                    <p class="text-md font-semibold text-gray-900 break-words truncate">Belum</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Dokumen Card -->
    <div class="bg-white rounded-xl shadow-md p-3 flex items-center">
        <div class="flex items-center space-x-4 w-full">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                <i class="fas fa-file-alt text-white text-xl"></i>
            </div>
            <div class="flex flex-col justify-center flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-600 break-words">Dokumen</p>
                @if($registration && $registration->hasAllDocuments())
                    <p class="text-md font-semibold text-gray-900 break-words truncate">Lengkap</p>
                @elseif($registration)
                    <p class="text-md font-semibold text-gray-900 break-words truncate">
                        {{ round($documentProgress) }}%
                    </p>
                @else
                    <p class="text-md font-semibold text-gray-900 break-words truncate">Belum</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Pembayaran Card -->
    <div class="bg-white rounded-xl shadow-md p-3 flex items-center">
        <div class="flex items-center space-x-4 w-full">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                <i class="fas fa-credit-card text-white text-xl"></i>
            </div>
            <div class="flex flex-col justify-center flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-600 break-words">Pembayaran</p>
                @if($hasSuccessfulPayment)
                    <p class="text-md font-semibold text-gray-900 break-words truncate">Lunas</p>
                @elseif($registration && $registration->hasAllDocuments())
                    <p class="text-md font-semibold text-gray-900 break-words truncate">Siap Bayar</p>
                @else
                    <p class="text-md font-semibold text-gray-900 break-words truncate">Menunggu</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Kuota Card -->
    <div class="bg-white rounded-xl shadow-md p-3 flex items-center">
        <div class="flex items-center space-x-4 w-full">
            <div class="flex-shrink-0 {{ $quotaAvailable ? 'bg-green-500' : 'bg-red-500' }} rounded-md p-3">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div class="flex flex-col justify-center flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-600 break-words">Kuota Pendaftaran</p>
                @if($quota)
                    <p class="text-lg font-semibold break-words {{ $quotaAvailable ? 'text-green-700' : 'text-red-700' }}">
                        {{ $quota->sisa }} / {{ $quota->kuota }}
                    </p>
                    <p class="text-xs break-words {{ $quotaAvailable ? 'text-green-600' : 'text-red-600' }}">
                        {{ $quotaAvailable ? 'Tersedia' : 'Penuh' }}
                    </p>
                @else
                    <p class="text-lg font-semibold text-gray-700 break-words">-</p>
                    <p class="text-xs text-gray-500 break-words">Belum tersedia</p>
                @endif
            </div>
        </div>
    </div>
</div>

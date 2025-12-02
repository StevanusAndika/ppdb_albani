@if($registration && $barcodeUrl)
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-primary">QR Code Pendaftaran</h3>
        <div class="flex gap-2">
            <!-- Buttons can be added here -->
        </div>
    </div>

    <div class="flex flex-col md:flex-row items-center gap-6">
        <!-- QR Code Preview -->
        <div class="bg-white p-4 rounded-lg border-2 border-indigo-200 shadow-sm">
            <img src="{{ $barcodeUrl }}"
                 alt="QR Code Pendaftaran"
                 class="w-48 h-48 mx-auto qr-fade-in"
                 id="barcodePreview">
        </div>

        <!-- Barcode Information -->
        <div class="flex-1">
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Informasi QR Code</h4>
                    <p class="text-sm text-gray-600">
                        QR Code ini berisi informasi pendaftaran Anda dan dapat digunakan untuk verifikasi oleh admin.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">ID Pendaftaran</p>
                        <p class="font-mono font-bold text-primary">{{ $registration->id_pendaftaran }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="font-semibold text-green-600">Aktif</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button onclick="showBarcodeModal()" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition duration-300 text-sm flex items-center">
                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                    </button>
                    <a href="{{ $barcodeDownloadUrl }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 text-sm flex items-center">
                        <i class="fas fa-download mr-1"></i> Download
                    </a>
                    <a href="{{ $barcodeInfoUrl }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-sm flex items-center">
                        <i class="fas fa-external-link-alt mr-1"></i> Info Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

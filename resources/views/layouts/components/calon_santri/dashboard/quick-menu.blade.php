<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-xl font-bold text-primary mb-4">Menu Cepat</h3>

    <div class="grid grid-cols-2 gap-4">
        <!-- Biodata Card -->
        <a href="{{ route('santri.biodata.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-4 text-center hover:from-blue-600 hover:to-blue-700 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-user-edit text-2xl mb-2"></i>
                <span class="font-semibold text-sm">Biodata</span>
            </div>
        </a>

        <!-- Dokumen Card -->
        <a href="{{ route('santri.documents.index') }}" class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-lg p-4 text-center hover:from-emerald-600 hover:to-emerald-700 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-file-upload text-2xl mb-2"></i>
                <span class="font-semibold text-sm">Dokumen</span>
            </div>
        </a>

        <!-- Pembayaran Card -->
        <a href="{{ route('santri.payments.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg p-4 text-center hover:from-purple-600 hover:to-purple-700 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-credit-card text-2xl mb-2"></i>
                <span class="font-semibold text-sm">Pembayaran</span>
            </div>
        </a>

        <!-- Barcode Card -->
        @if($registration && $barcodeUrl)
        <a href="javascript:void(0)" onclick="showBarcodeModal()" class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg p-4 text-center hover:from-indigo-600 hover:to-indigo-700 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-qrcode text-2xl mb-2"></i>
                <span class="font-semibold text-sm">QR Code</span>
            </div>
        </a>
        @else
        <div class="bg-gradient-to-br from-gray-300 to-gray-400 text-white rounded-lg p-4 text-center opacity-50">
            <div class="flex flex-col items-center">
                <i class="fas fa-qrcode text-2xl mb-2"></i>
                <span class="font-semibold text-sm">QR Code</span>
            </div>
        </div>
        @endif

        <!-- Settings Card -->
        <a href="{{ route('santri.settings.index') }}" class="bg-gradient-to-br from-gray-600 to-gray-700 text-white rounded-lg p-4 text-center hover:from-gray-700 hover:to-gray-800 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-cog text-2xl mb-2"></i>
                <span class="font-semibold text-sm">Pengaturan</span>
            </div>
        </a>

        <!-- FAQ Card -->
        <a href="{{ route('santri.faq.index') }}" class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-lg p-4 text-center hover:from-amber-600 hover:to-amber-700 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-question-circle text-2xl mb-2"></i>
                <span class="font-semibold text-sm">FAQ</span>
            </div>
        </a>

        <!-- Kegiatan Card -->
        <a href="{{ route('santri.kegiatan.index') }}" class="bg-gradient-to-br from-pink-500 to-pink-600 text-white rounded-lg p-4 text-center hover:from-pink-600 hover:to-pink-700 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-calendar-alt text-2xl mb-2"></i>
                <span class="font-semibold text-sm">Kegiatan</span>
            </div>
        </a>

        <!-- Support PPDB Putra -->
        <a href="https://api.whatsapp.com/send?phone=6289510279293&text=Halo%20Admin%20Pondok%20Pesantren%20Al%20Quran%20Bani%20Syahid%2C%20saya%20memiliki%20kendala%20atau%20ingin%20konsultasi%20seputar%20Pondok%20Pesantren%20Al%20Quran%20Bani%20Syahid"
        class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-lg p-4 text-center hover:from-blue-700 hover:to-blue-800 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-headset text-2xl mb-2"></i>
                <span class="font-semibold text-sm">Support PPDB Putra</span>
            </div>
        </a>

        <!-- Support PPDB Putri -->
        <a href="https://api.whatsapp.com/send?phone=6282183953533&text=Halo%20Admin%20Pondok%20Pesantren%20Al%20Quran%20Bani%20Syahid%2C%20saya%20memiliki%20kendala%20atau%20ingin%20konsultasi%20seputar%20Pondok%20Pesantren%20Al%20Quran%20Bani%20Syahid"
        class="bg-gradient-to-br from-pink-600 to-pink-700 text-white rounded-lg p-4 text-center hover:from-pink-700 hover:to-pink-800 transition duration-300 transform hover:scale-105">
            <div class="flex flex-col items-center">
                <i class="fas fa-headset text-2xl mb-2"></i>
                <span class="font-semibold text-sm">Support PPDB Putri</span>
            </div>
        </a>
    </div>
</div>

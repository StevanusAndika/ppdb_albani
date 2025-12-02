<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-xl font-bold text-primary mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('santri.biodata.index') }}" class="p-4 border-2 border-primary rounded-lg text-center hover:bg-primary hover:text-white transition duration-300">
            <i class="fas fa-edit text-2xl mb-2"></i>
            <p class="font-semibold">Edit Biodata</p>
            <p class="text-sm opacity-75">Perbarui data pribadi</p>
        </a>
        <a href="{{ route('santri.documents.index') }}" class="p-4 border-2 border-secondary rounded-lg text-center hover:bg-secondary hover:text-white transition duration-300">
            <i class="fas fa-upload text-2xl mb-2"></i>
            <p class="font-semibold">Upload Dokumen</p>
            <p class="text-sm opacity-75">Kelola berkas persyaratan</p>
        </a>
        <a href="{{ route('santri.payments.index') }}" class="p-4 border-2 border-green-500 rounded-lg text-center hover:bg-green-500 hover:text-white transition duration-300">
            <i class="fas fa-receipt text-2xl mb-2"></i>
            <p class="font-semibold">Riwayat Bayar</p>
            <p class="text-sm opacity-75">Lihat status pembayaran</p>
        </a>
        @if($registration && $barcodeUrl)
        <a href="javascript:void(0)" onclick="showBarcodeModal()" class="p-4 border-2 border-indigo-500 rounded-lg text-center hover:bg-indigo-500 hover:text-white transition duration-300">
            <i class="fas fa-qrcode text-2xl mb-2"></i>
            <p class="font-semibold">QR Code</p>
            <p class="text-sm opacity-75">Lihat barcode pendaftaran</p>
        </a>
        @else
        <div class="p-4 border-2 border-gray-300 rounded-lg text-center text-gray-400">
            <i class="fas fa-qrcode text-2xl mb-2"></i>
            <p class="font-semibold">QR Code</p>
            <p class="text-sm opacity-75">Tersedia setelah daftar</p>
        </div>
        @endif
    </div>
</div>

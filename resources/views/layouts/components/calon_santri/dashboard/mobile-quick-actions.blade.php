<div class="bg-white rounded-xl shadow-md p-4">
    <h3 class="text-lg font-bold text-primary mb-3">Aksi Cepat</h3>
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('santri.biodata.index') }}" class="p-4 border-2 border-primary rounded-lg text-center hover:bg-primary hover:text-white active:bg-primary active:text-white transition duration-200 touch-manipulation">
            <i class="fas fa-edit text-xl mb-2 block"></i>
            <p class="font-semibold text-sm">Edit Biodata</p>
            <p class="text-xs opacity-75 mt-1">Perbarui data pribadi</p>
        </a>
        <a href="{{ route('santri.documents.index') }}" class="p-4 border-2 border-secondary rounded-lg text-center hover:bg-secondary hover:text-white active:bg-secondary active:text-white transition duration-200 touch-manipulation">
            <i class="fas fa-upload text-xl mb-2 block"></i>
            <p class="font-semibold text-sm">Upload Dokumen</p>
            <p class="text-xs opacity-75 mt-1">Kelola berkas persyaratan</p>
        </a>
        <a href="{{ route('santri.payments.index') }}" class="p-4 border-2 border-green-500 rounded-lg text-center hover:bg-green-500 hover:text-white active:bg-green-500 active:text-white transition duration-200 touch-manipulation">
            <i class="fas fa-receipt text-xl mb-2 block"></i>
            <p class="font-semibold text-sm">Riwayat Bayar</p>
            <p class="text-xs opacity-75 mt-1">Lihat status pembayaran</p>
        </a>
        @if($registration && $barcodeUrl)
        <a href="javascript:void(0)" onclick="showBarcodeModal()" class="p-4 border-2 border-indigo-500 rounded-lg text-center hover:bg-indigo-500 hover:text-white active:bg-indigo-500 active:text-white transition duration-200 touch-manipulation">
            <i class="fas fa-qrcode text-xl mb-2 block"></i>
            <p class="font-semibold text-sm">QR Code</p>
            <p class="text-xs opacity-75 mt-1">Lihat barcode pendaftaran</p>
        </a>
        @else
        <div class="p-4 border-2 border-gray-300 rounded-lg text-center text-gray-400">
            <i class="fas fa-qrcode text-xl mb-2 block"></i>
            <p class="font-semibold text-sm">QR Code</p>
            <p class="text-xs opacity-75 mt-1">Tersedia setelah daftar</p>
        </div>
        @endif
    </div>
</div>

<style>
    .touch-manipulation {
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
    }
</style>

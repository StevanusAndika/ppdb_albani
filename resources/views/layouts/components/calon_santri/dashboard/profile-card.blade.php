<div id="profile" class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center gap-4">
        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center">
            <i class="fas fa-user text-2xl text-primary"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-primary">{{ Auth::user()->name }}</h3>
            <p class="text-secondary text-sm">{{ Auth::user()->email }}</p>
        </div>
    </div>

    <div class="mt-6 space-y-2 text-sm text-secondary">
        <div class="flex justify-between"><span>Telepon</span><span class="font-medium">{{ Auth::user()->phone_number ?? '-' }}</span></div>
        <div class="flex justify-between"><span>Role</span><span class="font-medium text-green-600">{{ Auth::user()->role }}</span></div>
        <div class="flex justify-between">
            <span>Tanggal Daftar</span>
            <span class="font-medium">{{ Auth::user()->created_at->translatedFormat('d F Y') }}</span>
        </div>
        @if($registration)
        <div class="flex justify-between">
            <span>Paket Dipilih</span>
            <span class="font-medium text-primary">{{ $registration->package->name ?? '-' }}</span>
        </div>
        @endif
    </div>

    <div class="mt-6 flex gap-3">
        <!-- Action Button -->
        @if($registration)
            @if($registration->status_pendaftaran == 'belum_mendaftar')
            <!-- button untuk mulai pendaftaran -->
            <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                Mulai Pendaftaran
            </a>
            @elseif($registration->status_pendaftaran == 'telah_mengisi')

            <!-- button untuk edit biodata -->
            <a href="{{ route('santri.biodata.index') }}" class="w-full text-center border-2 border-primary text-primary py-2 rounded-full hover:bg-primary hover:text-white transition duration-300">
                Edit Biodata
            </a>

            <!-- button untuk upload dokumen -->
            <a href="{{ route('santri.documents.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                Upload Dokumen
            </a>
            @elseif($registration->status_pendaftaran == 'menunggu_diverifikasi')
            <!-- Button untuk perbaiki data -->
            <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                Edit Biodata
            </a>
            <!-- perbaiki dokumen -->
            <a href="{{ route('santri.documents.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                Edit Dokumen
            </a>
            <!-- button untuk bayar -->
            <a href="{{ route('santri.payments.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                Pembayaran
            </a>
            @endif
        @else
            <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                Mulai Pendaftaran
            </a>
        @endif
    </div>

    <!-- Settings Button -->
    <div class="mt-4 pt-4 border-t border-gray-200">
        <a href="{{ route('santri.settings.index') }}" class="w-full bg-gray-600 text-white py-2 rounded-full hover:bg-gray-700 transition duration-300 flex items-center justify-center">
            <i class="fas fa-cog mr-2"></i> Pengaturan Akun
        </a>
        <p class="text-xs text-gray-500 text-center mt-2">Kelola profil, email, dan koneksi Google</p>
    </div>

    <!-- Download All Documents Button -->
    @if($registration && $registration->hasAllDocuments())
    <div class="mt-4 pt-4 border-t border-gray-200">
        <button onclick="downloadAllDocuments()" class="w-full bg-purple-600 text-white py-2 rounded-full hover:bg-purple-700 transition duration-300 flex items-center justify-center">
            <i class="fas fa-file-archive mr-2"></i> Download Semua Dokumen
        </button>
    </div>
    @endif
</div>

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
        @if($registration)
            <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                Edit Biodata
            </a>
        @else
            <a href="{{ route('santri.biodata.index') }}" class="w-full text-center bg-primary text-white py-2 rounded-full hover:bg-secondary transition duration-300">
                Isi Biodata
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

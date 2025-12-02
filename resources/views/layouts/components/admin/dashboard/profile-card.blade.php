<div id="profile" class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center gap-4">
        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center">
            <i class="fas fa-user-shield text-2xl text-primary"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-primary">{{ Auth::user()->name }}</h3>
            <p class="text-secondary text-sm">{{ Auth::user()->email }}</p>
        </div>
    </div>

    <div class="mt-6 space-y-2 text-sm text-secondary">
        <div class="flex justify-between"><span>Telepon</span><span class="font-medium">{{ Auth::user()->phone_number ?? '-' }}</span></div>
        <div class="flex justify-between"><span>Role</span><span class="font-medium text-blue-600">{{ Auth::user()->role}}</span></div>
        <div class="flex justify-between"><span>Tanggal Bergabung</span><span class="font-medium">{{ Auth::user()->created_at->translatedFormat('d F Y') }}</span></div>
    </div>

    <div class="mt-6 flex gap-3">
         <a href="{{ route('admin.settings.index') }}?tab=profile" class="w-full text-center bg-primary text-white py-2 rounded-full transition duration-300 hover:bg-secondary">Edit Profil</a>
    </div>
</div>

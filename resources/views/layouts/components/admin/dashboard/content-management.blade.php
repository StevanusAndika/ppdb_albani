<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Website Manajemen</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.landing.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-edit text-2xl mb-2"></i>
            <p>Kelola Konten</p>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="bg-teal-500 hover:bg-teal-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-cogs text-2xl mb-2"></i>
            <p>Pengaturan</p>
        </a>
        <a href="{{ route('admin.billing.packages.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-box text-2xl mb-2"></i>
            <p>Kelola Paket</p>
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.manage-users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-user-cog text-2xl mb-2"></i>
            <p>Kelola User</p>
        </a>
        <a href="{{ route('admin.registrations.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-clipboard-list text-2xl mb-2"></i>
            <p>Kelola Pendaftaran</p>
        </a>
        <a href="{{ route('admin.transactions.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-credit-card text-2xl mb-2"></i>
            <p>Kelola Transaksi</p>
        </a>
        <!-- TAMBAHKAN ANNOUNCEMENT -->
        <a href="{{ route('admin.announcements.index') }}" class="bg-teal-500 hover:bg-teal-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-bullhorn text-2xl mb-2"></i>
            <p>Pengumuman</p>
        </a>
    </div>
</div>

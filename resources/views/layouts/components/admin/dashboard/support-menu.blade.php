<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Support Menu</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.seleksi-announcements.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-bell text-2xl mb-2"></i>
            <p>Kirim Notif Tes Tertulis</p>
        </a>
        <a href="{{ route('admin.qrcode-scanner.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-qrcode text-2xl mb-2"></i>
            <p>Scan QR Calon Santri</p>
        </a>
        <a href="{{ route('admin.quota.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg transition duration-200 text-center">
            <i class="fas fa-chart-pie text-2xl mb-2"></i>
            <p>Kelola Kuota Pendaftaran</p>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pendaftaran</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_registrations'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                <i class="fas fa-user-check text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Diterima</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_registrations'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                <i class="fas fa-clock text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_registrations'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                <i class="fas fa-ban text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Ditolak</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected_registrations'] }}</p>
            </div>
        </div>
    </div>
</div>

@if(isset($stats['eligible_for_announcement']) && isset($stats['sent_announcements']))
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-teal-500 rounded-md p-3">
                <i class="fas fa-bullhorn text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Siap Diumumkan</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['eligible_for_announcement'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                <i class="fas fa-paper-plane text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pengumuman Terkirim</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['sent_announcements'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-gray-500 rounded-md p-3">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Sisa Kuota</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['sisa_kuota'] }}/{{ $stats['kuota_total'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<div class="bg-white rounded-xl shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
    <p class="text-gray-600">Anda login sebagai <span class="font-semibold text-blue-600">{{ Auth::user()->role }}</span></p>
    <p class="text-gray-600 mt-2">Total <span class="font-semibold">{{ $stats['total_registrations'] }}</span> pendaftaran telah masuk ke sistem.</p>
    @if(isset($stats['eligible_for_announcement']))
    <p class="text-gray-600"><span class="font-semibold">{{ $stats['eligible_for_announcement'] }}</span> calon santri siap menerima pengumuman kelulusan.</p>
    @endif
</div>

<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Pendaftaran Terbaru</h3>
        <a href="{{ route('admin.registrations.index') }}" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua</a>
    </div>

    @if($recentRegistrations->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3">ID Pendaftaran</th>
                    <th class="px-4 py-3">Nama Santri</th>
                    <th class="px-4 py-3">Paket</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentRegistrations as $registration)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $registration->id_pendaftaran }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $registration->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500">{{ $registration->user->email }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                            {{ $registration->package->name }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'belum_mendaftar' => 'bg-gray-100 text-gray-800',
                                'telah_mengisi' => 'bg-blue-100 text-blue-800',
                                'telah_dilihat' => 'bg-yellow-100 text-yellow-800',
                                'menunggu_diverifikasi' => 'bg-orange-100 text-orange-800',
                                'ditolak' => 'bg-red-100 text-red-800',
                                'diterima' => 'bg-green-100 text-green-800',
                                'perlu_review' => 'bg-purple-100 text-purple-800',
                            ];
                        @endphp
                        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $statusColors[$registration->status_pendaftaran] }}">
                            {{ $registration->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        {{ $registration->created_at->translatedFormat('d M Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">Belum ada pendaftaran</p>
    </div>
    @endif
</div>

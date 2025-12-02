@if(isset($recentAnnouncements) && $recentAnnouncements->count() > 0)
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Pengumuman Terbaru</h3>
        <a href="{{ route('admin.announcements.index') }}" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua</a>
    </div>

    <div class="space-y-4">
        @foreach($recentAnnouncements as $announcement)
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800">{{ $announcement->registration->nama_lengkap }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->message, 100) }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-xs text-gray-500">
                            {{ $announcement->created_at->translatedFormat('d M Y H:i') }}
                        </span>
                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                            Terkirim
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

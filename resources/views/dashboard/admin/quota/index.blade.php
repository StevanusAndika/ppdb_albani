@extends('layouts.app')

@section('title', 'Kelola Kuota Pendaftaran - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')


    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Kelola Kuota Pendaftaran</h1>
        <p class="text-secondary">Pengaturan kuota penerimaan santri baru</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto py-6 px-4">
        <!-- Add Quota Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Tambah Kuota Baru</h2>
            <form action="{{ route('admin.quota.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik"
                               placeholder="2024-2025"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required pattern="\d{4}-\d{4}">
                        <p class="text-xs text-gray-500 mt-1">Format: 2024-2025</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kuota</label>
                        <input type="number" name="kuota" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-primary hover:bg-secondary text-white py-2 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Tambah Kuota
                        </button>
                    </div>
                </div>
                @error('tahun_akademik')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </form>
        </div>

        <!-- Quota List -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Kuota</h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($quotas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">Tahun Akademik</th>
                                <th class="px-4 py-3">Kuota</th>
                                <th class="px-4 py-3">Terpakai</th>
                                <th class="px-4 py-3">Sisa</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotas as $quota)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $quota->tahun_akademik }}
                                        @if($quota->is_active)
                                            <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ number_format($quota->kuota) }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <span class="mr-2">{{ number_format($quota->terpakai) }}</span>
                                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full"
                                                     style="width: {{ $quota->persentase_terpakai }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-semibold {{ $quota->sisa == 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($quota->sisa) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($quota->is_active)
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Aktif</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            @if(!$quota->is_active)
                                                <form action="{{ route('admin.quota.activate', $quota) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs transition duration-300">
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            @endif

                                            <button onclick="editQuota({{ $quota }})"
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs transition duration-300">
                                                Edit
                                            </button>

                                            @if($quota->terpakai > 0)
                                                <form action="{{ route('admin.quota.reset', $quota) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-lg text-xs transition duration-300"
                                                            onclick="return confirm('Reset kuota terpakai ke 0?')">
                                                        Reset
                                                    </button>
                                                </form>
                                            @endif

                                            @if($quota->terpakai == 0)
                                                <form action="{{ route('admin.quota.destroy', $quota) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs transition duration-300"
                                                            onclick="return confirm('Hapus kuota {{ $quota->tahun_akademik }}?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada data kuota</p>
                </div>
            @endif
        </div>


    </main>
    @include('layouts.components.admin.footer')

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Kuota</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik</label>
                        <input type="text" id="edit_tahun_akademik" name="tahun_akademik"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required pattern="\d{4}-\d{4}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kuota</label>
                        <input type="number" id="edit_kuota" name="kuota" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition duration-300">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>


<script>
    function editQuota(quota) {
        document.getElementById('edit_tahun_akademik').value = quota.tahun_akademik;
        document.getElementById('edit_kuota').value = quota.kuota;
        document.getElementById('editForm').action = `/admin/quota/${quota.id}`;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    // Close modal when clicking outside
    document.getElementById('editModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });
</script>
@endsection

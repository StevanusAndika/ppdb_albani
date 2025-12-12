@extends('layouts.app')

@section('title', 'Kelola Program Unggulan - Admin')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    @include('layouts.components.admin.navbar')

    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Program Unggulan</h1>
        <p class="text-secondary">Kelola program unggulan, diskon, dan dokumen tambahan</p>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        {{-- Alert Messages --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header dengan tombol tambah --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Program Unggulan</h2>
                <p class="text-gray-600 text-sm mt-1">Total: {{ $programs->total() }} program</p>
            </div>
            <a href="{{ route('admin.program-unggulan.create') }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                <i class="fas fa-plus mr-2"></i>Tambah Program
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($programs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Program</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Diskon (%)</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Verifikasi</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Dokumen Tambahan</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programs as $index => $program)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $programs->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                        {{ $program->nama_program }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                            {{ $program->potongan }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($program->perlu_verifikasi === 'yes')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                                                <i class="fas fa-check-circle mr-1"></i>Diperlukan
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">
                                                <i class="fas fa-times-circle mr-1"></i>Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if ($program->dokumen_tambahan && count($program->dokumen_tambahan) > 0)
                                            <button class="text-primary hover:underline" 
                                                    onclick="showDocuments('{{ json_encode($program->dokumen_tambahan) }}')">
                                                <i class="fas fa-file-alt mr-1"></i>{{ count($program->dokumen_tambahan) }} dokumen
                                            </button>
                                        @else
                                            <span class="text-gray-500">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        <a href="{{ route('admin.program-unggulan.edit', $program) }}"
                                           class="text-blue-600 hover:text-blue-800 mr-3 inline-block">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.program-unggulan.destroy', $program) }}"
                                              class="inline-block"
                                              onsubmit="return confirm('Yakin ingin menghapus program ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $programs->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada program unggulan</p>
                    <p class="text-gray-400 text-sm mt-2">Klik tombol "Tambah Program" untuk membuat program baru</p>
                </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    @include('layouts.components.footer')
</div>

{{-- Modal Lihat Dokumen --}}
<div id="documentsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Dokumen Tambahan</h3>
            <div id="documentsList" class="space-y-2">
                <!-- Documents will be inserted here -->
            </div>
            <div class="mt-6">
                <button onclick="closeDocumentsModal()" 
                        class="w-full px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDocuments(docs) {
    const documents = JSON.parse(docs);
    const documentsList = document.getElementById('documentsList');
    documentsList.innerHTML = '';
    
    documents.forEach(doc => {
        const li = document.createElement('li');
        li.className = 'px-3 py-2 bg-gray-100 rounded text-sm text-gray-700';
        li.textContent = '• ' + doc;
        documentsList.appendChild(li);
    });
    
    document.getElementById('documentsModal').classList.remove('hidden');
}

function closeDocumentsModal() {
    document.getElementById('documentsModal').classList.add('hidden');
}
</script>
@endsection

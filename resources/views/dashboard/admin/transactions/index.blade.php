@extends('layouts.app')

@section('title', 'Manajemen Transaksi - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
     @include('layouts.components.admin.navbar')
    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Manajemen Transaksi</h1>
        <p class="text-secondary">Kelola semua transaksi pembayaran santri</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $payments->total() }}</div>
                <div class="text-sm text-gray-600">Total Transaksi</div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600">
                    {{ $payments->whereIn('status', ['success', 'lunas'])->count() }}
                </div>
                <div class="text-sm text-gray-600">Berhasil</div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-yellow-600">
                    {{ $payments->whereIn('status', ['pending', 'waiting_payment'])->count() }}
                </div>
                <div class="text-sm text-gray-600">Menunggu</div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-red-600">
                    {{ $payments->whereIn('status', ['failed', 'expired'])->count() }}
                </div>
                <div class="text-sm text-gray-600">Gagal</div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form action="{{ route('admin.transactions.search') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text"
                           name="search"
                           placeholder="Cari berdasarkan kode pembayaran, nama santri, atau ID pendaftaran..."
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if(request('search'))
                <a href="{{ route('admin.transactions.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Transaksi</h2>

            @if($payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Kode</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Santri</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Paket</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Metode</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-4">
                                <div class="font-mono font-bold text-gray-900">{{ $payment->payment_code }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->registration->id_pendaftaran }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900">{{ $payment->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-700">{{ $payment->registration->package->name }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-semibold text-primary">{{ $payment->formatted_amount }}</div>
                            </td>
                            <td class="py-4 px-4">
                                @if($payment->payment_method === 'cash')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-money-bill-wave mr-1"></i> Cash
                                </span>
                                @else
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-credit-card mr-1"></i> Online
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $payment->status_color }}">
                                    {{ $payment->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-500">
                                {{ $payment->created_at->translatedFormat('d F Y H:i') }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.transactions.show', $payment) }}"
                                       class="text-blue-600 hover:text-blue-900 transition duration-200 p-2 rounded-full hover:bg-blue-50"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($payment->payment_method === 'cash' && $payment->isPending())
                                    <button onclick="showUpdateForm('{{ $payment->id }}', '{{ $payment->payment_code }}')"
                                            class="text-green-600 hover:text-green-900 transition duration-200 p-2 rounded-full hover:bg-green-50"
                                            title="Update Status">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Transaksi</h3>
                <p class="text-gray-500">Tidak ada data transaksi yang ditemukan</p>
            </div>
            @endif
        </div>
    </main>
      @include('layouts.components.admin.footer')
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Update Status Pembayaran</h3>
        <form id="updateStatusForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="lunas">Lunas</option>
                    <option value="failed">Gagal</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                <textarea name="admin_notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Opsional: Tambahkan catatan untuk santri..."></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="hideUpdateForm()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-300">
                    Batal
                </button>
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showUpdateForm(paymentId, paymentCode) {
    const form = document.getElementById('updateStatusForm');
    form.action = `/admin/transactions/${paymentId}/status`;

    const modal = document.getElementById('updateStatusModal');
    modal.classList.remove('hidden');
}

function hideUpdateForm() {
    const modal = document.getElementById('updateStatusModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('updateStatusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideUpdateForm();
    }
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Detail Transaksi - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="{{ route('admin.transactions.index') }}" class="text-primary hover:text-secondary font-medium">Transaksi</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Detail Transaksi</h1>
                    <p class="text-secondary">Kode: {{ $payment->payment_code }}</p>
                </div>
                <a href="{{ route('admin.transactions.index') }}"
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Transaction Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Transaksi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kode Pembayaran</label>
                            <p class="font-mono font-bold text-lg text-primary">{{ $payment->payment_code }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $payment->status_color }}">
                                {{ $payment->status_label }}
                            </span>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Metode Pembayaran</label>
                            <p class="font-semibold text-gray-800">
                                @if($payment->payment_method === 'cash')
                                <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Cash
                                @else
                                <i class="fas fa-credit-card text-blue-500 mr-2"></i>Online
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Jumlah</label>
                            <p class="text-2xl font-bold text-primary">{{ $payment->formatted_amount }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Dibuat</label>
                            <p class="text-gray-800">{{ $payment->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Bayar</label>
                            <p class="text-gray-800">
                                {{ $payment->paid_at ? $payment->paid_at->translatedFormat('d F Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Santri Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Santri</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama Santri</label>
                            <p class="font-semibold text-gray-800">{{ $payment->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-800">{{ $payment->user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Telepon</label>
                            <p class="text-gray-800">{{ $payment->user->phone_number ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">ID Pendaftaran</label>
                            <p class="font-mono text-gray-800">{{ $payment->registration->id_pendaftaran }}</p>
                        </div>
                    </div>
                </div>

                <!-- Package Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Paket</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama Paket</label>
                            <p class="font-semibold text-gray-800">{{ $payment->registration->package->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tipe Paket</label>
                            <p class="text-gray-800">{{ $payment->registration->package->type_label }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                            <p class="text-gray-800">{{ $payment->registration->package->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Xendit Info (if online payment) -->
                @if($payment->payment_method === 'xendit' && $payment->xendit_response)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Xendit</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Xendit ID</label>
                            <p class="font-mono text-sm text-gray-800">{{ $payment->xendit_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">External ID</label>
                            <p class="font-mono text-sm text-gray-800">{{ $payment->xendit_external_id }}</p>
                        </div>
                        @if($payment->xendit_response['invoice_url'] ?? false)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Invoice URL</label>
                            <a href="{{ $payment->xendit_response['invoice_url'] }}"
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 break-all">
                                {{ $payment->xendit_response['invoice_url'] }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi</h2>

                    @if($payment->payment_method === 'cash' && $payment->isPending())
                    <form action="{{ route('admin.transactions.update-status', $payment) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                            <select name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Status</option>
                                <option value="lunas">Lunas</option>
                                <option value="failed">Gagal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="admin_notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Opsional..."></textarea>
                        </div>
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 rounded-lg hover:bg-secondary transition duration-300 font-semibold">
                            Update Status
                        </button>
                    </form>
                    @else
                    <p class="text-gray-500 text-sm">Tidak ada aksi yang tersedia untuk transaksi ini.</p>
                    @endif
                </div>

                <!-- Admin Notes -->
                @if($payment->admin_notes)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Catatan Admin</h2>
                    <p class="text-gray-700">{{ $payment->admin_notes }}</p>
                </div>
                @endif

                <!-- Status History -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Status</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Dibuat</span>
                            <span class="text-xs text-gray-500">{{ $payment->created_at->format('d/m H:i') }}</span>
                        </div>
                        @if($payment->paid_at)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Dibayar</span>
                            <span class="text-xs text-gray-500">{{ $payment->paid_at->format('d/m H:i') }}</span>
                        </div>
                        @endif
                        @if($payment->updated_at->gt($payment->created_at))
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Diupdate</span>
                            <span class="text-xs text-gray-500">{{ $payment->updated_at->format('d/m H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

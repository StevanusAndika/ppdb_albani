@extends('layouts.app')

@section('title', 'Pembayaran - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('santri.dashboard') }}" class="text-primary hover:text-secondary font-medium">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Pembayaran Pendaftaran</h1>
            <p class="text-gray-600 mb-6">Pilih metode pembayaran untuk menyelesaikan pendaftaran</p>

            @if($registration)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                    <div>
                        <h3 class="font-semibold text-blue-800">Detail Pendaftaran</h3>
                        <p class="text-blue-600 text-sm">ID: {{ $registration->id_pendaftaran }} | Paket: {{ $registration->package->name }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cash Payment -->
                <div class="border-2 border-gray-300 rounded-xl p-6 hover:border-primary transition duration-300">
                    <div class="text-center">
                        <i class="fas fa-money-bill-wave text-4xl text-green-500 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pembayaran Cash</h3>
                        <p class="text-gray-600 mb-4">Bayar langsung di pesantren</p>
                        <div class="text-2xl font-bold text-primary mb-4">{{ $registration->formatted_total_biaya }}</div>

                        <form action="{{ route('santri.payments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="cash">
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition duration-300">
                                Pilih Cash
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Xendit Payment -->
                <div class="border-2 border-gray-300 rounded-xl p-6 hover:border-primary transition duration-300">
                    <div class="text-center">
                        <i class="fas fa-credit-card text-4xl text-blue-500 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pembayaran Online</h3>
                        <p class="text-gray-600 mb-4">Transfer bank, e-wallet, dll</p>
                        <div class="text-2xl font-bold text-primary mb-4">{{ $registration->formatted_total_biaya }}</div>

                        <form action="{{ route('santri.payments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="xendit">
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition duration-300">
                                Bayar Online
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800">Informasi Penting</h4>
                        <ul class="text-yellow-700 text-sm mt-2 space-y-1">
                            <li>• Pastikan data pendaftaran sudah benar sebelum melakukan pembayaran</li>
                            <li>• Pembayaran cash akan diverifikasi oleh admin dalam 1x24 jam</li>
                            <li>• Pembayaran online akan mendapatkan invoice via WhatsApp</li>
                            <li>• Batas waktu pembayaran online adalah 24 jam</li>
                        </ul>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Data Pendaftaran Tidak Ditemukan</h3>
                <p class="text-gray-600 mb-4">Silakan lengkapi pendaftaran terlebih dahulu</p>
                <a href="{{ route('santri.biodata.index') }}" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                    Lengkapi Pendaftaran
                </a>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection

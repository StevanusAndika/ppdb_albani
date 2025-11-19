@extends('layouts.app')

@section('title', 'Pembayaran Gagal - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
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

    <main class="max-w-2xl mx-auto py-12 px-4 text-center">
        <div class="bg-white rounded-xl shadow-md p-8">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-times text-3xl text-red-500"></i>
            </div>

            <h1 class="text-3xl font-bold text-red-600 mb-4">Pembayaran Gagal</h1>
            <p class="text-gray-600 mb-6 text-lg">
                Maaf, pembayaran Anda gagal atau dibatalkan. Silakan coba kembali.
            </p>

            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-semibold text-red-800 mb-3">Yang dapat Anda lakukan:</h3>
                <ul class="text-red-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-sync-alt text-red-500 mt-1 mr-3"></i>
                        <span>Coba kembali dengan metode pembayaran yang sama atau berbeda</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-phone text-red-500 mt-1 mr-3"></i>
                        <span>Hubungi admin jika mengalami kendala berulang</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-money-bill-wave text-red-500 mt-1 mr-3"></i>
                        <span>Gunakan metode pembayaran cash di pesantren</span>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('santri.payments.create') }}"
                   class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300 font-semibold">
                    Coba Pembayaran Lagi
                </a>
                <a href="{{ route('santri.dashboard') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300 font-semibold">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </main>
</div>
@endsection

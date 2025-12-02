@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
  @include('layouts.components.calon_santri.navbar')

    <main class="max-w-2xl mx-auto py-12 px-4 text-center">
        <div class="bg-white rounded-xl shadow-md p-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-3xl text-green-500"></i>
            </div>

            <h1 class="text-3xl font-bold text-green-600 mb-4">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 mb-6 text-lg">
                Terima kasih telah melakukan pembayaran. Status pendaftaran Anda akan segera diperbarui.
            </p>

            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-semibold text-green-800 mb-3">Informasi Penting:</h3>
                <ul class="text-green-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <span>Notifikasi WhatsApp telah dikirim ke nomor Anda</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-clock text-green-500 mt-1 mr-3"></i>
                        <span>Status akan diperbarui otomatis dalam beberapa menit</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-user-check text-green-500 mt-1 mr-3"></i>
                        <span>Tim admin akan menghubungi Anda untuk informasi selanjutnya</span>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('santri.payments.index') }}"
                   class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition duration-300 font-semibold">
                    Lihat Riwayat Pembayaran
                </a>
                <a href="{{ route('santri.dashboard') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300 font-semibold">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

    </main>
     @include('layouts.components.calon_santri.footer')
</div>
@endsection

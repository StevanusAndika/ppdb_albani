@extends('layouts.app')

@section('title', 'Register PPDB - Pondok Pesantren Bani Syahid')

@section('content')
<div class="auth-container text-white rounded-2xl w-full max-w-md overflow-hidden">
    <!-- Header dengan Logo PPDB -->
    <div class="p-6 text-center border-b border-white/20">
        <div class="logo-container">
            <div class="logo-text">PPDB</div>
        </div>
        <h1 class="text-2xl font-bold mt-2">Daftar PPDB</h1>
        <p class="text-white-90 text-sm mt-1">Pondok Pesantren Bani Syahid</p>

        <!-- Info Socialite -->
        @if(session('socialite_data'))
            <div class="mt-4 p-3 bg-blue-500/20 rounded-lg border border-blue-300/30">
                <p class="text-sm text-blue-100">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ session('info') }}
                </p>
            </div>
        @endif
    </div>

    <!-- Form Register -->
    <div class="p-6 md:p-8">
        @if(session('socialite_data'))
            <!-- Form untuk Socialite Registration -->
            <form action="{{ route('socialite.register.post') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="provider" value="{{ session('socialite_data.provider') }}">
                <input type="hidden" name="provider_id" value="{{ session('socialite_data.provider_id') }}">

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-white-90 text-sm font-medium mb-2">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ session('socialite_data.name') ?? old('name') }}"
                        class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500"
                        placeholder="Masukkan nama lengkap Anda"
                        required
                        autofocus
                    >
                    @error('name')
                        <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-white-90 text-sm font-medium mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ session('socialite_data.email') ?? old('email') }}"
                        class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500"
                        placeholder="Masukkan email Anda"
                        required
                        readonly
                    >
                    @error('email')
                        <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number Field -->
                <div>
                    <label for="phone_number" class="block text-white-90 text-sm font-medium mb-2">Nomor Telepon</label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500"
                        placeholder="Masukkan nomor telepon"
                    >
                    @error('phone_number')
                        <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-3 rounded-lg font-medium transition duration-200 shadow-md"
                >
                    <i class="fab fa-{{ session('socialite_data.provider') }} mr-2"></i>
                    Lengkapi Pendaftaran dengan {{ ucfirst(session('socialite_data.provider')) }}
                </button>
            </form>
        @else
            <!-- Form untuk Manual Registration -->
            <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-white-90 text-sm font-medium mb-2">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500"
                        placeholder="Masukkan nama lengkap Anda"
                        required
                        autofocus
                    >
                    @error('name')
                        <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-white-90 text-sm font-medium mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500"
                        placeholder="Masukkan email Anda"
                        required
                    >
                    @error('email')
                        <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number Field -->
                <div>
                    <label for="phone_number" class="block text-white-90 text-sm font-medium mb-2">Nomor Telepon</label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500"
                        placeholder="Masukkan nomor telepon"
                    >
                    @error('phone_number')
                        <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-white-90 text-sm font-medium mb-2">Password</label>
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 pr-10"
                            placeholder="Masukkan password (min. 8 karakter)"
                            required
                        >
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-white-90 text-sm font-medium mb-2">Konfirmasi Password</label>
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 pr-10"
                            placeholder="Masukkan ulang password"
                            required
                        >
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-3 rounded-lg font-medium transition duration-200 shadow-md"
                >
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t divider"></div>
                <span class="mx-4 text-white-90 text-sm">Atau</span>
                <div class="flex-grow border-t divider"></div>
            </div>

            <!-- Google Register Button -->
            <a href="{{ route('socialite.redirect', 'google') }}"
                class="btn-google w-full flex items-center justify-center gap-3 py-3 rounded-lg font-medium transition duration-200 shadow-md"
            >
                <span class="google-icon text-xl font-bold">G</span>
                Daftar dengan Google
            </a>
        @endif

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-white-90 text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-white font-medium hover:underline ml-1">Login di sini</a>
            </p>
        </div>
    </div>
</div>

<script>
// Auto show register suggestion for manual login
document.addEventListener('DOMContentLoaded', function() {
    @if(session('redirect_to_register'))
        const registerLink = document.createElement('div');
        registerLink.className = 'mt-4 p-3 bg-yellow-500/20 rounded-lg border border-yellow-300/30 text-center';
        registerLink.innerHTML = `
            <p class="text-sm text-yellow-100 mb-2">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Email tidak ditemukan. Belum punya akun?
            </p>
            <a href="{{ route('register') }}" class="text-white font-medium hover:underline">
                Daftar di sini
            </a>
        `;

        const form = document.querySelector('form');
        form.parentNode.insertBefore(registerLink, form.nextSibling);
    @endif
});
</script>
@endsection

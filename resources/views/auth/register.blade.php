@extends('layouts.app')

@section('title', 'Register PPDB - Pondok Pesantren Bani Syahid')

@section('content')
<div class="auth-container text-white rounded-2xl w-full max-w-md">
    <!-- Header dengan Logo PPDB -->
    <div class="p-3 md:p-6 text-center border-b border-white/20">
        <div class="logo-container">
            <div class="logo-text text-lg md:text-2xl">PPDB</div>
        </div>
        <h1 class="text-lg md:text-2xl font-bold mt-1 md:mt-2">Daftar PPDB</h1>
        <p class="text-white-90 text-xs md:text-sm mt-1">Pondok Pesantren Bani Syahid</p>

        <!-- Info Socialite -->
        @if(session('socialite_data'))
            <div class="mt-2 md:mt-3 p-2 md:p-3 bg-blue-500/20 rounded-lg border border-blue-300/30">
                <p class="text-xs md:text-sm text-white">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ session('info') }}
                </p>
            </div>
        @endif
    </div>

    <!-- Form Register -->
    <div class="p-3 md:p-6">
        @if(session('socialite_data'))
            <!-- Form untuk Socialite Registration -->
            <form action="{{ route('socialite.register.post') }}" method="POST" class="space-y-3 md:space-y-4">
                @csrf
                <input type="hidden" name="provider" value="{{ session('socialite_data.provider') }}">
                <input type="hidden" name="provider_id" value="{{ session('socialite_data.provider_id') }}">

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ session('socialite_data.name') ?? old('name') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm"
                        placeholder="Masukkan nama lengkap Anda"
                        required
                        autofocus
                    >
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ session('socialite_data.email') ?? old('email') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm"
                        placeholder="Masukkan email Anda"
                        required
                        readonly
                    >
                </div>

                <!-- Phone Number Field -->
                <div>
                    <label for="phone_number" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Nomor Telepon</label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm"
                        placeholder="Contoh: 081234567890"
                        maxlength="15"
                        oninput="validatePhoneNumber(this)"
                    >
                    <p class="text-xs text-white-90 mt-1">Hanya angka, maksimal 12 digit</p>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
                >
                    <i class="fab fa-{{ session('socialite_data.provider') }} mr-1 md:mr-2 text-xs md:text-sm"></i>
                    Lengkapi Pendaftaran
                </button>
            </form>
        @else
            <!-- Form untuk Manual Registration -->
            <form action="{{ route('register.post') }}" method="POST" class="space-y-3 md:space-y-4">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm"
                        placeholder="Masukkan nama lengkap Anda"
                        required
                        autofocus
                    >
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm"
                        placeholder="Masukkan email Anda"
                        required
                    >
                </div>

                <!-- Phone Number Field -->
                <div>
                    <label for="phone_number" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Nomor Telepon</label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm"
                        placeholder="Contoh: 081234567890"
                        maxlength="15"
                        oninput="validatePhoneNumber(this)"
                    >
                    <p class="text-xs text-white-90 mt-1">Hanya angka, maksimal 15 digit</p>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Password</label>
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 pr-10 md:pr-12 text-xs md:text-sm"
                            placeholder="Masukkan password"
                            required
                        >
                        <button type="button" class="password-toggle-btn" data-target="password">
                            <i class="fas fa-eye text-xs md:text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Konfirmasi Password</label>
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 pr-10 md:pr-12 text-xs md:text-sm"
                            placeholder="Masukkan ulang password"
                            required
                        >
                        <button type="button" class="password-toggle-btn" data-target="password_confirmation">
                            <i class="fas fa-eye text-xs md:text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
                >
                    <i class="fas fa-user-plus mr-1 md:mr-2 text-xs md:text-sm"></i>
                    Daftar Sekarang
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-3 md:my-4">
                <div class="flex-grow border-t divider"></div>
                <span class="mx-2 md:mx-3 text-white-90 text-xs md:text-sm">Atau</span>
                <div class="flex-grow border-t divider"></div>
            </div>

            <!-- Google Register Button -->
            <a href="{{ route('socialite.redirect', 'google') }}"
                class="btn-google w-full flex items-center justify-center gap-1 md:gap-2 py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
            >
                <span class="google-icon text-base md:text-lg font-bold">G</span>
                Daftar dengan Google
            </a>
        @endif

        <!-- Login Link -->
        <div class="mt-3 md:mt-4 text-center">
            <p class="text-white-90 text-xs md:text-sm">
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
        registerLink.className = 'mt-2 md:mt-3 p-2 md:p-3 bg-yellow-500/20 rounded-lg border border-yellow-300/30 text-center';
        registerLink.innerHTML = `
            <p class="text-xs md:text-sm text-white mb-1">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Email tidak ditemukan. Belum punya akun?
            </p>
            <a href="{{ route('register') }}" class="text-white font-medium hover:underline text-xs md:text-sm">
                Daftar di sini
            </a>
        `;

        const form = document.querySelector('form');
        form.parentNode.insertBefore(registerLink, form.nextSibling);
    @endif
});

// Validasi nomor telepon hanya angka
function validatePhoneNumber(input) {
    // Hapus semua karakter non-digit
    input.value = input.value.replace(/[^0-9]/g, '');

    // Batasi panjang maksimal 15 digit
    if (input.value.length > 12) {
        input.value = input.value.substring(0, 15);
    }
}

// Prevent paste non-numeric characters
document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');

    phoneInputs.forEach(input => {
        input.addEventListener('paste', function(e) {
            e.preventDefault();

            // Get pasted data
            const pastedData = e.clipboardData.getData('text');

            // Filter hanya angka
            const numbersOnly = pastedData.replace(/[^0-9]/g, '');

            // Insert filtered data
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const currentValue = input.value;

            input.value = currentValue.substring(0, start) + numbersOnly + currentValue.substring(end);

            // Set cursor position
            input.setSelectionRange(start + numbersOnly.length, start + numbersOnly.length);
        });
    });
});
</script>
@endsection

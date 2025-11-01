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
        {{-- @if(session('socialite_data'))
            <div class="mt-2 md:mt-3 p-2 md:p-3 bg-blue-500/20 rounded-lg border border-blue-300/30">
                <p class="text-xs md:text-sm text-white">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ session('info', 'Silakan lengkapi pendaftaran dengan Google.') }}
                </p>
            </div>
        @endif --}}
    </div>

    <!-- Form Register -->
    <div class="p-3 md:p-6">
        @if(session('socialite_data'))
            <!-- Form untuk Socialite Registration -->
            <form action="{{ route('socialite.register.post') }}" method="POST" class="space-y-3 md:space-y-4" id="socialiteRegisterForm">
                @csrf
                <input type="hidden" name="provider" value="{{ session('socialite_data.provider') }}">
                <input type="hidden" name="provider_id" value="{{ session('socialite_data.provider_id') }}">

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
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
                    <label for="email" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Email <span class="text-red-400">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ session('socialite_data.email') ?? old('email') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm bg-gray-100"
                        placeholder="Masukkan email Anda"
                        required
                        readonly
                    >
                </div>

                <!-- Phone Number Field -->
                <div>
                    <label for="phone_number" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Nomor Telepon <span class="text-red-400">*</span></label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm"
                        placeholder="Contoh: 081234567890"
                        maxlength="15"
                        oninput="validatePhoneNumber(this)"
                        required
                    >
                    <p class="text-xs text-white-90 mt-1">Hanya angka, maksimal 15 digit</p>
                    <div id="phone_error" class="text-xs text-red-400 mt-1 hidden"></div>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
                    id="socialiteSubmitBtn"
                >
                    <i class="fab fa-{{ session('socialite_data.provider') }} mr-1 md:mr-2 text-xs md:text-sm"></i>
                    Lengkapi Pendaftaran
                </button>
            </form>
        @else
            <!-- Form untuk Manual Registration -->
            <form action="{{ route('register.post') }}" method="POST" class="space-y-3 md:space-y-4" id="manualRegisterForm">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
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
                    <label for="email" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Email <span class="text-red-400">*</span></label>
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
                    <label for="phone_number" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Nomor Telepon <span class="text-red-400">*</span></label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-xs md:text-sm"
                        placeholder="Contoh: 081234567890"
                        maxlength="15"
                        oninput="validatePhoneNumber(this)"
                        required
                    >
                    <p class="text-xs text-white-90 mt-1">Hanya angka, maksimal 15 digit</p>
                    <div id="phone_error" class="text-xs text-red-400 mt-1 hidden"></div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Password <span class="text-red-400">*</span></label>
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
                    <label for="password_confirmation" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Konfirmasi Password <span class="text-red-400">*</span></label>
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
                    id="manualSubmitBtn"
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
// Validasi nomor telepon hanya angka
function validatePhoneNumber(input) {
    // Hapus semua karakter non-digit
    const originalValue = input.value;
    input.value = input.value.replace(/[^0-9]/g, '');

    // Batasi panjang maksimal 15 digit
    if (input.value.length > 15) {
        input.value = input.value.substring(0, 15);
    }

    // Tampilkan pesan error jika diperlukan
    const errorElement = document.getElementById('phone_error');
    if (input.value.length < 10 && input.value.length > 0) {
        errorElement.textContent = 'Nomor telepon minimal 10 digit';
        errorElement.classList.remove('hidden');
    } else if (input.value.length === 0) {
        errorElement.textContent = 'Nomor telepon wajib diisi';
        errorElement.classList.remove('hidden');
    } else {
        errorElement.classList.add('hidden');
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

            // Trigger validation
            validatePhoneNumber(input);
        });

        // Validasi saat form submit
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const phoneValue = input.value.trim();
                if (phoneValue.length === 0) {
                    e.preventDefault();
                    document.getElementById('phone_error').textContent = 'Nomor telepon wajib diisi';
                    document.getElementById('phone_error').classList.remove('hidden');
                    input.focus();
                } else if (phoneValue.length < 10) {
                    e.preventDefault();
                    document.getElementById('phone_error').textContent = 'Nomor telepon minimal 10 digit';
                    document.getElementById('phone_error').classList.remove('hidden');
                    input.focus();
                }
            });
        }
    });
});
</script>
@endsection

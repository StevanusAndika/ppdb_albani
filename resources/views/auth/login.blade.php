@extends('layouts.app')

@section('title', 'Login PPDB - Pondok Pesantren Bani Syahid')

@section('content')
<div class="auth-container text-white rounded-2xl w-full max-w-md">
    <!-- Header dengan Logo PPDB -->
    <div class="p-3 md:p-6 text-center border-b border-white/20">
        <div class="logo-container">
            <div class="logo-text text-lg md:text-2xl">PPDB</div>
        </div>
        <h1 class="text-lg md:text-2xl font-bold mt-1 md:mt-2">Login PPDB</h1>
        <p class="text-white-90 text-xs md:text-sm mt-1">Pondok Pesantren Bani Syahid</p>
    </div>

    <!-- Form Login -->
    <div class="p-3 md:p-6">
        <form action="{{ route('login.post') }}" method="POST" class="space-y-3 md:space-y-4">
            @csrf

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
                    autofocus
                >
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
                        placeholder="Masukkan password Anda"
                        required
                    >
                    <button type="button" class="password-toggle-btn" data-target="password">
                        <i class="fas fa-eye text-xs md:text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-xs md:text-sm">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-white focus:ring-white scale-75 md:scale-100">
                    <span class="ml-1 md:ml-2 text-white-90">Ingat saya</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-white hover:underline text-xs md:text-sm">Lupa password?</a>
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
            >
                <i class="fas fa-sign-in-alt mr-1 md:mr-2 text-xs md:text-sm"></i>
                Login
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-3 md:my-4">
            <div class="flex-grow border-t divider"></div>
            <span class="mx-2 md:mx-3 text-white-90 text-xs md:text-sm">Atau</span>
            <div class="flex-grow border-t divider"></div>
        </div>

        <!-- Google Login Button -->
        <a href="{{ route('socialite.redirect', 'google') }}"
            class="btn-google w-full flex items-center justify-center gap-1 md:gap-2 py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
        >
            <span class="google-icon text-base md:text-lg font-bold">G</span>
            Login dengan Google
        </a>

        <!-- Register Link -->
        <div class="mt-3 md:mt-4 text-center">
            <p class="text-white-90 text-xs md:text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-white font-medium hover:underline ml-1">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>

<script>
// Auto show register suggestion
document.addEventListener('DOMContentLoaded', function() {
    @if(session('redirect_to_register'))
        const registerSuggestion = document.createElement('div');
        registerSuggestion.className = 'mt-2 md:mt-3 p-2 md:p-3 bg-yellow-500/20 rounded-lg border border-yellow-300/30 text-center';
        registerSuggestion.innerHTML = `
            <p class="text-xs md:text-sm text-white mb-1">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Email tidak ditemukan. Belum punya akun?
            </p>
            <a href="{{ route('register') }}" class="text-white font-medium hover:underline bg-yellow-600 hover:bg-yellow-700 px-2 py-1 md:px-3 md:py-1 rounded-lg transition duration-200 text-xs md:text-sm">
                Daftar Sekarang
            </a>
        `;

        const form = document.querySelector('form');
        form.parentNode.insertBefore(registerSuggestion, form.nextSibling);
    @endif

    @if(session('socialite_user_no_password'))
        const socialiteSuggestion = document.createElement('div');
        socialiteSuggestion.className = 'mt-2 md:mt-3 p-2 md:p-3 bg-blue-500/20 rounded-lg border border-blue-300/30 text-center';
        socialiteSuggestion.innerHTML = `
            <p class="text-xs md:text-sm text-white mb-1">
                <i class="fas fa-info-circle mr-1"></i>
                Akun Google terdeteksi. Reset password untuk login manual.
            </p>
            <a href="{{ route('password.request') }}" class="text-white font-medium hover:underline bg-blue-600 hover:bg-blue-700 px-2 py-1 md:px-3 md:py-1 rounded-lg transition duration-200 text-xs md:text-sm">
                Reset Password
            </a>
        `;

        const form = document.querySelector('form');
        form.parentNode.insertBefore(socialiteSuggestion, form.nextSibling);
    @endif
});
</script>
@endsection

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
        <form action="{{ route('login.post') }}" method="POST" class="space-y-3 md:space-y-4" id="loginForm">
            @csrf
            <input type="hidden" name="recaptcha_enabled" value="{{ $recaptcha_enabled ? 'true' : 'false' }}">

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
                    autofocus
                >
                @error('email')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
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
                @error('password')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- reCAPTCHA Error -->
            @error('recaptcha')
                <div class="p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                    <p class="text-xs text-red-400">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                </div>
            @enderror

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="ml-2 text-white-90 text-xs md:text-sm">Ingat saya</span>
                </label>

                <a href="{{ route('password.request') }}" class="text-white hover:underline text-xs md:text-sm">
                    Lupa password?
                </a>
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
                id="loginSubmitBtn"
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
            class="btn-google w-full flex items-center justify-center gap-1 md:gap-2 py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm mb-3 md:mb-4"
        >
            <span class="google-icon text-base md:text-lg font-bold">G</span>
            Login dengan Google
        </a>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-white-90 text-xs md:text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-white font-medium hover:underline ml-1">Daftar di sini</a>
            </p>
        </div>

        <!-- Auto Redirect to Register Section -->
        @if(session('redirect_to_register'))
            <div class="mt-3 md:mt-4 p-2 md:p-3 bg-yellow-500/20 rounded-lg border border-yellow-300/30 text-center">
                <p class="text-xs md:text-sm text-white mb-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    Email tidak ditemukan. Belum punya akun?
                </p>
                <a href="{{ route('register') }}" class="text-white font-medium hover:underline text-xs md:text-sm">
                    Daftar di sini
                </a>
            </div>
        @endif

        <!-- Socialite User No Password Warning -->
        @if(session('socialite_user_no_password'))
            <div class="mt-3 md:mt-4 p-2 md:p-3 bg-blue-500/20 rounded-lg border border-blue-300/30 text-center">
                <p class="text-xs md:text-sm text-white mb-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Akun ini terdaftar melalui Google. Silakan login menggunakan Google.
                </p>
                <a href="{{ route('socialite.redirect', 'google') }}" class="text-white font-medium hover:underline text-xs md:text-sm">
                    Login dengan Google
                </a>
            </div>
        @endif

        <!-- Locked Account Message -->
        @if(session('locked_user_email'))
            <div class="mt-3 md:mt-4 p-2 md:p-3 bg-red-500/20 rounded-lg border border-red-300/30 text-center">
                <p class="text-xs md:text-sm text-white mb-1">
                    <i class="fas fa-lock mr-1"></i>
                    Akun terkunci. Silakan reset password.
                </p>
                <a href="{{ route('password.request') }}" class="text-white font-medium hover:underline text-xs md:text-sm">
                    Reset Password
                </a>
            </div>
        @endif
    </div>
</div>

@include('auth.scripts.password-toggle')
<script>
// Auto focus on email field if there's redirect_to_register session
@if(session('redirect_to_register'))
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.focus();
            emailInput.select();
        }
    });
@endif

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginSubmitBtn = document.getElementById('loginSubmitBtn');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email || !password) {
                e.preventDefault();
                return;
            }

            // Show loading state
            loginSubmitBtn.disabled = true;
            loginSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        });
    }
});
</script>
@endsection

@section('styles')
@include('auth.styles.auth-styles')
@endsection

@section('scripts')
@if($recaptcha_enabled)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptcha_site_key }}"></script>
@include('auth.scripts.recaptcha')
@endif
@endsection

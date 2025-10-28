@extends('layouts.app')

@section('title', 'Login PPDB - Pondok Pesantren Bani Syahid')

@section('content')
<div class="auth-container text-white rounded-2xl w-full max-w-md overflow-hidden">
    <!-- Header dengan Logo PFDB -->
    <div class="p-6 text-center border-b border-white/20">
        <div class="logo-container">
            <div class="logo-text">PPDB</div>
        </div>
        <h1 class="text-2xl font-bold mt-2">Login PPDB</h1>
        <p class="text-white-90 text-sm mt-1">Pondok Pesantren Bani Syahid</p>
    </div>

    <!-- Form Login -->
    <div class="p-6 md:p-8">
        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

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
                    autofocus
                >
                @error('email')
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
                        placeholder="Masukkan password Anda"
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

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="ml-2 text-white-90 text-sm">Ingat saya</span>
                </label>
                <a href="#" class="text-white text-sm hover:underline">Lupa password?</a>
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                class="btn-primary w-full py-3 rounded-lg font-medium transition duration-200 shadow-md"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>
                Login
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-6">
            <div class="flex-grow border-t divider"></div>
            <span class="mx-4 text-white-90 text-sm">Atau</span>
            <div class="flex-grow border-t divider"></div>
        </div>

        <!-- Google Login Button -->
        <a href="{{ route('socialite.redirect', 'google') }}"
            class="btn-google w-full flex items-center justify-center gap-3 py-3 rounded-lg font-medium transition duration-200 shadow-md"
        >
            <span class="google-icon text-xl font-bold">G</span>
            Login dengan Google
        </a>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-white-90 text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-white font-medium hover:underline ml-1">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection

<script>
// Auto show register suggestion
document.addEventListener('DOMContentLoaded', function() {
    @if(session('redirect_to_register'))
        const registerSuggestion = document.createElement('div');
        registerSuggestion.className = 'mt-4 p-3 bg-yellow-500/20 rounded-lg border border-yellow-300/30 text-center';
        registerSuggestion.innerHTML = `
            <p class="text-sm text-yellow-100 mb-2">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Email tidak ditemukan. Belum punya akun?
            </p>
            <a href="{{ route('register') }}" class="text-white font-medium hover:underline bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-lg transition duration-200">
                Daftar Sekarang
            </a>
        `;

        const form = document.querySelector('form');
        form.parentNode.insertBefore(registerSuggestion, form.nextSibling);
    @endif
});
</script>

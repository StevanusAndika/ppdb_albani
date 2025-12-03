@extends('layouts.app')

@section('title', 'Lupa Password - PPDB Pondok Pesantren Bani Syahid')

@section('content')
<div class="auth-container text-white rounded-2xl w-full max-w-md">
    <!-- Header dengan Logo PPDB -->
    <div class="p-3 md:p-6 text-center border-b border-white/20">
        <div class="logo-container">
            <div class="logo-text text-lg md:text-2xl">PPDB</div>
        </div>
        <h1 class="text-lg md:text-2xl font-bold mt-1 md:mt-2">Reset Password</h1>
        <p class="text-white-90 text-xs md:text-sm mt-1">Pondok Pesantren Bani Syahid</p>
    </div>

    <!-- Form Lupa Password -->
    <div class="p-3 md:p-6">
        @if(!session('show_otp_verification') && !session('show_password_reset'))
            <!-- Form Input Email -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-3 md:space-y-4" id="forgotPasswordForm">
                @csrf
                <input type="hidden" name="recaptcha_enabled" value="{{ $recaptcha_enabled ? 'true' : 'false' }}">

                <div class="text-center mb-2 md:mb-3">
                    <p class="text-white-90 text-xs md:text-sm">
                        Masukkan email Anda yang terdaftar. Kami akan mengirim kode OTP via WhatsApp.
                    </p>
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
                        placeholder="Masukkan email terdaftar"
                        required
                        autofocus
                    >
                    @error('email')
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

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
                    id="forgotPasswordBtn"
                >
                    <i class="fas fa-key mr-1 md:mr-2 text-xs md:text-sm"></i>
                    Kirim OTP via WhatsApp
                </button>

                <!-- Back to Login -->
                <div class="text-center mt-2 md:mt-3">
                    <a href="{{ route('login') }}" class="text-white text-xs md:text-sm hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        @elseif(session('show_otp_verification'))
            <!-- Form Verifikasi OTP -->
            <form action="{{ route('password.verify.otp') }}" method="POST" class="space-y-3 md:space-y-4" id="otpVerificationForm">
                @csrf
                <input type="hidden" name="email" value="{{ session('user_email') }}">

                <div class="text-center mb-2 md:mb-3">
                    <div class="w-10 h-10 md:w-12 md:h-12 mx-auto mb-1 md:mb-2 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-blue-300 text-base md:text-lg"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold text-white mb-1 md:mb-2">Verifikasi OTP</h3>
                    <p class="text-white-90 text-xs md:text-sm">
                        Kode OTP telah dikirim via WhatsApp
                    </p>
                    <p class="text-blue-100 text-xs mt-1">
                        <i class="fas fa-clock mr-1"></i>
                        Berlaku 5 menit <!-- DIUBAH: dari 10 menit menjadi 5 menit -->
                    </p>
                </div>

                <!-- OTP Field -->
                <div>
                    <label for="otp" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Kode OTP</label>
                    <input
                        type="text"
                        id="otp"
                        name="otp"
                        class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 text-center text-lg tracking-widest text-xs md:text-sm"
                        placeholder="XXXXXX"
                        maxlength="6"
                        required
                        autofocus
                        pattern="[0-9]{6}"
                    >
                    <div class="text-center mt-1">
                        <button type="button" id="resend-otp-btn" class="text-blue-300 text-xs hover:underline disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-redo mr-1"></i>
                            <span id="resend-text">Kirim ulang OTP</span>
                            <span id="countdown" class="hidden"></span>
                        </button>
                    </div>
                    @error('otp')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
                    id="verifyOtpBtn"
                >
                    <i class="fas fa-check mr-1 md:mr-2 text-xs md:text-sm"></i>
                    Verifikasi OTP
                </button>

                <!-- Back to Input Form -->
                <div class="text-center mt-2 md:mt-3">
                    <button
                        type="button"
                        onclick="window.location.reload()"
                        class="text-white text-xs md:text-sm hover:underline"
                    >
                        <i class="fas fa-edit mr-1"></i>
                        Ganti Email
                    </button>
                </div>
            </form>
        @elseif(session('show_password_reset'))
            <!-- Form Reset Password setelah OTP terverifikasi -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-3 md:space-y-4" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="email" value="{{ session('user_email') }}">
                <input type="hidden" name="otp" value="{{ session('otp_code') }}"> <!-- DIUBAH: dari old('otp') ke session('otp_code') -->

                <div class="text-center mb-2 md:mb-3">
                    <div class="w-10 h-10 md:w-12 md:h-12 mx-auto mb-1 md:mb-2 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-lock text-green-300 text-base md:text-lg"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold text-white mb-1 md:mb-2">Password Baru</h3>
                    <p class="text-white-90 text-xs md:text-sm">
                        OTP berhasil diverifikasi. Buat password baru.
                    </p>
                </div>

                <!-- New Password Field -->
                <div>
                    <label for="new_password" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Password Baru</label>
                    <div class="password-input-wrapper relative">
                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 pr-10 text-xs md:text-sm"
                            placeholder="Masukkan password baru"
                            required
                            minlength="8"
                            autofocus
                        >
                        <button type="button" class="password-toggle-btn" data-target="new_password">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                    <!-- Password Strength Indicator -->
                    <div class="password-strength mt-2 md:mt-3">
                        <div class="strength-bars flex gap-1 mb-2">
                            <div class="strength-bar flex-1 h-2 bg-gray-300 rounded transition-all" data-strength="weak"></div>
                            <div class="strength-bar flex-1 h-2 bg-gray-300 rounded transition-all" data-strength="medium"></div>
                            <div class="strength-bar flex-1 h-2 bg-gray-300 rounded transition-all" data-strength="strong"></div>
                            <div class="strength-bar flex-1 h-2 bg-gray-300 rounded transition-all" data-strength="very-strong"></div>
                        </div>
                        <div class="strength-text">
                            <span id="password-strength-text" class="text-xs text-white-90">Kekuatan password</span>
                            <ul class="password-requirements mt-2 space-y-1" id="password-requirements">
                                <li class="requirement flex items-center" data-requirement="length">
                                    <i class="fas fa-times text-red-400 mr-2 text-xs"></i>
                                    <span class="text-xs text-white-90">Minimal 8 karakter</span>
                                </li>
                                <li class="requirement flex items-center" data-requirement="lowercase">
                                    <i class="fas fa-times text-red-400 mr-2 text-xs"></i>
                                    <span class="text-xs text-white-90">Huruf kecil (a-z)</span>
                                </li>
                                <li class="requirement flex items-center" data-requirement="uppercase">
                                    <i class="fas fa-times text-red-400 mr-2 text-xs"></i>
                                    <span class="text-xs text-white-90">Huruf besar (A-Z)</span>
                                </li>
                                <li class="requirement flex items-center" data-requirement="number">
                                    <i class="fas fa-times text-red-400 mr-2 text-xs"></i>
                                    <span class="text-xs text-white-90">Angka (0-9)</span>
                                </li>
                                <li class="requirement flex items-center" data-requirement="symbol">
                                    <i class="fas fa-times text-red-400 mr-2 text-xs"></i>
                                    <span class="text-xs text-white-90">Simbol (@$!%*?&)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Confirm New Password Field -->
                <div>
                    <label for="new_password_confirmation" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Konfirmasi Password</label>
                    <div class="password-input-wrapper relative">
                        <input
                            type="password"
                            id="new_password_confirmation"
                            name="new_password_confirmation"
                            class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 pr-10 text-xs md:text-sm"
                            placeholder="Konfirmasi password baru"
                            required
                            minlength="8"
                        >
                        <button type="button" class="password-toggle-btn" data-target="new_password_confirmation">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                    <div class="confirmation-status mt-2">
                        <span id="password-match-text" class="text-xs hidden items-center">
                            <i class="fas fa-check text-green-400 mr-2 text-xs"></i>
                            <span class="text-green-400 text-xs font-medium">Password cocok</span>
                        </span>
                        <span id="password-mismatch-text" class="text-xs hidden items-center">
                            <i class="fas fa-times text-red-400 mr-2 text-xs"></i>
                            <span class="text-red-400 text-xs font-medium">Password tidak cocok</span>
                        </span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400"
                    id="reset-button"
                    disabled
                >
                    <i class="fas fa-check mr-1 md:mr-2 text-xs md:text-sm"></i>
                    Reset Password
                </button>

                <!-- Back Button -->
                <div class="text-center mt-2 md:mt-3">
                    <button
                        type="button"
                        onclick="window.location.href='{{ route('password.request') }}'"
                        class="text-white text-xs md:text-sm hover:underline flex items-center justify-center mx-auto"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Lupa Password
                    </button>
                </div>
            </form>
        @endif

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mt-3 md:mt-4 p-3 bg-green-500/10 border border-green-500/20 rounded-lg">
                <p class="text-xs md:text-sm text-green-400">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-3 md:mt-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                <p class="text-xs md:text-sm text-red-400">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </p>
            </div>
        @endif

        @if(session('info'))
            <div class="mt-3 md:mt-4 p-3 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                <p class="text-xs md:text-sm text-blue-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    {{ session('info') }}
                </p>
            </div>
        @endif
    </div>
</div>

@include('auth.scripts.password-toggle')
@if(session('show_otp_verification'))
    @include('auth.scripts.otp-resend')
@endif

@if(session('show_password_reset'))
    @include('auth.scripts.password-strength')
@endif
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

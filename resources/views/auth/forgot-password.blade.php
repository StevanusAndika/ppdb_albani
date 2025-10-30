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
        @if(!session('show_confirmation'))
            <!-- Form Input Email -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-3 md:space-y-4" id="forgotPasswordForm">
                @csrf

                <div class="text-center mb-2 md:mb-3">
                    <p class="text-white-90 text-xs md:text-sm">
                        Masukkan email Anda yang terdaftar. Kami akan memverifikasi dan membantu reset password.
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
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
                >
                    <i class="fas fa-key mr-1 md:mr-2 text-xs md:text-sm"></i>
                    Verifikasi Email
                </button>

                <!-- Back to Login -->
                <div class="text-center mt-2 md:mt-3">
                    <a href="{{ route('login') }}" class="text-white text-xs md:text-sm hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        @else
            <!-- Konfirmasi Reset Password dengan Input Password Baru -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-3 md:space-y-4" id="resetConfirmationForm">
                @csrf
                <input type="hidden" name="email" value="{{ session('user_email') }}">

                <div class="text-center mb-2 md:mb-3">
                    <div class="w-10 h-10 md:w-12 md:h-12 mx-auto mb-1 md:mb-2 bg-yellow-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-300 text-base md:text-lg"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold text-white mb-1 md:mb-2">Reset Password</h3>
                    <p class="text-white-90 text-xs md:text-sm">
                        Email: <span class="font-medium text-white">{{ session('user_email') }}</span>
                    </p>
                    <p class="text-yellow-100 text-xs mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Buat password baru yang kuat
                    </p>
                </div>

                <!-- New Password Field -->
                <div>
                    <label for="new_password" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Password Baru</label>
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 pr-10 md:pr-12 text-xs md:text-sm"
                            placeholder="Masukkan password baru"
                            required
                            minlength="8"
                        >
                        <button type="button" class="password-toggle-btn" data-target="new_password">
                            <i class="fas fa-eye text-xs md:text-sm"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator - Compact -->
                    <div class="password-strength mt-1 md:mt-2">
                        <div class="strength-bars flex gap-1 mb-1 md:mb-2">
                            <div class="strength-bar flex-1 h-1 md:h-2 bg-gray-300 rounded transition-all" data-strength="weak"></div>
                            <div class="strength-bar flex-1 h-1 md:h-2 bg-gray-300 rounded transition-all" data-strength="medium"></div>
                            <div class="strength-bar flex-1 h-1 md:h-2 bg-gray-300 rounded transition-all" data-strength="strong"></div>
                            <div class="strength-bar flex-1 h-1 md:h-2 bg-gray-300 rounded transition-all" data-strength="very-strong"></div>
                        </div>
                        <div class="strength-text text-xs">
                            <span id="password-strength-text" class="text-xs">Kekuatan password</span>
                            <ul class="password-requirements mt-1 space-y-0.5 hidden" id="password-requirements">
                                <li class="requirement" data-requirement="length">
                                    <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                                    <span class="text-xs">8+ karakter</span>
                                </li>
                                <li class="requirement" data-requirement="lowercase">
                                    <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                                    <span class="text-xs">Huruf kecil</span>
                                </li>
                                <li class="requirement" data-requirement="uppercase">
                                    <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                                    <span class="text-xs">Huruf besar</span>
                                </li>
                                <li class="requirement" data-requirement="number">
                                    <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                                    <span class="text-xs">Angka</span>
                                </li>
                                <li class="requirement" data-requirement="symbol">
                                    <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                                    <span class="text-xs">Simbol</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Confirm New Password Field -->
                <div>
                    <label for="new_password_confirmation" class="block text-white-90 text-xs md:text-sm font-medium mb-1 md:mb-2">Konfirmasi Password</label>
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="new_password_confirmation"
                            name="new_password_confirmation"
                            class="input-field w-full px-3 py-2 md:px-4 md:py-3 rounded-lg focus:ring-2 focus:ring-white transition text-gray-800 placeholder-gray-500 pr-10 md:pr-12 text-xs md:text-sm"
                            placeholder="Konfirmasi password baru"
                            required
                            minlength="8"
                        >
                        <button type="button" class="password-toggle-btn" data-target="new_password_confirmation">
                            <i class="fas fa-eye text-xs md:text-sm"></i>
                        </button>
                    </div>
                    <div class="confirmation-status mt-1">
                        <span id="password-match-text" class="text-xs hidden">
                            <i class="fas fa-check text-green-400 mr-1 text-xs"></i>
                            <span class="text-white text-xs">Password cocok</span>
                        </span>
                        <span id="password-mismatch-text" class="text-xs hidden">
                            <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                            <span class="text-white text-xs">Password tidak cocok</span>
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button
                        type="submit"
                        name="action"
                        value="confirm"
                        class="btn-primary flex-1 py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md confirm-reset disabled:opacity-50 disabled:cursor-not-allowed text-xs md:text-sm"
                        id="reset-button"
                        disabled
                    >
                        <i class="fas fa-check mr-1 md:mr-2 text-xs md:text-sm"></i>
                        Reset Password
                    </button>

                   
                </div>

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
        @endif
    </div>
</div>

@section('scripts')
<script>
// Password strength checker untuk forgot password
function initPasswordStrengthChecker() {
    const passwordInput = document.getElementById('new_password');
    const confirmInput = document.getElementById('new_password_confirmation');
    const resetButton = document.getElementById('reset-button');

    if (!passwordInput || !confirmInput) return;

    function validateForm() {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        const strength = checkPasswordStrength(password);

        // Enable button only if password is very strong and matches
        if (strength.strength === 'very-strong' && password === confirmPassword && password.length >= 8) {
            resetButton.disabled = false;
        } else {
            resetButton.disabled = true;
        }
    }

    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        validateForm();
        checkPasswordMatch();
    });

    confirmInput.addEventListener('input', function() {
        checkPasswordMatch();
        validateForm();
    });

    // Initial validation
    validateForm();
}

function checkPasswordStrength(password) {
    const requirements = {
        length: password.length >= 8,
        lowercase: /[a-z]/.test(password),
        uppercase: /[A-Z]/.test(password),
        number: /\d/.test(password),
        symbol: /[@$!%*?&]/.test(password)
    };

    // Calculate strength score
    const metRequirements = Object.values(requirements).filter(Boolean).length;
    let strength = 'weak';
    let strengthText = 'Lemah';
    let strengthColor = 'red';

    if (metRequirements === 5) {
        strength = 'very-strong';
        strengthText = 'Sangat Kuat';
        strengthColor = 'green';
    } else if (metRequirements >= 4) {
        strength = 'strong';
        strengthText = 'Kuat';
        strengthColor = 'lightgreen';
    } else if (metRequirements >= 3) {
        strength = 'medium';
        strengthText = 'Sedang';
        strengthColor = 'orange';
    }

    // Update UI
    updatePasswordStrengthUI(requirements, strength, strengthText, strengthColor);

    return { requirements, strength, strengthText, strengthColor };
}

function updatePasswordStrengthUI(requirements, strength, strengthText, strengthColor) {
    // Update strength bars
    const bars = document.querySelectorAll('.strength-bar');
    bars.forEach(bar => {
        const barStrength = bar.getAttribute('data-strength');
        bar.style.backgroundColor = '#d1d5db'; // Reset to gray

        if (barStrength === 'weak' && strength !== 'weak') {
            bar.style.backgroundColor = strength === 'very-strong' ? '#10b981' :
                                      strength === 'strong' ? '#34d399' :
                                      strength === 'medium' ? '#f59e0b' : '#d1d5db';
        } else if (barStrength === 'medium' && (strength === 'medium' || strength === 'strong' || strength === 'very-strong')) {
            bar.style.backgroundColor = strength === 'very-strong' ? '#10b981' :
                                      strength === 'strong' ? '#34d399' : '#f59e0b';
        } else if (barStrength === 'strong' && (strength === 'strong' || strength === 'very-strong')) {
            bar.style.backgroundColor = strength === 'very-strong' ? '#10b981' : '#34d399';
        } else if (barStrength === 'very-strong' && strength === 'very-strong') {
            bar.style.backgroundColor = '#10b981';
        }
    });

    // Update strength text
    const strengthTextElement = document.getElementById('password-strength-text');
    strengthTextElement.textContent = `Kekuatan: ${strengthText}`;
    strengthTextElement.className = `text-xs font-medium ${
        strength === 'very-strong' ? 'text-green-400' :
        strength === 'strong' ? 'text-green-300' :
        strength === 'medium' ? 'text-yellow-400' : 'text-red-400'
    }`;

    // Update requirements list
    const requirementsList = document.getElementById('password-requirements');
    requirementsList.classList.remove('hidden');

    Object.keys(requirements).forEach(req => {
        const requirementElement = document.querySelector(`[data-requirement="${req}"]`);
        if (requirementElement) {
            const icon = requirementElement.querySelector('i');
            const text = requirementElement.querySelector('span');

            if (requirements[req]) {
                icon.className = 'fas fa-check text-green-400 mr-1 text-xs';
                requirementElement.classList.add('met');
                requirementElement.classList.remove('not-met');
            } else {
                icon.className = 'fas fa-times text-red-400 mr-1 text-xs';
                requirementElement.classList.add('not-met');
                requirementElement.classList.remove('met');
            }
        }
    });
}

function checkPasswordMatch() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const matchText = document.getElementById('password-match-text');
    const mismatchText = document.getElementById('password-mismatch-text');

    if (confirmPassword.length === 0) {
        matchText.classList.add('hidden');
        mismatchText.classList.add('hidden');
        return;
    }

    if (password === confirmPassword) {
        matchText.classList.remove('hidden');
        mismatchText.classList.add('hidden');
    } else {
        matchText.classList.add('hidden');
        mismatchText.classList.remove('hidden');
    }
}

// Initialize when document is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize password strength checker for forgot password
    if (document.getElementById('new_password')) {
        initPasswordStrengthChecker();
    }
});
</script>
@endsection

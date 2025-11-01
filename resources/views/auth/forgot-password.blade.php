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
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
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
                        Berlaku 10 menit
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
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 md:py-3 rounded-lg font-medium transition duration-200 shadow-md text-xs md:text-sm"
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
                <input type="hidden" name="otp" value="{{ old('otp') }}">

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
                    <!-- Password Strength Indicator -->
                    <div class="password-strength mt-2 md:mt-3">
                        <div class="strength-bars flex gap-1 mb-2">
                            <div class="strength-bar flex-1 h-2 bg-gray-300 rounded transition-all" data-strength="weak"></div>
                            <div class="strength-bar flex-1 h-2 bg-gray-300 rounded transition-all" data-target="new_password"></div>
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

                <!-- Back to OTP Form -->
                <div class="text-center mt-2 md:mt-3">
                    <button
                        type="button"
                        onclick="history.back()"
                        class="text-white text-xs md:text-sm hover:underline flex items-center justify-center mx-auto"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Verifikasi OTP
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<style>
.password-input-wrapper {
    position: relative;
}

.password-toggle-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
}

.password-toggle-btn:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.password-toggle-btn:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Strength bar colors */
.strength-bar.weak { background-color: #ef4444 !important; }
.strength-bar.medium { background-color: #f59e0b !important; }
.strength-bar.strong { background-color: #10b981 !important; }
.strength-bar.very-strong { background-color: #047857 !important; }

/* Requirement styles */
.requirement.met i {
    color: #10b981 !important;
}

.requirement.met span {
    color: #10b981 !important;
}

.requirement.not-met i {
    color: #ef4444 !important;
}

.requirement.not-met span {
    color: #ef4444 !important;
}

/* Button styles */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.input-field {
    padding-right: 45px !important;
}
</style>

@section('scripts')
<script>
// Auto move to next input for OTP
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length === 6) {
                document.getElementById('otpVerificationForm').submit();
            }
        });
    }

    // Resend OTP functionality
    const resendBtn = document.getElementById('resend-otp-btn');
    if (resendBtn) {
        let countdown = 60;
        const resendText = document.getElementById('resend-text');
        const countdownEl = document.getElementById('countdown');

        function startCountdown() {
            resendBtn.disabled = true;
            resendText.classList.add('hidden');
            countdownEl.classList.remove('hidden');
            countdownEl.textContent = `(${countdown}s)`;

            const timer = setInterval(() => {
                countdown--;
                countdownEl.textContent = `(${countdown}s)`;

                if (countdown <= 0) {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendText.classList.remove('hidden');
                    countdownEl.classList.add('hidden');
                    countdown = 60;
                }
            }, 1000);
        }

        resendBtn.addEventListener('click', function() {
            const email = document.querySelector('input[name="email"]').value;

            fetch('{{ route("password.resend.otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false,
                        background: '#f0fdf4',
                        color: '#166534'
                    });
                    startCountdown();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        timer: 4000,
                        showConfirmButton: true,
                        background: '#fef2f2',
                        color: '#dc2626'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengirim ulang OTP',
                    timer: 4000,
                    showConfirmButton: true,
                    background: '#fef2f2',
                    color: '#dc2626'
                });
            });
        });

        // Start countdown on page load
        startCountdown();
    }

    // Initialize password strength checker for reset password form
    if (document.getElementById('new_password')) {
        initPasswordStrengthChecker();
    }
});

// Password strength checker dengan validasi sederhana
function initPasswordStrengthChecker() {
    const passwordInput = document.getElementById('new_password');
    const confirmInput = document.getElementById('new_password_confirmation');
    const resetButton = document.getElementById('reset-button');

    if (!passwordInput || !confirmInput) {
        return;
    }

    let validationTimeout;

    function validateForm() {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;

        // Clear previous timeout
        if (validationTimeout) {
            clearTimeout(validationTimeout);
        }

        // Set new timeout untuk menghindari terlalu banyak validasi
        validationTimeout = setTimeout(() => {
            const isPasswordValid = password.length >= 8;
            const isConfirmValid = password === confirmPassword && confirmPassword !== '';

            // Enable button jika semua kondisi terpenuhi
            if (isPasswordValid && isConfirmValid) {
                resetButton.disabled = false;
                resetButton.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
                resetButton.classList.add('bg-gradient-to-r', 'from-green-500', 'to-green-600', 'hover:from-green-600', 'hover:to-green-700');
            } else {
                resetButton.disabled = true;
                resetButton.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
                resetButton.classList.remove('bg-gradient-to-r', 'from-green-500', 'to-green-600', 'hover:from-green-600', 'hover:to-green-700');
            }
        }, 100);
    }

    function checkPasswordStrength(password) {
        const requirements = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /\d/.test(password),
            symbol: /[@$!%*?&]/.test(password)
        };

        const metRequirements = Object.values(requirements).filter(Boolean).length;
        let strength = 'weak';
        let strengthText = 'Lemah';

        if (metRequirements === 5) {
            strength = 'very-strong';
            strengthText = 'Sangat Kuat';
        } else if (metRequirements >= 4) {
            strength = 'strong';
            strengthText = 'Kuat';
        } else if (metRequirements >= 3) {
            strength = 'medium';
            strengthText = 'Sedang';
        } else if (password.length >= 8) {
            strength = 'weak';
            strengthText = 'Lemah';
        } else {
            strength = 'weak';
            strengthText = 'Terlalu Pendek';
        }

        updatePasswordStrengthUI(requirements, strength, strengthText);
        return { requirements, strength, strengthText };
    }

    function updatePasswordStrengthUI(requirements, strength, strengthText) {
        // Update strength bars
        const bars = document.querySelectorAll('.strength-bar');
        bars.forEach(bar => {
            // Reset semua classes
            bar.classList.remove('weak', 'medium', 'strong', 'very-strong');

            const barStrength = bar.getAttribute('data-strength');

            // Apply color based on current strength
            if (barStrength === 'weak') {
                bar.classList.add(strength);
            } else if (barStrength === 'medium') {
                if (strength === 'medium' || strength === 'strong' || strength === 'very-strong') {
                    bar.classList.add(strength);
                }
            } else if (barStrength === 'strong') {
                if (strength === 'strong' || strength === 'very-strong') {
                    bar.classList.add(strength);
                }
            } else if (barStrength === 'very-strong') {
                if (strength === 'very-strong') {
                    bar.classList.add(strength);
                }
            }
        });

        // Update strength text
        const strengthTextElement = document.getElementById('password-strength-text');
        if (strengthTextElement) {
            strengthTextElement.textContent = `Kekuatan: ${strengthText}`;
            strengthTextElement.className = `text-xs font-medium ${
                strength === 'very-strong' ? 'text-green-400' :
                strength === 'strong' ? 'text-green-300' :
                strength === 'medium' ? 'text-yellow-400' : 'text-red-400'
            }`;
        }

        // Update requirements list
        const requirementsList = document.getElementById('password-requirements');
        if (requirementsList) {
            Object.keys(requirements).forEach(req => {
                const requirementElement = document.querySelector(`[data-requirement="${req}"]`);
                if (requirementElement) {
                    const icon = requirementElement.querySelector('i');

                    if (requirements[req]) {
                        icon.className = 'fas fa-check text-green-400 mr-2 text-xs';
                        requirementElement.classList.add('met');
                        requirementElement.classList.remove('not-met');
                    } else {
                        icon.className = 'fas fa-times text-red-400 mr-2 text-xs';
                        requirementElement.classList.add('not-met');
                        requirementElement.classList.remove('met');
                    }
                }
            });
        }
    }

    function checkPasswordMatch() {
        const password = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;
        const matchText = document.getElementById('password-match-text');
        const mismatchText = document.getElementById('password-mismatch-text');

        if (confirmPassword.length === 0) {
            if (matchText) matchText.classList.add('hidden');
            if (mismatchText) mismatchText.classList.add('hidden');
            return;
        }

        if (password === confirmPassword) {
            if (matchText) {
                matchText.classList.remove('hidden');
                matchText.classList.add('flex');
            }
            if (mismatchText) mismatchText.classList.add('hidden');
        } else {
            if (matchText) matchText.classList.add('hidden');
            if (mismatchText) {
                mismatchText.classList.remove('hidden');
                mismatchText.classList.add('flex');
            }
        }
    }

    // Event listeners
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
    checkPasswordMatch();
    if (passwordInput.value) {
        checkPasswordStrength(passwordInput.value);
    }
}
</script>
@endsection

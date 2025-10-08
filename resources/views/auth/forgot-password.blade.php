<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Pendaftaran Santri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-lock text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Reset Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan email dan password baru Anda
            </p>
        </div>

        <!-- Notifikasi -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-green-800 text-sm">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    <span class="text-red-800 font-medium">Terjadi kesalahan:</span>
                </div>
                <ul class="text-red-700 text-sm list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Reset Password -->
        <form class="mt-8 space-y-6 bg-white p-6 rounded-lg shadow-md" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-envelope mr-2"></i>Alamat Email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    required
                    value="{{ $email ?? old('email') }}"
                    class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror"
                    placeholder="Masukkan email Anda"
                    {{ $email ? 'readonly' : '' }}
                >
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-key mr-2"></i>Password Baru
                </label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password baru (min. 8 karakter)"
                    >
                    <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="password-eye"></i>
                    </button>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    <ul class="list-disc list-inside space-y-1">
                        <li id="length" class="text-gray-400">Minimal 8 karakter</li>
                        <li id="uppercase" class="text-gray-400">Mengandung huruf besar</li>
                        <li id="number" class="text-gray-400">Mengandung angka</li>
                    </ul>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-key mr-2"></i>Konfirmasi Password Baru
                </label>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        placeholder="Ketik ulang password baru"
                    >
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="password_confirmation-eye"></i>
                    </button>
                </div>
                <div id="password-match" class="mt-2 text-xs text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>Password harus sama
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    id="submit-btn"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                >
                    <i class="fas fa-redo mr-2"></i>
                    <span id="btn-text">Reset Password</span>
                    <i class="fas fa-spinner fa-spin ml-2 hidden" id="loading-icon"></i>
                </button>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium transition duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke halaman login
                </a>
            </div>
        </form>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-medium">Tips Password Aman:</p>
                    <ul class="list-disc list-inside mt-1 space-y-1">
                        <li>Gunakan kombinasi huruf besar, kecil, dan angka</li>
                        <li>Jangan gunakan informasi pribadi yang mudah ditebak</li>
                        <li>Gunakan password yang berbeda dari akun lain</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');

            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                eyeIcon.className = 'fas fa-eye';
            }
        }

        // Password strength validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const loadingIcon = document.getElementById('loading-icon');

        // Password requirements elements
        const lengthRequirement = document.getElementById('length');
        const uppercaseRequirement = document.getElementById('uppercase');
        const numberRequirement = document.getElementById('number');
        const matchRequirement = document.getElementById('password-match');

        function validatePassword() {
            const value = password.value;
            let isValid = true;

            // Validate length
            if (value.length >= 8) {
                lengthRequirement.className = 'text-green-500';
                lengthRequirement.innerHTML = '<i class="fas fa-check mr-1"></i>Minimal 8 karakter';
            } else {
                lengthRequirement.className = 'text-red-500';
                lengthRequirement.innerHTML = '<i class="fas fa-times mr-1"></i>Minimal 8 karakter';
                isValid = false;
            }

            // Validate uppercase
            if (/[A-Z]/.test(value)) {
                uppercaseRequirement.className = 'text-green-500';
                uppercaseRequirement.innerHTML = '<i class="fas fa-check mr-1"></i>Mengandung huruf besar';
            } else {
                uppercaseRequirement.className = 'text-red-500';
                uppercaseRequirement.innerHTML = '<i class="fas fa-times mr-1"></i>Mengandung huruf besar';
                isValid = false;
            }

            // Validate number
            if (/[0-9]/.test(value)) {
                numberRequirement.className = 'text-green-500';
                numberRequirement.innerHTML = '<i class="fas fa-check mr-1"></i>Mengandung angka';
            } else {
                numberRequirement.className = 'text-red-500';
                numberRequirement.innerHTML = '<i class="fas fa-times mr-1"></i>Mengandung angka';
                isValid = false;
            }

            // Validate confirmation
            if (confirmPassword.value && value === confirmPassword.value) {
                matchRequirement.className = 'text-green-500';
                matchRequirement.innerHTML = '<i class="fas fa-check mr-1"></i>Password cocok';
            } else if (confirmPassword.value) {
                matchRequirement.className = 'text-red-500';
                matchRequirement.innerHTML = '<i class="fas fa-times mr-1"></i>Password tidak cocok';
                isValid = false;
            } else {
                matchRequirement.className = 'text-gray-400';
                matchRequirement.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Password harus sama';
            }

            return isValid;
        }

        function validateForm() {
            const isPasswordValid = validatePassword();
            const isConfirmed = password.value && confirmPassword.value && password.value === confirmPassword.value;

            submitBtn.disabled = !(isPasswordValid && isConfirmed);
        }

        password.addEventListener('input', validateForm);
        confirmPassword.addEventListener('input', validateForm);

        // Form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!submitBtn.disabled) {
                btnText.textContent = 'Memproses...';
                loadingIcon.classList.remove('hidden');
                submitBtn.disabled = true;
            }
        });

        // Initial validation
        validateForm();
    </script>
</body>
</html>

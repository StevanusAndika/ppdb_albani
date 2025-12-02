@extends('layouts.app')

@section('title', 'Tambah User - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
 @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Tambah User Baru</h1>
        <p class="text-secondary">Tambahkan user baru ke sistem PPDB</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto py-6 px-4">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('admin.manage-users.store') }}" method="POST" id="userForm">
                @csrf

                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                        <input type="tel"
                               id="phone_number"
                               name="phone_number"
                               value="{{ old('phone_number') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                               required>
                        @error('phone_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                        <select id="role"
                                name="role"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                                required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Option -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Password *</label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio"
                                       id="password_generate"
                                       name="password_option"
                                       value="generate"
                                       class="focus:ring-primary text-primary"
                                       {{ old('password_option', 'generate') == 'generate' ? 'checked' : '' }}>
                                <label for="password_generate" class="ml-2 text-sm text-gray-700">
                                    Generate Password Acak
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio"
                                       id="password_manual"
                                       name="password_option"
                                       value="manual"
                                       class="focus:ring-primary text-primary"
                                       {{ old('password_option') == 'manual' ? 'checked' : '' }}>
                                <label for="password_manual" class="ml-2 text-sm text-gray-700">
                                    Input Password Manual
                                </label>
                            </div>
                        </div>
                        @error('password_option')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Manual Password Fields -->
                    <div id="manual_password_fields" class="space-y-4 {{ old('password_option') == 'manual' ? '' : 'hidden' }}">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                            <div class="relative">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       value="{{ old('password') }}"
                                       class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200">
                                <button type="button"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 password-toggle"
                                        data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password *</label>
                            <div class="relative">
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       value="{{ old('password_confirmation') }}"
                                       class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200">
                                <button type="button"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 password-toggle"
                                        data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @if($errors->has('password'))
                                <p class="text-red-500 text-sm mt-1">Konfirmasi password tidak sesuai</p>
                            @endif
                        </div>
                    </div>

                    <!-- Generated Password Display -->
                    <div id="generated_password_display" class="{{ old('password_option') == 'manual' ? 'hidden' : '' }}">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                    <span class="text-yellow-700 font-medium">Password yang dihasilkan:</span>
                                </div>
                                <button type="button"
                                        id="regenerate_password"
                                        class="bg-primary hover:bg-secondary text-white px-3 py-1 rounded text-sm flex items-center gap-1 transition duration-200">
                                    <i class="fas fa-redo-alt"></i>
                                    Generate Ulang
                                </button>
                            </div>
                            <div class="mt-2">
                                <input type="text"
                                       id="generated_password"
                                       name="generated_password"
                                       readonly
                                       class="w-full px-3 py-2 bg-white border border-yellow-300 rounded text-gray-700 font-mono text-center text-lg"
                                       value="{{ old('generated_password', $generatedPassword ?? '') }}">
                            </div>
                            <p class="text-yellow-600 text-sm mt-2 text-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Harap catat password ini! Password tidak dapat dilihat lagi setelah ini.
                            </p>
                            @error('generated_password')
                                <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.manage-users.index') }}"
                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-lg text-center transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 bg-primary hover:bg-secondary text-white py-3 px-6 rounded-lg transition duration-200">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </main>
    @include('layouts.components.admin.footer')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const manualFields = document.getElementById('manual_password_fields');
        const generatedDisplay = document.getElementById('generated_password_display');
        const passwordGenerate = document.getElementById('password_generate');
        const passwordManual = document.getElementById('password_manual');
        const generatedPasswordField = document.getElementById('generated_password');
        const regenerateBtn = document.getElementById('regenerate_password');
        const passwordField = document.getElementById('password');
        const passwordConfirmationField = document.getElementById('password_confirmation');

        // Check if there are password errors to determine initial state
        const hasPasswordErrors = {{ $errors->has('password') || $errors->has('password_confirmation') ? 'true' : 'false' }};
        const oldPasswordOption = '{{ old("password_option", "generate") }}';

        // Custom show/hide password function
        function initPasswordToggle() {
            const passwordToggles = document.querySelectorAll('.password-toggle');

            passwordToggles.forEach(toggle => {
                // Set initial state based on input type
                const targetId = toggle.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = toggle.querySelector('i');

                if (passwordInput.type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    toggle.setAttribute('title', 'Sembunyikan password');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    toggle.setAttribute('title', 'Tampilkan password');
                }

                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                        this.setAttribute('title', 'Sembunyikan password');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                        this.setAttribute('title', 'Tampilkan password');
                    }
                });
            });
        }

        function togglePasswordFields() {
            if (passwordManual.checked) {
                manualFields.classList.remove('hidden');
                generatedDisplay.classList.add('hidden');
                // Set required attribute for manual password fields
                passwordField.required = true;
                passwordConfirmationField.required = true;
                // Clear generated password requirement
                generatedPasswordField.required = false;
            } else {
                manualFields.classList.add('hidden');
                generatedDisplay.classList.remove('hidden');
                // Remove required attribute from manual fields
                passwordField.required = false;
                passwordConfirmationField.required = false;
                // Set required for generated password
                generatedPasswordField.required = true;
                // Generate password jika belum ada value
                if (!generatedPasswordField.value) {
                    generatePassword();
                }
            }
        }

        async function generatePassword() {
            try {
                const response = await fetch('{{ route("admin.manage-users.generate-password") }}');
                const data = await response.json();
                if (data.password) {
                    generatedPasswordField.value = data.password;
                }
            } catch (error) {
                console.error('Error generating password:', error);
                // Fallback to client-side generation
                generatedPasswordField.value = generateRandomPassword();
            }
        }

        function generateRandomPassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return password;
        }

        // Event listeners
        passwordGenerate.addEventListener('change', togglePasswordFields);
        passwordManual.addEventListener('change', togglePasswordFields);
        regenerateBtn.addEventListener('click', generatePassword);

        // Initial state based on old input or errors
        if (hasPasswordErrors && oldPasswordOption === 'manual') {
            passwordManual.checked = true;
        } else {
            // Use the old value or default to generate
            if (oldPasswordOption === 'manual') {
                passwordManual.checked = true;
            } else {
                passwordGenerate.checked = true;
            }
        }

        togglePasswordFields();
        initPasswordToggle();

        // Force show password fields if there are password errors
        if (hasPasswordErrors) {
            manualFields.classList.remove('hidden');
            // Ensure required attributes are set correctly
            passwordField.required = true;
            passwordConfirmationField.required = true;
        }
    });
</script>
@endsection

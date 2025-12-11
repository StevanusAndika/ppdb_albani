@extends('layouts.app')

@section('title', 'Edit User - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between max-w-6xl mx-auto">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Edit User</h1>
                <p class="text-secondary">Edit data user {{ $user->name }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">
                        <i class="fas fa-user mr-1"></i>
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                    <span class="px-3 py-1 rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-medium">
                        <i class="fas fa-circle mr-1 text-xs"></i>
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <!-- Informasi Notifikasi -->
        <div class="mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-bell text-blue-600"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-blue-900 mb-1">Notifikasi Otomatis</h3>
                        <p class="text-blue-800 text-sm mb-2">
                            Jika Anda mereset password, sistem akan otomatis mengirimkan notifikasi WhatsApp berisi password baru ke nomor telepon user.
                        </p>
                        <div class="flex items-center text-xs text-blue-700">
                            <i class="fas fa-phone mr-1"></i>
                            <span>Notifikasi akan dikirim ke: <span class="font-medium">{{ $user->phone_number }}</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            <form action="{{ route('admin.manage-users.update', $user) }}" method="POST" id="editUserForm" novalidate>
                @csrf
                @method('PUT')

                <div class="space-y-8">
                    <!-- Informasi Dasar -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                            <i class="fas fa-user-edit text-primary mr-2"></i>
                            Informasi Dasar
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-user text-primary mr-2 text-sm"></i>
                                    Nama Lengkap <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                                           required>
                                    <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-envelope text-primary mr-2 text-sm"></i>
                                    Email <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200"
                                           required>
                                    <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-user-tag text-primary mr-2 text-sm"></i>
                                    Role <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <select id="role"
                                            name="role"
                                            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200 appearance-none bg-white"
                                            required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $value => $label)
                                            <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-user-tag absolute left-3 top-3.5 text-gray-400"></i>
                                    <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400"></i>
                                </div>
                                @error('role')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Info tambahan -->
                            <div class="md:col-span-2">
                                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                                    <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-info-circle text-primary mr-2"></i>
                                        Informasi Tambahan
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Nomor Telepon</p>
                                            <p class="font-medium text-gray-800">{{ $user->phone_number }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Status</p>
                                            <p class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Dibuat</p>
                                            <p class="font-medium text-gray-800">{{ $user->created_at->translatedFormat('d F Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Password Section -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                            <i class="fas fa-key text-primary mr-2"></i>
                            Reset Password (Opsional)
                        </h2>

                        <div class="space-y-6">
                            <!-- Opsi Reset Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4">
                                    <i class="fas fa-cog text-primary mr-2 text-sm"></i>
                                    Opsi Reset Password
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Tidak Reset -->
                                    <label class="relative">
                                        <input type="radio"
                                               id="reset_password_none"
                                               name="reset_password_option"
                                               value=""
                                               class="sr-only peer"
                                               checked>
                                        <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 hover:border-gray-300 peer-checked:border-primary peer-checked:bg-primary/5">
                                            <div class="flex-shrink-0 mr-4">
                                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                    <i class="fas fa-ban text-gray-500"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Tidak Reset Password</div>
                                                <div class="text-sm text-gray-500">Password tetap seperti semula</div>
                                            </div>
                                            <div class="ml-auto">
                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-white peer-checked:block hidden"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Generate Password -->
                                    <label class="relative">
                                        <input type="radio"
                                               id="reset_password_generate"
                                               name="reset_password_option"
                                               value="generate"
                                               class="sr-only peer">
                                        <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 hover:border-primary peer-checked:border-primary peer-checked:bg-primary/5">
                                            <div class="flex-shrink-0 mr-4">
                                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                                    <i class="fas fa-random text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Generate Password Acak</div>
                                                <div class="text-sm text-gray-500">Sistem akan membuat password baru</div>
                                            </div>
                                            <div class="ml-auto">
                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-white peer-checked:block hidden"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Manual Password -->
                                    <label class="relative">
                                        <input type="radio"
                                               id="reset_password_manual"
                                               name="reset_password_option"
                                               value="manual"
                                               class="sr-only peer">
                                        <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 hover:border-primary peer-checked:border-primary peer-checked:bg-primary/5">
                                            <div class="flex-shrink-0 mr-4">
                                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Input Password Manual</div>
                                                <div class="text-sm text-gray-500">Masukkan password baru manual</div>
                                            </div>
                                            <div class="ml-auto">
                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-white peer-checked:block hidden"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('reset_password_option')
                                    <p class="text-red-500 text-sm mt-3 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Manual Password Fields -->
                            <div id="reset_manual_password_fields" class="space-y-6 hidden">
                                <!-- Password Baru -->
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-lock text-primary mr-2 text-sm"></i>
                                        Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password"
                                               id="new_password"
                                               name="new_password"
                                               class="w-full px-4 py-3 pl-10 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200 placeholder-gray-400"
                                               placeholder="Masukkan password baru (opsional jika menggunakan generate)">
                                        <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                        <button type="button"
                                                class="absolute right-3 top-3.5 text-gray-500 hover:text-gray-700 password-toggle"
                                                data-target="new_password"
                                                title="Tampilkan/Sembunyikan password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <div class="mt-2 text-xs text-gray-500 flex items-center">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Password minimal 8 karakter (biarkan kosong jika menggunakan generate)
                                    </div>
                                </div>

                                <!-- Konfirmasi Password Baru -->
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-lock text-primary mr-2 text-sm"></i>
                                        Konfirmasi Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password"
                                               id="new_password_confirmation"
                                               name="new_password_confirmation"
                                               class="w-full px-4 py-3 pl-10 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition duration-200 placeholder-gray-400"
                                               placeholder="Konfirmasi password baru">
                                        <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                        <button type="button"
                                                class="absolute right-3 top-3.5 text-gray-500 hover:text-gray-700 password-toggle"
                                                data-target="new_password_confirmation"
                                                title="Tampilkan/Sembunyikan password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Generated Password Display -->
                            <div id="reset_generated_password_display" class="hidden">
                                <div class="bg-gradient-to-r from-primary/5 to-secondary/5 border border-primary/20 rounded-xl p-6 shadow-sm">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mr-4">
                                                <i class="fas fa-shield-alt text-primary text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">Password yang Dihasilkan</h3>
                                                <p class="text-sm text-gray-600">Password baru akan dikirim ke WhatsApp user</p>
                                            </div>
                                        </div>

                                        <!-- Tombol Generate Ulang -->
                                        <button type="button"
                                                id="reset_regenerate_password"
                                                class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                                            <i class="fas fa-redo-alt"></i>
                                            Generate Ulang
                                        </button>
                                    </div>

                                    <!-- Password Display -->
                                    <div class="relative mb-4">
                                        <input type="text"
                                               id="reset_generated_password"
                                               name="generated_password"
                                               readonly
                                               class="w-full px-4 py-4 bg-white border-2 border-primary/30 rounded-lg text-gray-800 font-mono text-center text-lg tracking-wider shadow-inner"
                                               value="{{ $generatedPassword ?? '' }}">
                                        <button type="button"
                                                onclick="copyToClipboardEdit('reset_generated_password')"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-primary/10 hover:bg-primary/20 text-primary p-2 rounded-lg transition duration-200"
                                                title="Salin password">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>

                                    <!-- Checkbox Salin ke Manual -->
                                    <div class="flex justify-center mb-4">
                                        <div class="bg-white px-4 py-3 rounded-lg border border-gray-200 shadow-sm">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox"
                                                       id="reset_copy_to_manual"
                                                       name="reset_copy_to_manual"
                                                       class="mr-3 focus:ring-primary text-primary rounded h-5 w-5">
                                                <div class="flex items-center">
                                                    <i class="fas fa-copy text-primary mr-2"></i>
                                                    <span class="font-medium text-gray-700">Salin ke password manual</span>
                                                    <span class="ml-2 text-xs bg-primary/10 text-primary px-2 py-1 rounded-full">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Opsi
                                                    </span>
                                                </div>
                                            </label>
                                            <p class="text-xs text-gray-500 mt-1 ml-8">
                                                Centang untuk menyalin password ini ke form password manual
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Informasi Notifikasi -->
                                    <div class="bg-green-50 border border-green-100 rounded-lg p-4 mb-4">
                                        <div class="flex items-start">
                                            <i class="fas fa-whatsapp text-green-600 mt-0.5 mr-3 text-lg"></i>
                                            <div>
                                                <p class="text-green-800 font-medium mb-1">Notifikasi WhatsApp</p>
                                                <p class="text-green-700 text-sm">
                                                    Password baru ini akan otomatis dikirimkan ke WhatsApp user <span class="font-semibold">{{ $user->phone_number }}</span> dengan pesan "anda berhasil melakukan reset password melalui admin".
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informasi Keamanan -->
                                    <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-3"></i>
                                            <div>
                                                <p class="text-yellow-800 font-medium mb-1">Perhatian!</p>
                                                <p class="text-yellow-700 text-sm">
                                                    Pastikan user mengetahui bahwa password telah direset. Password lama tidak akan berlaku lagi setelah perubahan ini.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.manage-users.biodata.show', $user) }}"
                       class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 py-3.5 px-6 rounded-lg text-center transition duration-200 font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-id-card"></i>
                        Lihat Biodata 
                    </a>
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white py-3.5 px-6 rounded-lg transition duration-200 font-medium shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save mr-2"></i>
                        <div class="text-left">
                            <div>Update User</div>
                            <div class="text-xs font-normal opacity-90">Data akan diperbarui dan notifikasi dikirim jika reset password</div>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('layouts.components.admin.footer')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements for reset password section
        const resetPasswordNone = document.getElementById('reset_password_none');
        const resetPasswordGenerate = document.getElementById('reset_password_generate');
        const resetPasswordManual = document.getElementById('reset_password_manual');
        const resetManualFields = document.getElementById('reset_manual_password_fields');
        const resetGeneratedDisplay = document.getElementById('reset_generated_password_display');
        const resetGeneratedPasswordField = document.getElementById('reset_generated_password');
        const resetRegenerateBtn = document.getElementById('reset_regenerate_password');
        const newPasswordField = document.getElementById('new_password');
        const newPasswordConfirmationField = document.getElementById('new_password_confirmation');
        const resetCopyToManualCheckbox = document.getElementById('reset_copy_to_manual');

        // Password toggle functionality
        function initPasswordToggle() {
            const passwordToggles = document.querySelectorAll('.password-toggle');

            passwordToggles.forEach(toggle => {
                const targetId = toggle.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = toggle.querySelector('i');

                if (passwordInput && passwordInput.type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    toggle.setAttribute('title', 'Sembunyikan password');
                } else if (passwordInput) {
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

        // Copy to clipboard function for edit form
        window.copyToClipboardEdit = function(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999);

            try {
                navigator.clipboard.writeText(element.value).then(() => {
                    const originalTitle = event.target.title;
                    event.target.innerHTML = '<i class="fas fa-check"></i>';
                    event.target.title = 'Password disalin!';

                    setTimeout(() => {
                        event.target.innerHTML = '<i class="fas fa-copy"></i>';
                        event.target.title = originalTitle;
                    }, 2000);

                    showToast('Password berhasil disalin ke clipboard!', 'success');
                });
            } catch (err) {
                document.execCommand('copy');

                const originalTitle = event.target.title;
                event.target.innerHTML = '<i class="fas fa-check"></i>';
                event.target.title = 'Password disalin!';

                setTimeout(() => {
                    event.target.innerHTML = '<i class="fas fa-copy"></i>';
                    event.target.title = originalTitle;
                }, 2000);

                showToast('Password berhasil disalin ke clipboard!', 'success');
            }
        };

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Toggle reset password fields
        function toggleResetPasswordFields() {
            if (resetPasswordManual.checked) {
                resetManualFields.classList.remove('hidden');
                resetGeneratedDisplay.classList.add('hidden');

                // Set required attribute untuk manual password
                newPasswordField.required = true;
                newPasswordConfirmationField.required = true;
                resetGeneratedPasswordField.required = false;

                // Jika checkbox dicentang, salin password dari generated
                if (resetCopyToManualCheckbox.checked && resetGeneratedPasswordField.value) {
                    newPasswordField.value = resetGeneratedPasswordField.value;
                    newPasswordConfirmationField.value = resetGeneratedPasswordField.value;
                }
            } else if (resetPasswordGenerate.checked) {
                resetManualFields.classList.add('hidden');
                resetGeneratedDisplay.classList.remove('hidden');

                // Remove required attribute dari manual password
                newPasswordField.required = false;
                newPasswordConfirmationField.required = false;
                resetGeneratedPasswordField.required = true;

                // Generate password jika belum ada value
                if (!resetGeneratedPasswordField.value) {
                    generateResetPassword();
                }
            } else {
                // Tidak reset password
                resetManualFields.classList.add('hidden');
                resetGeneratedDisplay.classList.add('hidden');

                // Remove required attribute dari semua field password
                newPasswordField.required = false;
                newPasswordConfirmationField.required = false;
                resetGeneratedPasswordField.required = false;
            }
        }

        // Generate password
        async function generateResetPassword() {
            try {
                const response = await fetch('{{ route("admin.manage-users.generate-password") }}');
                const data = await response.json();
                if (data.password) {
                    resetGeneratedPasswordField.value = data.password;

                    // Jika checkbox dicentang dan manual fields ditampilkan, salin password
                    if (resetCopyToManualCheckbox.checked && resetPasswordManual.checked) {
                        newPasswordField.value = data.password;
                        newPasswordConfirmationField.value = data.password;
                    }

                    showToast('Password acak berhasil dibuat!', 'success');
                }
            } catch (error) {
                console.error('Error generating password:', error);
                resetGeneratedPasswordField.value = generateRandomPassword();

                // Jika checkbox dicentang dan manual fields ditampilkan, salin password
                if (resetCopyToManualCheckbox.checked && resetPasswordManual.checked) {
                    newPasswordField.value = resetGeneratedPasswordField.value;
                    newPasswordConfirmationField.value = resetGeneratedPasswordField.value;
                }

                showToast('Password acak dibuat secara lokal', 'info');
            }
        }

        // Generate random password (fallback)
        function generateRandomPassword() {
            const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const lowercase = 'abcdefghijklmnopqrstuvwxyz';
            const numbers = '0123456789';
            const symbols = '!@#$%^&*';
            const all = uppercase + lowercase + numbers + symbols;

            // Pastikan minimal satu karakter dari setiap jenis
            let password = uppercase[Math.floor(Math.random() * uppercase.length)]
                        + lowercase[Math.floor(Math.random() * lowercase.length)]
                        + numbers[Math.floor(Math.random() * numbers.length)]
                        + symbols[Math.floor(Math.random() * symbols.length)];

            // Tambahkan karakter random hingga mencapai panjang 12
            for (let i = password.length; i < 12; i++) {
                password += all[Math.floor(Math.random() * all.length)];
            }

            // Acak urutan password
            return password.split('').sort(() => Math.random() - 0.5).join('');
        }

        // Event listeners for reset password
        resetPasswordNone.addEventListener('change', toggleResetPasswordFields);
        resetPasswordGenerate.addEventListener('change', toggleResetPasswordFields);
        resetPasswordManual.addEventListener('change', toggleResetPasswordFields);
        resetRegenerateBtn.addEventListener('click', generateResetPassword);

        // Handle copy to manual checkbox
        if (resetCopyToManualCheckbox) {
            resetCopyToManualCheckbox.addEventListener('change', function() {
                if (this.checked && resetGeneratedPasswordField.value) {
                    // Jika manual fields ditampilkan, salin password
                    if (resetPasswordManual.checked) {
                        newPasswordField.value = resetGeneratedPasswordField.value;
                        newPasswordConfirmationField.value = resetGeneratedPasswordField.value;
                    }
                } else if (!this.checked) {
                    // Kosongkan manual fields jika checkbox tidak dicentang
                    if (resetPasswordManual.checked) {
                        newPasswordField.value = '';
                        newPasswordConfirmationField.value = '';
                    }
                }
            });
        }

        // Initialize
        toggleResetPasswordFields();
        initPasswordToggle();

        // Handle form validation
        const editUserForm = document.getElementById('editUserForm');
        if (editUserForm) {
            editUserForm.addEventListener('submit', function(e) {
                // Reset semua required fields dulu
                newPasswordField.required = false;
                newPasswordConfirmationField.required = false;
                resetGeneratedPasswordField.required = false;

                // Set required berdasarkan opsi yang dipilih
                if (resetPasswordManual.checked) {
                    newPasswordField.required = true;
                    newPasswordConfirmationField.required = true;
                } else if (resetPasswordGenerate.checked) {
                    resetGeneratedPasswordField.required = true;
                }

                // Validasi untuk manual password
                if (resetPasswordManual.checked) {
                    // Jika password manual diisi, validasi konfirmasi
                    if (newPasswordField.value && newPasswordField.value !== newPasswordConfirmationField.value) {
                        e.preventDefault();
                        showToast('Konfirmasi password tidak sesuai dengan password baru!', 'error');
                        return false;
                    }

                    // Validasi panjang password manual
                    if (newPasswordField.value && newPasswordField.value.length < 8) {
                        e.preventDefault();
                        newPasswordField.classList.add('border-red-500');
                        showToast('Password manual harus minimal 8 karakter!', 'error');
                        return false;
                    }
                }

                // Validasi untuk generated password
                if (resetPasswordGenerate.checked && resetGeneratedPasswordField.value.length < 8) {
                    e.preventDefault();
                    resetGeneratedPasswordField.classList.add('border-red-500');
                    showToast('Password generate harus minimal 8 karakter!', 'error');
                    return false;
                }

                // Show loading state
                const submitBtn = editUserForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                if (resetPasswordManual.checked || resetPasswordGenerate.checked) {
                    submitBtn.innerHTML = `
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        <div class="text-left">
                            <div>Memperbarui...</div>
                            <div class="text-xs font-normal opacity-90">Sedang reset password dan mengirim notifikasi</div>
                        </div>
                    `;
                } else {
                    submitBtn.innerHTML = `
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        <div class="text-left">
                            <div>Memperbarui...</div>
                            <div class="text-xs font-normal opacity-90">Sedang memperbarui data user</div>
                        </div>
                    `;
                }

                submitBtn.disabled = true;

                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
        }
    });
</script>

<style>
    select::-ms-expand {
        display: none;
    }

    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.7rem center;
        background-size: 1em;
    }

    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    input[type="checkbox"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #d1d5db;
        border-radius: 0.375rem;
        background-color: white;
        position: relative;
        cursor: pointer;
        transition: all 0.2s;
    }

    input[type="checkbox"]:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    input[type="checkbox"]:checked::after {
        content: "✓";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.875rem;
        font-weight: bold;
    }

    input[type="checkbox"]:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    input[type="radio"]:checked + div {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.05);
    }

    .password-toggle:hover {
        transform: scale(1.1);
    }

    #reset_generated_password {
        letter-spacing: 0.1em;
        font-weight: 600;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    @media (max-width: 640px) {
        .grid-cols-3, .grid-cols-2 {
            grid-template-columns: 1fr;
        }

        #reset_generated_password {
            font-size: 0.875rem;
            padding: 0.75rem;
        }
    }
</style>
@endsection

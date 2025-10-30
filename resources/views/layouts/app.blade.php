<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB - Pondok Pesantren Bani Syahid')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('styles')
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%); font-family: 'Poppins', sans-serif;">
    @yield('content')

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert for notifications - Hanya tampilkan di popup, sembunyikan error di form
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                background: '#f0fdf4',
                color: '#166534'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 4000,
                showConfirmButton: true,
                background: '#fef2f2',
                color: '#dc2626'
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: '{{ session('info') }}',
                timer: 4000,
                showConfirmButton: true,
                background: '#eff6ff',
                color: '#1e40af'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                timer: 5000,
                showConfirmButton: true,
                background: '#fef2f2',
                color: '#dc2626'
            });
        @endif

        // Global password toggle functionality
        function initPasswordToggle() {
            const passwordToggles = document.querySelectorAll('.password-toggle-btn');

            passwordToggles.forEach(btn => {
                // Set initial title
                btn.setAttribute('title', 'Tampilkan password');
                btn.setAttribute('aria-label', 'Tampilkan password');

                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                        this.setAttribute('title', 'Sembunyikan password');
                        this.setAttribute('aria-label', 'Sembunyikan password');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                        this.setAttribute('title', 'Tampilkan password');
                        this.setAttribute('aria-label', 'Tampilkan password');
                    }

                    // Focus kembali ke input setelah toggle
                    passwordInput.focus();
                });
            });
        }

        // Initialize when document is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initPasswordToggle();
        });
    </script>

    @yield('scripts')
</body>
</html>

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

    <!-- Load CSS langsung di layout -->
    <style>
        /* Auth Styles */
        .auth-container {
            box-shadow: 0 15px 35px rgba(5, 117, 114, 0.3);
            background: linear-gradient(145deg, #057572, #04615f);
        }

        .btn-primary {
            background: #002F2D;
            color: white;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #001f1e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 47, 45, 0.4);
        }

        .btn-google {
            background: #002F2D;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-google:hover {
            background: #001f1e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 47, 45, 0.4);
        }

        .google-icon {
            background: conic-gradient(from -45deg, #ea4335 110deg, #4285f4 90deg 180deg, #34a853 180deg 270deg, #fbbc05 270deg) 73% 55%/150% 150% no-repeat;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
            border-color: white;
            background: white;
        }

        .text-white-90 {
            color: rgba(255, 255, 255, 0.9);
        }

        .divider {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .logo-container {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #ffffff, #e0f2f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .password-toggle {
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #002F2D;
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 4px;
            z-index: 10;
        }
    </style>

    @yield('styles')
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%); font-family: 'Poppins', sans-serif;">
    @yield('content')

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JavaScript untuk toggle password -->
    <script>
        // Password toggle functionality
        function initPasswordToggle() {
            const passwordToggles = document.querySelectorAll('.password-toggle');

            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const passwordInput = this.closest('.password-input-wrapper').querySelector('input');
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

        // SweetAlert for notifications
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

        // Initialize when document is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initPasswordToggle();
        });
    </script>

    @yield('scripts')
</body>
</html>

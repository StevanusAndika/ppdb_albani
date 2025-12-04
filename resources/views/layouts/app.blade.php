<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB - Pondok Pesantren Bani Syahid')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        /* Make custom color names available project-wide so views like
           welcome.blade.php and dashboard use the same 'primary'/'secondary' colors */
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#057572',
                        'secondary': '#5B5B5B',
                        'accent': '#9D9D9D',
                        'white': '#FFFFFF'
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">



    @yield('styles')
</head>
<body class="bg-gray-50 font-sans" style="font-family: 'Poppins', sans-serif;">
    @yield('content')

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert for notifications - Hanya tampilkan di popup saja dengan tombol OK
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981',
                background: '#f0fdf4',
                color: '#166534',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444',
                background: '#fef2f2',
                color: '#dc2626',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: '{{ session('info') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6',
                background: '#eff6ff',
                color: '#1e40af',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444',
                background: '#fef2f2',
                color: '#dc2626',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        @endif

    </script>

    @yield('scripts')
</body>
</html>

<nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-lg md:text-xl font-bold text-primary nav-logo">Ponpes Al Bani</div>

        <div class="hidden md:flex space-x-6 items-center desktop-menu">
            @php
                $currentRoute = Route::currentRouteName();
            @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="text-primary hover:text-secondary font-medium {{ $currentRoute == 'admin.dashboard' ? 'active-nav-link' : '' }}">
                Beranda
            </a>

            <a href="{{ route('admin.settings.index') }}?tab=profile"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'admin.settings') ? 'active-nav-link' : '' }}">
                Profil
            </a>

            <a href="{{ route('admin.registrations.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'admin.registrations') ? 'active-nav-link' : '' }}">
                Pendaftaran
            </a>

            <a href="{{ route('admin.transactions.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'admin.transactions') ? 'active-nav-link' : '' }}">
                Pembayaran
            </a>
           <!-- LINK PENGUMUMAN SELEKSI BARU -->
            <a href="{{ route('admin.seleksi-announcements.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'admin.seleksi-announcements') ? 'active-nav-link' : '' }}">
                Pengumuman Seleksi
            </a>

            <!-- LINK PENGUMUMAN -->
            <a href="{{ route('admin.announcements.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'admin.announcements') ? 'active-nav-link' : '' }}">
                Pengumuman Kelulusan
            </a>


            <!-- Ubah form logout menjadi button dengan onclick event -->
            <button type="button" onclick="confirmLogout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300 cursor-pointer">
                Logout
            </button>
        </div>

        <div class="md:hidden flex items-center">
            <button id="mobile-menu-button" class="text-primary focus:outline-none mobile-menu-button">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden mt-2 bg-white p-4 rounded-xl shadow-lg">
        <div class="flex flex-col space-y-2">
            @php
                $currentRoute = Route::currentRouteName();
            @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="text-primary {{ $currentRoute == 'admin.dashboard' ? 'mobile-active-nav-link' : '' }}">
                Beranda
            </a>

            <a href="{{ route('admin.settings.index') }}?tab=profile"
               class="text-primary {{ str_starts_with($currentRoute, 'admin.settings') ? 'mobile-active-nav-link' : '' }}">
                Profil
            </a>

            <a href="{{ route('admin.registrations.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'admin.registrations') ? 'mobile-active-nav-link' : '' }}">
                Pendaftaran
            </a>

            <a href="{{ route('admin.transactions.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'admin.transactions') ? 'mobile-active-nav-link' : '' }}">
                Pembayaran
            </a>



            <!-- LINK PENGUMUMAN SELEKSI BARU MOBILE -->
            <a href="{{ route('admin.seleksi-announcements.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'admin.seleksi-announcements') ? 'mobile-active-nav-link' : '' }}">
                Pengumuman Seleksi
            </a>
              <!-- LINK PENGUMUMAN MOBILE -->
            <a href="{{ route('admin.announcements.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'admin.announcements') ? 'mobile-active-nav-link' : '' }}">
                Pengumuman Kelulusan
            </a>

            <!-- Ubah form logout mobile menjadi button dengan onclick event -->
            <button type="button" onclick="confirmLogout()" class="w-full bg-red-500 text-white py-2 rounded-full mt-2 cursor-pointer">
                Logout
            </button>
        </div>
    </div>
</nav>

<style>
    /* Style untuk garis hijau aktif di desktop */
    .active-nav-link {
        position: relative;
        font-weight: 600;
    }

    .active-nav-link::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #057572;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    /* Style untuk garis hijau aktif di mobile */
    .mobile-active-nav-link {
        position: relative;
        padding-left: 15px;
        font-weight: 600;
    }

    .mobile-active-nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 20px;
        background-color: #057572;
        border-radius: 2px;
    }

    /* Hover effect untuk link aktif */
    .active-nav-link:hover::after {
        transform: scaleX(1.1);
    }

    /* Untuk membedakan antara "Pengumuman" dan "Pengumuman Seleksi" */
    .desktop-menu a {
        white-space: nowrap;
    }

    #mobile-menu a {
        white-space: nowrap;
    }
</style>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Untuk menangani kasus khusus URL dengan query parameter
        // Jika halaman settings dengan tab=profile diakses
        const currentUrl = window.location.href;
        if (currentUrl.includes('admin.settings') && currentUrl.includes('tab=profile')) {
            // Pastikan link settings mendapatkan kelas aktif
            const settingsLinks = document.querySelectorAll('a[href*="admin.settings"]');
            settingsLinks.forEach(link => {
                if (!link.classList.contains('active-nav-link') && !link.classList.contains('mobile-active-nav-link')) {
                    if (link.closest('.desktop-menu')) {
                        link.classList.add('active-nav-link');
                    } else if (link.closest('#mobile-menu')) {
                        link.classList.add('mobile-active-nav-link');
                    }
                }
            });
        }
    });

    // Confirm logout function
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar dari akun?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            allowOutsideClick: false,
            allowEscapeKey: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Buat form logout secara dinamis
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';

                // Tambahkan CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Tambahkan form ke body dan submit
                document.body.appendChild(form);
                form.submit();
            } else {
                // Jika user memilih batal, tutup sweetalert
                Swal.close();
            }
        });
    }
</script>

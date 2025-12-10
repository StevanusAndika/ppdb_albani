<nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50 nav-container">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-lg font-bold text-primary nav-logo"><img src="{{ asset('image/SantriFlow_logo.png') }}" alt="SantriFlow Logo" class="inline h-8 w-auto"><div class="inline md:hidden lg:inline">|| Ponpes Al Bani</div></div>

        <div class="hidden md:inline space-x-6 items-center desktop-menu">
            @php
                $currentRoute = Route::currentRouteName();
            @endphp

            <a href="{{ route('santri.dashboard') }}"
               class="text-primary hover:text-secondary font-medium {{ $currentRoute == 'santri.dashboard' ? 'active-nav-link' : '' }}">
                Beranda
            </a>

            <a href="{{ route('santri.settings.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'santri.settings') ? 'active-nav-link' : '' }}">
                Pengaturan
            </a>

            <a href="{{ route('santri.biodata.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'santri.biodata') ? 'active-nav-link' : '' }}">
                Pendaftaran
            </a>

            <a href="{{ route('santri.documents.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'santri.documents') ? 'active-nav-link' : '' }}">
                Dokumen
            </a>

            <a href="{{ route('santri.payments.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'santri.payments') ? 'active-nav-link' : '' }}">
                Pembayaran
            </a>

            <a href="{{ route('santri.faq.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'santri.faq') ? 'active-nav-link' : '' }}">
                FAQ
            </a>

            <a href="{{ route('santri.kegiatan.index') }}"
               class="text-primary hover:text-secondary font-medium {{ str_starts_with($currentRoute, 'santri.kegiatan') ? 'active-nav-link' : '' }}">
                Kegiatan
            </a>

            <!-- Tombol Logout -->
            <button type="button" onclick="confirmLogout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300 cursor-pointer ml-4">
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

            <a href="{{ route('santri.dashboard') }}"
               class="text-primary {{ $currentRoute == 'santri.dashboard' ? 'mobile-active-nav-link' : '' }}">
                Beranda
            </a>

            <a href="{{ route('santri.settings.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'santri.settings') ? 'mobile-active-nav-link' : '' }}">
                Pengaturan
            </a>

            <a href="{{ route('santri.biodata.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'santri.biodata') ? 'mobile-active-nav-link' : '' }}">
                Pendaftaran
            </a>

            <a href="{{ route('santri.documents.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'santri.documents') ? 'mobile-active-nav-link' : '' }}">
                Dokumen
            </a>

            <a href="{{ route('santri.payments.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'santri.payments') ? 'mobile-active-nav-link' : '' }}">
                Pembayaran
            </a>

            <a href="{{ route('santri.faq.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'santri.faq') ? 'mobile-active-nav-link' : '' }}">
                FAQ
            </a>

            <a href="{{ route('santri.kegiatan.index') }}"
               class="text-primary {{ str_starts_with($currentRoute, 'santri.kegiatan') ? 'mobile-active-nav-link' : '' }}">
                Kegiatan
            </a>

            <!-- Tombol Logout Mobile -->
            <button type="button" onclick="confirmLogout()" class="w-full bg-red-500 text-white py-2 rounded-full mt-2 cursor-pointer">
                Logout
            </button>
        </div>
    </div>
</nav>

<style>
    /* Tambahkan style ini jika tidak ada file CSS terpisah */
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
    });

    // Confirm logout function dengan tombol Ya di kiri dan Batal di kanan
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar dari akun?',
            icon: 'question',
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
                Swal.close();
            }
        });
    }
</script>

<script>
// Password toggle functionality dengan namespace khusus untuk auth pages
function initAuthPasswordToggle() {
    const passwordToggles = document.querySelectorAll('.password-toggle-btn');

    passwordToggles.forEach(toggle => {
        // Skip jika sudah ada event listener dari script global
        if (toggle.dataset.authToggleInitialized === 'true') {
            return;
        }

        toggle.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            let passwordInput;

            if (target) {
                // Jika menggunakan data-target attribute
                passwordInput = document.getElementById(target);
            } else {
                // Fallback: cari input dalam wrapper terdekat
                const wrapper = this.closest('.password-input-wrapper');
                if (wrapper) {
                    passwordInput = wrapper.querySelector('input[type="password"], input[type="text"]');
                }
            }

            if (!passwordInput) {
                console.warn('Password input not found for auth toggle');
                return;
            }

            const icon = this.querySelector('i');

            // Toggle password visibility
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
                // Set attribute untuk tracking
                this.setAttribute('data-password-visible', 'true');
            } else {
                passwordInput.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
                // Set attribute untuk tracking
                this.setAttribute('data-password-visible', 'false');
            }

            // Focus kembali ke input setelah toggle
            setTimeout(() => {
                passwordInput.focus();
            }, 10);
        });

        // Mark sebagai sudah di-initialize oleh auth script
        toggle.dataset.authToggleInitialized = 'true';
    });
}

// Initialize hanya untuk auth pages
document.addEventListener('DOMContentLoaded', function() {
    // Cek jika di halaman auth (login, register, forgot-password)
    const isAuthPage = window.location.pathname.includes('/login') ||
                       window.location.pathname.includes('/register') ||
                       window.location.pathname.includes('/password');

    if (isAuthPage) {
        initAuthPasswordToggle();

        // Juga initialize untuk dynamic content (jika ada)
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('element.updated', () => {
                setTimeout(initAuthPasswordToggle, 100);
            });
        }
    }
});

// Function untuk disable toggle global jika ada
function disableGlobalPasswordToggle() {
    if (typeof initPasswordToggle === 'function') {
        // Override function global dengan yang kosong
        window.initPasswordToggle = function() {
            console.log('Global password toggle disabled for auth pages');
        };
    }
}

// Panggil untuk disable global toggle di auth pages
document.addEventListener('DOMContentLoaded', function() {
    const isAuthPage = window.location.pathname.includes('/login') ||
                       window.location.pathname.includes('/register') ||
                       window.location.pathname.includes('/password');

    if (isAuthPage) {
        disableGlobalPasswordToggle();
    }
});
</script>

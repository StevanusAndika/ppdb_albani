<script>
// Validasi nomor telepon hanya angka
function validatePhoneNumber(input) {
    // Hapus semua karakter non-digit
    const originalValue = input.value;
    input.value = input.value.replace(/[^0-9]/g, '');

    // Batasi panjang maksimal 15 digit
    if (input.value.length > 15) {
        input.value = input.value.substring(0, 15);
    }

    // Tampilkan pesan error jika diperlukan
    const errorElement = document.getElementById('phone_error');
    if (errorElement) {
        if (input.value.length < 10 && input.value.length > 0) {
            errorElement.textContent = 'Nomor telepon minimal 10 digit';
            errorElement.classList.remove('hidden');
        } else if (input.value.length === 0) {
            errorElement.textContent = 'Nomor telepon wajib diisi';
            errorElement.classList.remove('hidden');
        } else {
            errorElement.classList.add('hidden');
        }
    }
}

// Prevent paste non-numeric characters
document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');

    phoneInputs.forEach(input => {
        input.addEventListener('paste', function(e) {
            e.preventDefault();

            // Get pasted data
            const pastedData = e.clipboardData.getData('text');

            // Filter hanya angka
            const numbersOnly = pastedData.replace(/[^0-9]/g, '');

            // Insert filtered data
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const currentValue = input.value;

            input.value = currentValue.substring(0, start) + numbersOnly + currentValue.substring(end);

            // Set cursor position
            input.setSelectionRange(start + numbersOnly.length, start + numbersOnly.length);

            // Trigger validation
            validatePhoneNumber(input);
        });

        // Validasi saat form submit
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const phoneValue = input.value.trim();
                if (phoneValue.length === 0) {
                    e.preventDefault();
                    const errorElement = document.getElementById('phone_error');
                    if (errorElement) {
                        errorElement.textContent = 'Nomor telepon wajib diisi';
                        errorElement.classList.remove('hidden');
                    }
                    input.focus();
                } else if (phoneValue.length < 10) {
                    e.preventDefault();
                    const errorElement = document.getElementById('phone_error');
                    if (errorElement) {
                        errorElement.textContent = 'Nomor telepon minimal 10 digit';
                        errorElement.classList.remove('hidden');
                    }
                    input.focus();
                }
            });
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resendBtn = document.getElementById('resend-otp-btn');
    const resendText = document.getElementById('resend-text');
    const countdownText = document.getElementById('countdown');
    const email = document.querySelector('input[name="email"]').value;

    if (!resendBtn) return;

    let cooldown = 60; // 1 menit cooldown
    let timer = null;

    // Check cooldown on page load
    checkCooldown();

    function checkCooldown() {
        fetch('/check-password-cooldown', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.on_cooldown) {
                startCooldown(data.remaining_time);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function startCooldown(seconds) {
        cooldown = seconds;
        resendBtn.disabled = true;
        countdownText.classList.remove('hidden');
        resendText.classList.add('hidden');

        timer = setInterval(() => {
            cooldown--;

            const minutes = Math.floor(cooldown / 60);
            const secs = cooldown % 60;
            countdownText.textContent = `${minutes}:${secs.toString().padStart(2, '0')}`;

            if (cooldown <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                countdownText.classList.add('hidden');
                resendText.classList.remove('hidden');
            }
        }, 1000);
    }

    resendBtn.addEventListener('click', function() {
        if (resendBtn.disabled) return;

        fetch('/resend-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('OTP berhasil dikirim ulang!');
                startCooldown(60); // Start 1 minute cooldown
            } else {
                alert('Gagal mengirim ulang OTP: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim ulang OTP');
        });
    });
});
</script>

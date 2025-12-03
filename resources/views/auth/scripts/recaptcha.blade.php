@if($recaptcha_enabled)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Login Form reCAPTCHA
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $recaptcha_site_key }}', {action: 'login'}).then(function(token) {
                    const recaptchaInput = document.createElement('input');
                    recaptchaInput.type = 'hidden';
                    recaptchaInput.name = 'g-recaptcha-response';
                    recaptchaInput.value = token;
                    loginForm.appendChild(recaptchaInput);

                    loginForm.submit();
                });
            });
        });
    }

    // Manual Register Form reCAPTCHA
    const manualForm = document.getElementById('manualRegisterForm');
    if (manualForm) {
        manualForm.addEventListener('submit', function(e) {
            e.preventDefault();

            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $recaptcha_site_key }}', {action: 'register'}).then(function(token) {
                    const recaptchaInput = document.createElement('input');
                    recaptchaInput.type = 'hidden';
                    recaptchaInput.name = 'g-recaptcha-response';
                    recaptchaInput.value = token;
                    manualForm.appendChild(recaptchaInput);

                    manualForm.submit();
                });
            });
        });
    }

    // Socialite Register Form reCAPTCHA
    const socialiteForm = document.getElementById('socialiteRegisterForm');
    if (socialiteForm) {
        socialiteForm.addEventListener('submit', function(e) {
            e.preventDefault();

            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $recaptcha_site_key }}', {action: 'register'}).then(function(token) {
                    const recaptchaInput = document.createElement('input');
                    recaptchaInput.type = 'hidden';
                    recaptchaInput.name = 'g-recaptcha-response';
                    recaptchaInput.value = token;
                    socialiteForm.appendChild(recaptchaInput);

                    socialiteForm.submit();
                });
            });
        });
    }

    // Forgot Password Form reCAPTCHA
    const forgotForm = document.getElementById('forgotPasswordForm');
    if (forgotForm) {
        forgotForm.addEventListener('submit', function(e) {
            e.preventDefault();

            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $recaptcha_site_key }}', {action: 'reset_password'}).then(function(token) {
                    const recaptchaInput = document.createElement('input');
                    recaptchaInput.type = 'hidden';
                    recaptchaInput.name = 'g-recaptcha-response';
                    recaptchaInput.value = token;
                    forgotForm.appendChild(recaptchaInput);

                    forgotForm.submit();
                });
            });
        });
    }
});
</script>
@endif

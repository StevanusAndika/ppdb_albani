<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('new_password_confirmation');
    const resetButton = document.getElementById('reset-button');
    const strengthBars = document.querySelectorAll('.strength-bar');
    const requirements = document.querySelectorAll('.requirement');
    const passwordMatchText = document.getElementById('password-match-text');
    const passwordMismatchText = document.getElementById('password-mismatch-text');

    if (!passwordInput) return;

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;

        // Check requirements
        const hasLength = password.length >= 8;
        const hasLower = /[a-z]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSymbol = /[@$!%*?&]/.test(password);

        // Update requirement icons
        updateRequirement('length', hasLength);
        updateRequirement('lowercase', hasLower);
        updateRequirement('uppercase', hasUpper);
        updateRequirement('number', hasNumber);
        updateRequirement('symbol', hasSymbol);

        // Calculate strength
        if (hasLength) strength++;
        if (hasLower) strength++;
        if (hasUpper) strength++;
        if (hasNumber) strength++;
        if (hasSymbol) strength++;

        // Update strength bars
        updateStrengthBars(strength);

        return strength >= 5; // All requirements met
    }

    function updateRequirement(type, isValid) {
        const requirement = document.querySelector(`[data-requirement="${type}"] i`);
        if (requirement) {
            requirement.className = isValid ? 'fas fa-check text-green-400 mr-2 text-xs' : 'fas fa-times text-red-400 mr-2 text-xs';
        }
    }

    function updateStrengthBars(strength) {
        strengthBars.forEach((bar, index) => {
            if (index < strength) {
                bar.style.backgroundColor = getStrengthColor(strength);
            } else {
                bar.style.backgroundColor = '#d1d5db';
            }
        });

        // Update strength text
        const strengthText = document.getElementById('password-strength-text');
        const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
        const texts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat'];
        strengthText.textContent = `Kekuatan password: ${texts[strength - 1] || 'Sangat Lemah'}`;
        strengthText.style.color = colors[strength - 1] || '#ef4444';
    }

    function getStrengthColor(strength) {
        const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
        return colors[strength - 1] || '#ef4444';
    }

    function checkPasswordsMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword.length === 0) {
            passwordMatchText.classList.add('hidden');
            passwordMismatchText.classList.add('hidden');
            return false;
        }

        if (password === confirmPassword) {
            passwordMatchText.classList.remove('hidden');
            passwordMismatchText.classList.add('hidden');
            return true;
        } else {
            passwordMatchText.classList.add('hidden');
            passwordMismatchText.classList.remove('hidden');
            return false;
        }
    }

    function updateResetButton() {
        const isStrong = checkPasswordStrength(passwordInput.value);
        const isMatch = checkPasswordsMatch();

        resetButton.disabled = !(isStrong && isMatch);
    }

    // Event listeners
    passwordInput.addEventListener('input', updateResetButton);
    confirmPasswordInput.addEventListener('input', updateResetButton);

    // Initial check
    updateResetButton();
});
</script>

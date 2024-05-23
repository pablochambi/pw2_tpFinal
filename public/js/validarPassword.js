document.getElementById('registroForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const passwordError = document.getElementById('passwordError');

    if (password !== confirmPassword) {
        event.preventDefault();
        passwordError.textContent = "Las contraseñas no coinciden.";
    } else {
        passwordError.textContent = "";
    }
});
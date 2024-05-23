function validarPassword() {
    var password = document.getElementById('password').value;
    var confirm_password = document.getElementById('confirm_password').value;
    var passwordError = document.getElementById('passwordError');

    if (password !== confirm_password) {
        passwordError.textContent = 'Las contraseñas no coinciden';
        return false;
    } else {
        passwordError.textContent = '';
        return true;
    }
}

// Agregar un event listener al formulario para llamar a la función de
// validación antes de enviar el formulario

var registroForm = document.getElementById('registroForm')

registroForm.addEventListener('submit', function(event) {
    if (!validarPassword()) {
        event.preventDefault(); // Evitar que el formulario se envíe si las contraseñas no coinciden
    }
});
document.addEventListener("DOMContentLoaded", function() {
    var rolesPermitidos = ["Administrador", "Editor", "Jugador"]; // Define los roles permitidos
    var rolUsuario = "{{rol}}"; // Obtén el rol del usuario desde Mustache
    var elementosRol = document.querySelectorAll("[data-rol]");

    elementosRol.forEach(function(elemento) {
        var rolesElemento = elemento.getAttribute("data-rol").split(" ");
        if (rolesElemento.some(r => rolesPermitidos.includes(r)) && rolesPermitidos.includes(rolUsuario)) {
            elemento.style.display = "inline-block"; // Mostrar elemento si el rol del usuario está permitido
        } else {
            elemento.style.display = "none";
        }
        console.log("Rol del usuario:", rolUsuario);
    });
});
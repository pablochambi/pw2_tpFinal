document.addEventListener('DOMContentLoaded', function () {
    const usarTrampaBoton = document.getElementById('usarTrampa');

    usarTrampaBoton.addEventListener('click', function () {
        fetch('/partida/usarTrampa', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
            .then(response => {
                // Verificar si la respuesta es exitosa (status 200-299)
                if (!response.ok) {
                    throw new Error('Error en la petición para usar la trampa: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Trampa utilizada correctamente');

                    // Ocultar las respuestas incorrectas según lo devuelto por el servidor
                    data.respuestasIncorrectas.forEach(respuesta => {
                        const respuestaIncorrectaElement = document.querySelector(`button[data-respuesta="${respuesta}"]`);
                        if (respuestaIncorrectaElement) {
                            respuestaIncorrectaElement.style.display = 'none';
                        }
                    });

                    // Actualizar la cantidad de trampas disponibles si es necesario
                    const trampasDisponiblesElement = document.getElementById('trampasDisponibles');
                    if (trampasDisponiblesElement) {
                        trampasDisponiblesElement.textContent = `Trampas disponibles: ${data.trampitas}`;
                    }

                } else {
                    console.error('Error al usar la trampa:', data.message);
                }
            })
            .catch(error => {
                console.error('Error en la petición para usar la trampa:', error);
            });
    });
});




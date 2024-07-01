let timeLeft = 10;
let timer = document.getElementById('timer'); // aca es donde se muestra el tiempo restante
let timeExpiredInput = document.getElementById('time_expired'); // se utiliza para indicar si el tiempo se expiró
let form = document.getElementById('respPregunta');
const mensaje = document.querySelector('.mensaje');

let countdown = setInterval(function () { // se utiliza para ejecutar una funcion cada segundo
    if (timeLeft <= 0) { // si timeLeft es 0 o menos entonces se detiene el temporizador y establace el valor del campo ocuilto
        clearInterval(countdown);
        timeExpiredInput.value = "1";
        form.submit(); // lo mando al back
    } else { // si es mayor a cero actualiza el contenido del elemento timer para mostrar el tiempo restante (CREO)
        timer.innerHTML = timeLeft;
    }
    timeLeft -= 1;
}, 1000);

function checkAnswer(button) {
    //Obtener todos los botones de respuesta
    const buttons = document.querySelectorAll('.answer button');

    // Marque el botón hecho clic
    if (button.dataset.correct === "0") {
        button.classList.add('rojoImportant');

        mensaje.classList.remove("displayNone");
        mensaje.textContent = "Incorrecto";
        mensaje.style.color = "red";
    }else{
        button.classList.add('verdeImportat');
        mensaje.classList.remove("displayNone");
        mensaje.textContent = "Correcto";
        mensaje.style.color = "green";
    }

    // Marque todas las respuestas y resalte la correcta.
    buttons.forEach(btn => {
        if (btn.dataset.correct === "1") {
            btn.classList.add('verdeImportat');
        }
    });



    setTimeout(function() {
        button.closest('form').submit();
    }, 5000);

}

var music = document.getElementById('background-music');
var playButton = document.getElementById('play-music');
var pauseButton = document.getElementById('pause-music');
document.addEventListener('DOMContentLoaded', function () {
    // la musica empieza automaticamente cuando carga la pagina
    music.play();

    playButton.addEventListener('click', function() {
        music.play();
    });

    pauseButton.addEventListener('click', function() {
        music.pause();
    });
});

 /* document.addEventListener('DOMContentLoaded', function () {
    const usarTrampaBoton = document.getElementById('usarTrampa');

    usarTrampaBoton.addEventListener('click', function () {
        fetch('/partida/usarTrampa', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Trampa utilizada correctamente');

                    data.respuestasIncorrectas.forEach(respuesta => {
                        const respuestaIncorrectaElement = document.querySelector(`button[data-respuesta="${respuesta}"]`);
                        if (respuestaIncorrectaElement) {
                            respuestaIncorrectaElement.style.display = 'none'; // Oculta la respuesta incorrecta
                        }
                    });


                } else {
                    console.error('Error al usar la trampa:', data.message);
                }
            })
            .catch(error => {
                console.error('Error en la petición para usar la trampa:', error);
            });
    });

    })  */


<?php

class PartidaController extends BaseController
{
    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        $this->checkSession();

        if (isset($_POST['id_usuario'])) {
            $id_usuario = $_POST['id_usuario'];

            $this->model->arrancarPartida($id_usuario);

            $rol = $this->verificarDeQueRolEsElUsuario($_POST['id_usuario']);

            $pregunta = $this->model->traerPreguntaAleatoria();
            $this->model->updateDatosPregunta($pregunta[0]['id']);

            $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);

            $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta, 'respuestas' => $respuestas, "rol" => $rol['rol']]);
        }else{
            echo "No recibio ningun dato a partidaController";
        }


    }

    public function procesarRespuesta()
    {
        $this->checkSession();

        $this->manejoDeElProcesoDeRespuesta();
    }

    private function handleTimeExpired()
    {
        echo "El tiempo ha expirado. Has perdido la pregunta."; // ESTO DEBERIA SER UNA VISTA o controlarlo como mensaje pop up como mensajeValidacion.mustache
    }

    public function siguientePregunta()
    {
        $this->checkSession();

        $pregunta = $this->model->traerPreguntaAleatoria();
        $this->model->updateDatosPregunta($pregunta[0]['id']);
        $this->traerRespuestasDespuesSiguiente($pregunta);
    }

    public function continuar()
    {
        $this->checkSession();

        if (isset($_POST['valor_respuesta']) && isset($_POST['id_pregunta'])) {
            $continuar = $_POST['valor_respuesta'];
            $id_pregunta = $_POST['id_pregunta'];
            $this->manejoDeRespuesta($continuar, $id_pregunta);
        }
    }


    private function respuestaCorrectaPath($id_pregunta)
    {
        if (isset($_SESSION['username'])) {
            $user = $_SESSION['username'];
            $this->model->updatePregBienRespondidas($id_pregunta);
            $partida = $this->model->obtenerUltimaPartida($user['id']);
            $this->model->sumarPuntos($user['id'], $partida);
        }
    }


    private function manejoDeRespuesta($continuar, $id_pregunta)
    {
        if ($continuar == "Incorrecta") {
            $user = $_SESSION['username'];
            $puntaje = $this->model->obtenerCantidadDePuntos($user['id']);
            $this->presenter->render("view/mostrarPuntajeDespuesPerder.mustache", ['puntaje' => $puntaje]);
        } else {
            $this->respuestaCorrectaPath($id_pregunta);
            header("Location: /partida/siguientePregunta");
        }
    }


    private function traerRespuestasDespuesSiguiente($pregunta)
    {
        if (isset($pregunta[0]['id'])) {
            $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);
            $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta[0], 'respuestas' => $respuestas]);
        } else {
            echo "No se encontró ninguna pregunta.";
        }
    }


    private function manejoDeElProcesoDeRespuesta()
    {
        if (isset($_POST['time_expired']) && $_POST['time_expired'] == "1") {
            $this->handleTimeExpired(); // checkeo si se acabo el timepo
        } elseif (isset($_POST['respuesta']) && isset($_POST['pregunta'])) {
            $respuesta = $_POST['respuesta'];
            $idPregunta = $_POST['pregunta'];

            $categoria_nombre = $this->model->getCategoriaPorIdDePregunta($idPregunta);
            $valor_respuesta = $this->model->esRespuestaCorrecta($respuesta, $idPregunta);
            $pregunta = $this->model->getDescripcionDeLaPreguntaPorId($idPregunta);

            $datos = $this->getArr($categoria_nombre['nombre'], $valor_respuesta, $pregunta);

            $this->presenter->render("view/esRespuestaCorrecta.mustache", $datos);
        } else {
            echo "No se encontró la respuesta o la pregunta en el formulario.";
        }
    }

    
    private function getDatos($nombre, $valor_respuesta, $pregunta): array
    {
        $datos = [
            "categoria" => $nombre,
            "valor_respuesta" => $valor_respuesta == 1 ? "Correcta" : "Incorrecta",
            "pregunta" => $pregunta['texto'],
            "id_pregunta" => $pregunta['id']
        ];
        return $datos;
    }
}

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

        $id_usuario = $_SESSION['username']['id'];

        $this->model->arrancarPartida($id_usuario);
        $pregunta = $this->traerUnaPreguntaYActualizarDatos($id_usuario);
        $this->mostrarPreguntaYRespuestasPosibles($pregunta);

    }

    private function mostrarPreguntaYRespuestasPosibles($pregunta)
    {
        $id_usuario = $_SESSION['username']['id'];

        $rol = $this->verificarDeQueRolEsElUsuario($id_usuario);
        $categoria = $this->model->getCategoriaPorIdDePregunta($pregunta[0]['id']);

        $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);

        $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta, 'categoria' => $categoria, 'respuestas' => $respuestas, "rol" => $rol['rol']]);

    }
    private function traerUnaPreguntaYActualizarDatos($id_usuario)
    {
        $pregunta = $this->model->traerPreguntaAleatoriaSinRepeticionDePregunta($id_usuario);
        $this->model->registrarEnPreguntaVistaPorElUsuario($pregunta[0]['id'],$id_usuario);
        $this->model->updateDatosPregunta($pregunta[0]['id']);//sumar vecesEntregadas
        return $pregunta;
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

        $user_id = $_SESSION['username']['id'];

        $pregunta = $this->traerUnaPreguntaYActualizarDatos($user_id);

        $this->mostrarPreguntaYRespuestasPosibles($pregunta);

        //$this->traerRespuestasDespuesSiguiente($pregunta);
    }

    private function respuestaCorrectaPath($id_pregunta)
    {
        if (isset($_SESSION['username'])) {
            $user = $_SESSION['username'];
            $this->model->updatePregBienRespondidas($id_pregunta);
            $this->model->manejarNivelDePregunta($id_pregunta);
            $partida = $this->model->obtenerUltimaPartida($user['id']);
            $this->model->sumarPuntos($user['id'], $partida);
        }
    }

    /*
    private function manejoDeRespuesta($continuar, $id_pregunta)
    {
        $user_id = $_SESSION['username']['id'];

        $pregunta = $this->model->getDescripcionDeLaPreguntaPorId($id_pregunta);
        $categoria_nombre = $this->model->getCategoriaPorIdDePregunta($id_pregunta);

        $categoria = $categoria_nombre["nombre"];
        $rol = $this->verificarDeQueRolEsElUsuario($user_id);

        if ($continuar == "Incorrecta") {

            $puntaje = $this->model->obtenerCantidadDePuntos($user_id);
            $this->presenter->render("view/mostrarPuntajeDespuesPerder.mustache", ['puntaje' => $puntaje]);
        } else {
            $this->respuestaCorrectaPath($id_pregunta);
            $this->presenter->render("view/esRespuestaCorrecta.mustache", ['pregunta' => $pregunta['texto'], 'categoria' => $categoria, "rol" => $rol['rol']]);
        }
    }*/

    /*
    private function traerRespuestasDespuesSiguiente($pregunta)
    {
        if (isset($pregunta[0]['id'])) {
            $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);
            $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta[0], 'respuestas' => $respuestas]);
        } else {
            echo "No se encontró ninguna pregunta.";
        }
    }*/

    private function manejoDeElProcesoDeRespuesta()
    {
        $user_id = $_SESSION['username']['id'];
        $categoria = $this->model->getCategoriaPorIdDePregunta($_POST['pregunta']);
        $rol = $this->verificarDeQueRolEsElUsuario($user_id);

        if (isset($_POST['time_expired']) && $_POST['time_expired'] == "1") {
            $this->handleTimeExpired(); // checkeo si se acabo el timepo
        }elseif (isset($_POST['respuesta']) && isset($_POST['pregunta'])) {

            $respuesta = $_POST['respuesta'];
            $idPregunta = $_POST['pregunta'];
            $pregunta = $this->model->getDescripcionDeLaPreguntaPorId($idPregunta);

            if($this->model->esRespuestaCorrecta($respuesta, $idPregunta)){
                $this->respuestaCorrectaPath($idPregunta);
                $this->presenter->render("view/esRespuestaCorrecta.mustache", ['pregunta' => $pregunta['texto'], 'categoria' => $categoria, "rol" => $rol['rol']]);
            }else{
                $puntaje = $this->model->obtenerCantidadDePuntos($user_id);
                $this->presenter->render("view/mostrarPuntajeDespuesPerder.mustache", ['puntaje' => $puntaje]);
            }
            //$this->model->manejarNivelDePregunta($id_pregunta);
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

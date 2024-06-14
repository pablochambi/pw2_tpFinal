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
        $id_usuario =$this->checkSessionYTraerIdUsuario();
        $this->model->arrancarPartida($id_usuario);
        $pregunta = $this->traerUnaPreguntaYActualizarDatos($id_usuario);
        $this->mostrarPreguntaYRespuestasPosibles($pregunta);
    }

    private function traerUnaPreguntaYActualizarDatos($id_usuario)
    {
        $pregunta = $this->model->traerPreguntaAleatoriaSinRepeticionDePregunta($id_usuario);
        $this->model->registrarEnPreguntaVistaPorElUsuario($pregunta[0]['id'],$id_usuario);
        $this->model->updateDatosPregunta($pregunta[0]['id']);//sumar vecesEntregadas
        $this->model->actualizarPreguntasEntregadasAUnUsuario($id_usuario);
        return $pregunta;
    }
    private function mostrarPreguntaYRespuestasPosibles($pregunta)
    {
        $id_usuario = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($id_usuario);

        $categoria = $this->model->getCategoriaPorIdDePregunta($pregunta[0]['id']);
        $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);

        $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta, 'categoria' => $categoria, 'respuestas' => $respuestas, "rol" => $rol['rol']]);

    }

    public function procesarRespuesta()
    {
        $this->checkSession();
        $this->manejoDeElProcesoDeRespuesta();
    }
    private function manejoDeElProcesoDeRespuesta()
    {
        $user_id = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($user_id);

        $categoria = $this->model->getCategoriaPorIdDePregunta($_POST['pregunta']);

        if (isset($_POST['time_expired']) && $_POST['time_expired'] == "1") {
            $this->handleTimeExpired(); // checkeo si se acabo el timepo
        }elseif (isset($_POST['respuesta']) && isset($_POST['pregunta'])) {

            $respuesta = $_POST['respuesta'];
            $idPregunta = $_POST['pregunta'];
            $pregunta = $this->model->getDescripcionDeLaPreguntaPorId($idPregunta);

            if($this->model->esRespuestaCorrecta($respuesta, $idPregunta)){
                $this->respuestaCorrectaPath($idPregunta);
                $this->model->actualizarNivelDelUsuario($user_id);
                $this->presenter->render("view/esRespuestaCorrecta.mustache", ['pregunta' => $pregunta['texto'], 'categoria' => $categoria, "rol" => $rol['rol']]);
            }else{
                $puntaje = $this->model->obtenerCantidadDePuntos($user_id);
                $this->presenter->render("view/mostrarPuntajeDespuesPerder.mustache", ['puntaje' => $puntaje]);
            }
        } else {
            echo "No se encontró la respuesta o la pregunta en el formulario.";
        }
    }

    public function siguientePregunta()
    {
        $user_id = $this->checkSessionYTraerIdUsuario();
        $pregunta = $this->traerUnaPreguntaYActualizarDatos($user_id);
        $this->mostrarPreguntaYRespuestasPosibles($pregunta);
    }

    private function respuestaCorrectaPath($id_pregunta)
    {
        $id_usuario = $this->checkSessionYTraerIdUsuario();

        $this->model->updatePregBienRespondidas($id_pregunta);//sumar en vecesCorrectas
        $this->model->actualizarCantidadDePreguntasCorrectasAUnUsuario($id_usuario);
        $this->model->actualizarNivelDePregunta($id_pregunta);
        $partida = $this->model->obtenerUltimaPartida($id_usuario);
        $this->model->sumarPuntos($id_usuario, $partida);
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

    private function handleTimeExpired()
    {
        echo "El tiempo ha expirado. Has perdido la pregunta."; // ESTO DEBERIA SER UNA VISTA o controlarlo como mensaje pop up como mensajeValidacion.mustache
    }




}

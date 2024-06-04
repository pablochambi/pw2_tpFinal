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

        if(isset($_POST['id_usuario'])) {
            $id_usuario = $_POST['id_usuario'];
            $this->model->arrancarPartida($id_usuario);
        }

        $pregunta = $this->model->traerPreguntaAleatoria();
        /*yo traia el ['id'] pero me tiraba error mire google y dice que tenes
        que acceder al [0] para que te traiga el id de la primera consulta
        */
        $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);

        $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta, 'respuestas' => $respuestas]);
    }


    public function procesarRespuesta()
    {
        $this->checkSession();

        if (isset($_POST['respuesta']) && isset($_POST['pregunta'])) {
            $respuesta = $_POST['respuesta'];
            $idPregunta = $_POST['pregunta'];

            $categoria_nombre = $this->model->getCategoriaPorIdDePregunta($idPregunta);
            $valor_respuesta = $this->model->esRespuestaCorrecta($respuesta, $idPregunta);
            $pregunta_texto = $this->model->getDescripcionDeLaPreguntaPorId($idPregunta);

            $datos = [
                "categoria" => $categoria_nombre['nombre'],
                "valor_respuesta" => $valor_respuesta == 1 ? "Correcta" : "Incorrecta",
                "pregunta" => $pregunta_texto['texto'],
                "id_pregunta" => $pregunta_texto['id']
            ];

            $this->presenter->render("view/esRespuestaCorrecta.mustache", $datos);

        } else {
            echo "No se encontró la respuesta o la pregunta en el formulario.";
        }
    }

    public function siguientePregunta()
    {
        $this->checkSession();

        $pregunta = $this->model->traerPreguntaAleatoria();
        /*yo traia el ['id'] pero me tiraba error mire google y dice que tenes
        que acceder al [0] ya que es una matriz asociativa para que te traiga el id de la primera consulta
        */

        if (isset($pregunta[0]['id'])) {
            $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);
            $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta[0], 'respuestas' => $respuestas]);
        } else {
            echo "No se encontró ninguna pregunta.";
        }
    }

    public function continuar()
    {
        $this->checkSession();

        if (isset($_POST['valor_respuesta']) && isset($_POST['id_pregunta'])) {

            $continuar = $_POST['valor_respuesta'];
            $id_pregunta = $_POST['id_pregunta'];
            if ($continuar == "Incorrecta") {
                $user = $_SESSION['username'];
                $puntaje = $this->model->obtenerCantidadDePuntos($user['id']);
                $this->presenter->render("view/mostrarPuntajeDespuesPerder.mustache", ['puntaje' => $puntaje]);

            } else {
                if(isset($_SESSION['username'])){
                $user = $_SESSION['username'];
                $partida = $this->model->obtenerUltimaPartida($user['id']);

                /*$this->model->registrarPreguntaVistaPorElUsuario($id_pregunta,$user['id']);*/

                $this->model->sumarPuntos($user['id'], $partida);
                }
                header("Location: /partida/siguientePregunta");
            }

        }
    }


}



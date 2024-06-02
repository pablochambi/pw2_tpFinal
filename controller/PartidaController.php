<?php

class PartidaController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get()
    {
        session_start();

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
        session_start();

        if (isset($_POST['respuesta']) && isset($_POST['pregunta'])) {
            $respuesta = $_POST['respuesta'];
            $idPregunta = $_POST['pregunta'];

            $categoria_nombre = $this->model->getCategoriaPorIdDePregunta($idPregunta);
            $valor_respuesta = $this->model->esRespuestaCorrecta($respuesta, $idPregunta);
            $pregunta_texto = $this->model->getDescripcionDeLaPreguntaPorId($idPregunta);

            $datos = [
                "categoria" => $categoria_nombre['nombre'],
                "valor_respuesta" => $valor_respuesta == 1 ? "Correcta" : "Incorrecta",
                "pregunta" => $pregunta_texto['texto']
            ];

            $this->presenter->render("view/esRespuestaCorrecta.mustache", $datos);

        } else {
            echo "No se encontró la respuesta o la pregunta en el formulario.";
        }
    }

    public function siguientePregunta()
    {
        $pregunta = $this->model->traerPreguntaAleatoria();
        /*yo traia el ['id'] pero me tiraba error mire google y dice que tenes
        que acceder al [0] para que te traiga el id de la primera consulta
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

        if (isset($_POST['valor_respuesta'])) {

            $continuar = $_POST['valor_respuesta'];
            if ($continuar == "Incorrecta") {
                header("Location: /homeUsuario");

            } else {

                header("Location: /partida");
            }

        }
    }
}



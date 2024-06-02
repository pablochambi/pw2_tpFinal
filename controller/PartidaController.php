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
        $pregunta = $this->model->traerPreguntaAleatoria();
        /*yo traia el ['id'] pero me tiraba error mire google y dice que tenes
        que acceder al [0] para que te traiga el id de la primera consulta
        */
        $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);

        $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta, 'respuestas' => $respuestas]);
    }

    public function procesarRespuesta(){

        if(isset($_POST['respuesta']) && isset($_POST['pregunta'])){
            $respuesta = $_POST['respuesta'];
            $idPregunta = $_POST['pregunta'];
            $correcta = $this->model->esRespuestaCorrecta($respuesta, $idPregunta);

            if($correcta)
                header("Location: /perfil");
            else
                header("Location: /homeUsuario");
        }
    }

}

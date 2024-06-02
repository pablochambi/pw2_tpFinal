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
        $pregunta = $this->model->traerPreguntaAleatoria();
        /*yo traia el ['id'] pero me tiraba error mire google y dice que tenes
        que acceder al [0] para que te traiga el id de la primera consulta
        */
        $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);

        $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta, 'respuestas' => $respuestas]);
    }

    public function procesarRespuesta(){
        session_start();

        if(isset($_POST['respuesta']) && isset($_POST['pregunta'])){
            $respuesta = $_POST['respuesta'];
            $idPregunta = $_POST['pregunta'];

            $categoria_nombre =  $this->model->getCategoriaPorIdDePregunta($idPregunta);
            $valor_respuesta = $this->model->esRespuestaCorrecta($respuesta, $idPregunta);
            $pregunta_texto = $this->model->getDescripcionDeLaPreguntaPorId($idPregunta);


            $datos = [
                "categoria" => $categoria_nombre['nombre'],
                "valor_respuesta" => $valor_respuesta == 1 ? "Correcta" : "Incorrecta",
                "pregunta" => $pregunta_texto['texto']
            ];

            $this->presenter->render("view/esRespuestaCorrecta.mustache", $datos);

        }
    }

    public function siguientePregunta()
    {
        $pregunta = $this->model->traerPreguntaAleatoria();
        /*yo traia el ['id'] pero me tiraba error mire google y dice que tenes
        que acceder al [0] para que te traiga el id de la primera consulta
        */
        $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);

        $this->presenter->render("view/partida.mustache", ['pregunta' => $pregunta, 'respuestas' => $respuestas]);
    }



}

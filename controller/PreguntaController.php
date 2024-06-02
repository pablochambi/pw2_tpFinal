<?php

class PreguntaController {

    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function obtenerPregunta()
    {

      $pregunta = $this->model->obtenerPregunta();
      $this->presenter->render("view/pregunta.mustache", ["pregunta" => $pregunta]);
    }

}
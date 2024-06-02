<?php

class RespuestaController {

    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function obtenerRespuesta() // DONDE ESTA LA VISTA RESPUESTA WACHO
    {
      $respuesta = $this->model->obtenerRespuesta();
      $this->presenter->render("view/respuesta.mustache", ["respuesta" => $respuesta]);
    }

}
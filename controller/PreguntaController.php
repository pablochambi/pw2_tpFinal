<?php

class PreguntaController {

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
        if (isset($_SESSION)){
            $this->presenter->render("view/crearPregunta.mustache");
        }else{
            header("location: login");
            exit();
        }

    }


}
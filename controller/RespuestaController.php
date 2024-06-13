<?php

class RespuestaController extends BaseController {

    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get(){
        session_start();
        if (isset($_SESSION)){
            $this->presenter->render("view/crearRespuesta.mustache");
        }else{
            header("location: login");
            exit();
        }

    }

    public function obtenerRespuesta()
    {
      $respuesta = $this->model->obtenerRespuesta();
      $this->presenter->render("view/respuesta.mustache", ["respuesta" => $respuesta]);
    }

    public function crearRespuestasSugeridas()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $respuestas = $_POST['respuesta'];
            $id_pregunta = $_POST['id_pregunta'];
            $correcta = $_POST['correcta'];

            foreach ($respuestas as $index => $texto) {
                $es_correcta = ($index == $correcta) ? 1 : 0;
                $resultado = $this->model->crearRespuestasSugeridas($texto, $id_pregunta, $es_correcta);

            }
        } else {
            echo "error";
        }
    }



}
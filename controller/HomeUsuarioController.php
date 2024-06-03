<?php


class HomeUsuarioController{

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
        if (isset($_SESSION)) {
            $user = $_SESSION['username'];

            $rol = $this->model->verificarDeQueRolEsElUsuario($user['id']);

            $this->presenter->render("view/homeUsuario.mustache", ["usuario" => $user, "rol" => $rol['rol']]);
        }else{
            header("location:/login");
        }
    }

    public function obtenerPuntosTotales()
    {
        session_start();
        if (isset($_SESSION)) {
            $user = $_SESSION['username'];
            $puntaje = $this->model->sumarPuntajeAcumulado($user['id']);
            $this->presenter->render("view/puntaje.mustache", ["usuario" => $user, "puntaje" => $puntaje]);
        }else{
            header("location:/login");
        }
    }
}
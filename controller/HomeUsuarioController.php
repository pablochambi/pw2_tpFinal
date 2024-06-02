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
}
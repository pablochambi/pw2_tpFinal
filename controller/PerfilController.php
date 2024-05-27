<?php
class PerfilController
{
    private $model;
    private $presenter;

    public function get() // esto se vuela????
    {
        $this->presenter->render("view/perfilUsuario.mustache");
    }

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function mostrarPerfil()
    {
        session_start();
        if (isset($_SESSION["user_id"])) {
            $userId = $_SESSION["user_id"];
            $usuario = $this->model->obtenerDatosUsuario($userId);
            $this->presenter->render("view/perfilUsuario.mustache", $usuario);
        } else {
            header("location: login");
            exit();
        }

    }
}


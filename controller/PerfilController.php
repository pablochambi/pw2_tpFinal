<?php
class PerfilController
{
    private $model;
    private $presenter;



    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get()
    {
        session_start();
        if (isset($_SESSION["username"])) {
            $userId = $_SESSION["username"];
            $usuario = $this->model->obtenerUsuarioConNombrePaisPorId($userId);
            $this->presenter->render("view/perfilUsuario.mustache", ["usuario" => $usuario]);
        } else {
            header("location: login");
            exit();
        }

    }
}


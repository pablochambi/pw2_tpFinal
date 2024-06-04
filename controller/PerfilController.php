<?php
class PerfilController extends BaseController
{
    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        if (isset($_SESSION["username"])) {
            $userId = $_SESSION["username"];
            $usuario = $this->model->obtenerUsuarioConNombrePaisPorId($userId['id']);

            $this->presenter->render("view/perfilUsuario.mustache", ["usuario" => $usuario]);
        } else {
            header("location: login");
            exit();
        }

    }
}


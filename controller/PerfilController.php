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
            $userPais =
            $this->presenter->render("view/perfilUsuario.mustache", ["usuario" => $userId]);
        } else {
            header("location: login");
            exit();
        }

    }
}


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
        if (isset($_SESSION['username'])) {

            $user = $_SESSION['username'];
        }
            $this->presenter->render("view/homeUsuario.mustache", ["usuario" => $user]);

}



}
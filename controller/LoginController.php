<?php
class LoginController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get()
    {
        $this->presenter->render("view/login.mustache");
    }

    public function procesarLogeo(){

        if (isset($_POST["email"]) && isset($_POST["password"])){

            $email = $_POST["email"];
            $password= $_POST["password"];

            $result = $this->model->procesarInicioSesion($email, $password);

            if($result){//Poner el dato email y buscar los datos del usuario
                $this->presenter->reder("view/perfilUsuario.mustache");
            }

        }

    }
}
<?php
class BaseController
{
    protected $model;
    protected $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    protected function startSession()
    {
        session_start();
    }

    protected function checkSession()
    {
        if (!isset($_SESSION['username']) || empty($_SESSION)) {
            header("location: /login");
            exit();
        }
    }

    protected function checkSessionYTraerIdUsuario()
    {
        $this->checkSession();
        return $_SESSION['username']['id'];
    }



    protected function getUsername()
    {
        return $_SESSION["username"];
    }

    protected function verificarDeQueRolEsElUsuario($idUsuario)
    {
        return $this->model->verificarDeQueRolEsElUsuario($idUsuario);
    }

}
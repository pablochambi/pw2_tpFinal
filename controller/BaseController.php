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
        session_start(); // inicia una sesion
    }

    protected function checkSession()
    {
        if (!isset($_SESSION['username']) || empty($_SESSION)) {
            header("location: /login");
            exit();
        }
        /*Verifica si el usuario ha iniciado sesión.
        Si no es así, redirige al usuario a la página de inicio de sesión.
        Esto es útil para restringir el acceso a ciertas páginas a usuarios autenticados.*/
    }

    protected function checkSessionYTraerIdUsuario()
    {
        $this->checkSession();
        return $_SESSION['username']['id'];
        /*Verifica la sesión y devuelve el ID del usuario. Esto es útil para realizar operaciones específicas del usuario autenticado.*/
    }

    protected function getUsername()
    {
        return $_SESSION["username"];
        // Devuelve el nombre de usuario de la sesión actual. Útil para personalizar la experiencia del usuario.
    }

    protected function verificarDeQueRolEsElUsuario($idUsuario)
    {
        return $this->model->verificarDeQueRolEsElUsuario($idUsuario);
        //Verifica el rol del usuario en base a su ID. Esto puede ser útil para implementar control de acceso basado en roles.
    }
}
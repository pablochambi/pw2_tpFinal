<?php
class HomeUsuarioController extends BaseController
{
    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        $this->checkSession(); //verifica si hay una sesion activa sino me manda al login
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);
        $this->presenter->render("view/homeUsuario.mustache", ["usuario" => $user, "rol" => $rol['rol']]);
    }

    public function obtenerPuntosTotales()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $puntaje = $this->model->sumarPuntajeAcumulado($user['id']);
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);
        $this->presenter->render("view/puntaje.mustache", ["usuario" => $user, "puntaje" => $puntaje, "rol" => $rol['rol']]);
    }
}
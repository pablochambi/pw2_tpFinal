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
        if (!isset($_SESSION) || empty($_SESSION)) {
            header("location: /login");
            exit();
        }
    }

    protected function getUsername()
    {
        return $_SESSION["username"];
    }

    public function verificarDeQueRolEsElUsuario($idUsuario)
    {
        $consulta = "
        SELECT r.nombre AS rol
        FROM Usuarios u
        INNER JOIN Usuario_Rol ur ON u.id = ur.id_usuario
        INNER JOIN Rol r ON ur.id_rol = r.id
        WHERE u.id = ?;
    ";

        $stmt = $this->model->prepararConsulta($consulta);
        $this->model->unirParametros($stmt,"i", $idUsuario);
        return $this->model->obtenerResultados($stmt);
    }

}
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
        $this->checkSession();

        $userId = $_SESSION["username"];
        $usuario = $this->model->obtenerUsuarioConNombrePaisPorId($userId['id']);

        $this->presenter->render("view/perfilUsuario.mustache", ["usuario" => $usuario]);
    }

    public function mostrarPerfil()
    {
        if (!isset($_GET['id']))
            die('Usuario no especificado.');

        $id = $_GET['id'];
        $usuario = $this->usuarioModel->obtenerUsuarioPorUsername($id);

        if ($usuario === null)
            die('Usuario no encontrado.');

        $this->presenter->render("view/perfiles.mustache", ['usuario' => $usuario]);
    }
}


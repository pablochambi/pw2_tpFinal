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
        $usuario = $this->model->obtenerUsuarioPorUsername($userId['id']);
        $rol = $this->verificarDeQueRolEsElUsuario($userId['id']);

        $this->presenter->render("view/perfilUsuario.mustache", ["usuario" => $usuario, "rol" => $rol['rol']]);
    }

    public function mostrarPerfil()
    {
        $this->checkSession();

        if (!isset($_GET['username'])) {
            die('Usuario no especificado.');
        }

        $username = $_GET['username'];
        $usuario = $this->model->obtenerUsuarioPorUsername($username);

        if ($usuario === null) {
            die('Usuario no encontrado.');
        }

        $anioNacimiento = $usuario['anio_nacimiento'];
        $anioActual = date("Y");
        $edad = $anioActual - $anioNacimiento;
        $usuario['edad'] = $edad;

        $urlPerfil = 'http://localhost/perfiles?username=' . $username;
        $qrPath = 'public/qrs/' . $username . '.png';

        // genero el QR si no existe o si la URL cambio
        if (empty($usuario['qr']) || $usuario['qr'] !== $qrPath) {
            QRcode::png($urlPerfil, $qrPath);

            // actualizo la ruta del QR en la bdd
            $this->model->actualizarQRUsuario($username, $qrPath);
            $usuario['qr'] = $qrPath;
        }

        $this->presenter->render("view/perfiles.mustache", ['usuario' => $usuario]);
    }
}


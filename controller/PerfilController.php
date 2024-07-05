<?php

class PerfilController extends BaseController
{

    protected $mercadoPagoHandler;

    public function __construct($model, $presenter, $mercadoPagoHandler)
    {
        session_start();
        parent::__construct($model, $presenter);
        $this->mercadoPagoHandler = $mercadoPagoHandler;
    }

    public function get()
    {
        $this->checkSession();

        $userId = $_SESSION["username"];
        $usuario = $this->model->obtenerUsuarioConNombre($userId['id']);
        $rol = $this->verificarDeQueRolEsElUsuario($userId['id']);

        $this->presenter->render("view/perfilUsuario.mustache", ["usuario" => $usuario, "rol" => $rol['rol']]);
    }

    public function mostrarPerfil()
    {
        $this->checkSession();
        $rol = $this->verificarDeQueRolEsElUsuario($_SESSION["username"]['id']);

        if (!isset($_GET['username']))
            die('Usuario no especificado.');

        $username = $_GET['username'];
        $usuario = $this->model->obtenerUsuarioPorUsername($username);

        if ($usuario === null)
            die('Usuario no encontrado.');

        $anioNacimiento = $usuario['anio_nacimiento'];
        $anioActual = date("Y");
        $edad = $anioActual - $anioNacimiento;
        $usuario['edad'] = $edad;

        $urlPerfil = 'http://localhost/perfiles?username=' . $username;
        $qrPath = 'public/qrs/' . $username . '.png';

        // genero el QR si no existe o si la URL cambio
        QRcode::png($urlPerfil, $qrPath);

        // actualizo la ruta del QR en la bdd
        $this->model->actualizarQRUsuario($username, $qrPath);
        $usuario['qr'] = $qrPath;

        $this->presenter->render("view/perfiles.mustache", ['usuario' => $usuario, 'rol' => $rol['rol']]);
    }

    public function comprarTrampitas($usuarioId) {
        $this->checkSession();

        $userId = $_SESSION["username"];
        $usuario = $this->model->obtenerUsuarioConNombre($userId['id']);

        try {
            $this->mercadoPagoHandler->comprar();
            $costoTrampita = 1;

            if ($usuario['dinero'] >= $costoTrampita) {
                $nuevasTrampitas = $usuario['trampita'] + 1;
                $nuevoDinero = $usuario['dinero'] - $costoTrampita;

                $this->model->actualizarTrampitas($usuarioId, $nuevasTrampitas, $nuevoDinero);
                echo "Compra realizada con éxito. Trampitas disponibles: " . $nuevasTrampitas;
            } else {
                echo "Dinero insuficiente.";
            }
        } catch (Exception $e) {
            echo "Error al realizar la compra: " . $e->getMessage();
        }

        $this->presenter->render("view/comprar.mustache", ['usuario' => $usuario]);
    }
}


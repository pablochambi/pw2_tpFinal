<?php

class LoginController extends BaseController
{
    public function __construct($model, $presenter)
    {
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        // Destruir la sesión si está activa
        if (session_status() == PHP_SESSION_ACTIVE)
            session_destroy();

        $this->presenter->render("view/login.mustache");
    }

    public function procesarLogeo()
    {

        if (isset($_POST["email"]) && isset($_POST["password"])) {

            session_start();
            $email = $_POST["email"];
            $password = $_POST["password"];

            // aca llamo a procesarInicioSesion del modelo y guardo el resultado (verdadero si el inicio de sesion fue exitoso)
            $inicioSesionExitoso = $this->model->procesarInicioSesion($email, $password);

            if ($inicioSesionExitoso) {

                $user = $this->model->agarrarUsuarioDeLaBaseDeDatosPorEmail($email);

                $_SESSION['username'] = $user;

                header("Location: /homeUsuario");

            } else {
                header("Location: /login");
            }

        } else {
            header("Location: /login");
        }

    }
}
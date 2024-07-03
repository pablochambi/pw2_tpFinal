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

            $inicioSesionExitoso = $this->model->procesarInicioSesion($email, $password);

            if ($inicioSesionExitoso) {
                $user = $this->model->agarrarUsuarioDeLaBaseDeDatosPorEmail($email);
                $_SESSION['username'] = $user['username'];
                header("Location: /homeUsuario");
                exit();
            } else {
                $errorMsg = "Correo electrónico o contraseña incorrectos.";
                $this->presenter->render("view/login.mustache", ['error' => $errorMsg]);
                exit();
            }
        } else {
            $errorMsg = "Por favor, complete todos los campos.";
            $this->presenter->render("view/login.mustache", ['error' => $errorMsg]);
            exit();
        }
    }
}
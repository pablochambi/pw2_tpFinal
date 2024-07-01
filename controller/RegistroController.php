<?php

class RegistroController
{
    private $presenter;
    private $model;

    private $emailHelper;

    public function __construct($model, $presenter, $emailHelper)
    {
        $this->presenter = $presenter;
        $this->model = $model;
        $this->emailHelper = $emailHelper;
    }


    public function get()
    {
        $this->presenter->render("view/registro.mustache");
    }

    public function procesarRegistro()
    {
        if (isset($_POST['nombre']) && isset($_POST['sexo']) && isset($_POST['anio_nacimiento'])
             && isset($_POST['ciudad']) && isset($_POST['username'])
            && isset($_POST['email']) && isset($_POST['password'])) {

            $nombre = $_POST['nombre'];
            $anio_nacimiento = $_POST['anio_nacimiento'];
            $sexo = $_POST['sexo'];
            $pais = $_POST['pais'];
            $ciudad = $_POST['ciudad'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $username = $_POST['username'];
            $latitud = $_POST['latitud'];
            $longitud = $_POST['longitud'];
            $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;

            $direccionDestino = $this->model->verificarYSubirLaFotoDePerfil($foto);


           $token = $this->model->registrarUsuarioAlaBD($nombre, $anio_nacimiento, $sexo, $ciudad, $pais, $email, $password, $username, $direccionDestino, $latitud, $longitud);

            error_log("Token enviado por correo: " . $token);
            if($token) {
                $enviado = $this->emailHelper->enviarCorreo($email, $token);


                $this->verificarElEnvio($enviado);
            } else {
                die("Error al registrar el usuario");
            }

        } else {
            die("No se recibieron datos del formulario de registro");
        }
    }

    public function validar()
    {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $this->model->validarCorreo($token);
            $this->presenter->render("view/vistasPostAccion/bienvenido.mustache");
        } else {
            echo "No se proporcionó un token de validación.";
        }
    }

    public function confirmarValidacion()
    {
        $this->model->confirmarValidacion();
    }

    public function confirmarMail()
    {


        $this->presenter->render("view/confirmarMail.mustache");
    }


    private function verificarElEnvio($enviado)
    {
        if ($enviado) {
            $this->presenter->render("view/vistasPostAccion/confirmarMail.mustache");
        } else {
            die("Error al enviar el email de confirmación");
        }
    }


}
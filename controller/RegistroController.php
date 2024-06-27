<?php

class RegistroController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get()
    {
        $this->presenter->render("view/registro.mustache");
    }

    public function procesarRegistro()
    {
        if (isset($_POST['nombre']) && isset($_POST['sexo']) && isset($_POST['anio_nacimiento'])
            && isset($_POST['pais']) && isset($_POST['ciudad']) && isset($_POST['username'])
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

            $this->model->enviarEmail($token, $email);

            $this->presenter->render("view/confirmarMail.mustache");

        } else {
            die("No se recibieron datos del formulario de registro");
        }
    }

    public function validar()
    {
        $this->model->validarCorreo();
        $this->presenter->render("view/bienvenida.mustache");
    }

    public function confirmarValidacion()
    {
        $this->model->confirmarValidacion();
    }

    public function confirmarMail()
    {
        $this->presenter->render("view/confirmarMail.mustache");
    }
}
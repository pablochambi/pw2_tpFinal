<?php

class RegistroController
{
    private $presenter;
    private $model;

    public function __construct($model,$presenter)
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
        if (isset($_POST['nombre']) && isset($_POST['sexo'])&& isset($_POST['anio_nacimiento'])
            && isset($_POST['pais']) && isset($_POST['ciudad'])&& isset($_POST['username'])
            && isset($_POST['email']) && isset($_POST['password']) ){

            $nombre = $_POST['nombre'];
            $anio_nacimiento = $_POST['anio_nacimiento'];
            $sexo = $_POST['sexo'];
            $pais = $_POST['pais'];
            $ciudad = $_POST['ciudad'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $username = $_POST['username'];
            $foto =  isset($_FILES['foto']) ? $_FILES['foto'] : null;


            $direccionDestino = $this->model->verificarYSubirLaFotoDePerfil($foto);

            $token = $this->model->registrarUsuarioAlaBD($nombre, $anio_nacimiento, $sexo,$pais,$ciudad,$email,$password,$username,$direccionDestino);

            $mensaje = $this->model->enviarCorreoValidacion($email, $token);

            $this->presenter->render("view/validacion.mustache", ["mensaje" => $mensaje]);


        } else {
            echo "No se recibieron datos del formulario.";
        }


    }


    public function validar()
    {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];

           $mensaje = $this->model->habilitarCuentaConToken($token);

        } else {
            $mensaje  = "No se recibió el token.";
        }

        $this->presenter->render("view/mensajeValidacion.mustache", ["mensaje" => $mensaje]);
    }



}
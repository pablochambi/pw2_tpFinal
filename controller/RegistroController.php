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
        if (isset($_POST['nombre']) && isset($_POST['sexo']) && isset($_POST['anio_nacimiento']) && isset($_POST['pais'])
            && isset($_POST['ciudad']) && isset($_POST['username']) && isset($_POST['email'])
            && isset($_POST['password']) && isset($_POST['confirm_password'])) {

            $nombre = $_POST['nombre'];
            $anio_nacimiento = $_POST['anio_nacimiento'];
            $sexo = $_POST['sexo'];
            $pais = $_POST['pais'];
            $ciudad = $_POST['ciudad'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $username = $_POST['username'];
            $foto =  isset($_FILES['foto']) ? $_FILES['foto'] : null;

            // aca valido el correo electronico
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Correo electrónico no válido.";
                return;
            }

            // Verificar si las contraseñas coinciden
            if ($password != $confirm_password) {
                echo "Las contraseñas no coinciden.";
                return;
            }

            // Verificar si se ha subido una imagen de perfil
            if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                echo "Error al cargar la imagen de perfil.";
            }

            // Procesar la imagen de perfil
            $archivoSubido = null;
            if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
                $direccion = 'public/imagenes/';
                $archivoSubido = $direccion . basename($_FILES['foto']['name']);

                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $archivoSubido)) {
                    echo "Error al subir la imagen de perfil.";
                    return;
                }

            } else {
                echo "Error al cargar la imagen de perfil.";
                return;
            }

            $result = $this->model->registrarUsuarioAlaBD($nombre, $anio_nacimiento, $sexo, $pais, $ciudad, $email, $password, $username, $archivoSubido);

            if ($result) {
                echo "Registro exitoso.";
            } else {
                echo "Error al registrar el usuario.";
            }

        } else {
            echo "No se recibieron datos del formulario.";
        }

        /* $this->presenter->render("view/verificar.mustache");*/
    }

}
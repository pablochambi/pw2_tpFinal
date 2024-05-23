<?php

class RegistroModel
{
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }
    public function registrarUsuarioAlaBD($nombre, $anio_nacimiento, $sexo,$pais,$ciudad,$email, $password,$username,$foto)
    {
       return  $this->database->executeAndReturn("INSERT INTO usuarios (nombre_completo, anio_nacimiento, sexo, pais, ciudad, email,password, username, foto)
VALUES ('$nombre', '$anio_nacimiento', '$sexo', '$pais', '$ciudad', '$email', '$password', '$username', '$foto')");

    }

    public function verificarYSubirLaFotoDePerfil($foto)
    {
        if ($foto['error'] !== UPLOAD_ERR_OK) {
            echo "Error al cargar la imagen de perfil.";
        }

        $direccionDestino = 'public/imagenes/' . basename($foto['name']);

        if (!$this->subirFotoAUnaDireccion($foto, $direccionDestino)) {
            echo "Error al subir la imagen de perfil.";
        }

        return $direccionDestino;
    }



    private function subirFotoAUnaDireccion($direccionOrigen,$direccionDestino) : bool
    {
        return move_uploaded_file($direccionOrigen['tmp_name'], $direccionDestino);
    }


}
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

}
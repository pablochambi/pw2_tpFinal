<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function procesarInicioSesion($email, $password){

        $resultado = $this->database->prepare("SELECT * FROM usuario WHERE email = ?");
        if ($resultado -> num_rows > 0) {
            $fila = $resultado -> fetch_assoc();
            if (password_verify($password, $fila["password"])){
                session_start();
                $_SESSION["email"] = $email;
                header("location: futuro_home");
                exit();
            }else{
                echo "Contraseña incorrecta";
            }

        }else{
            echo "Usuario no encontrado";
        }

    }













}
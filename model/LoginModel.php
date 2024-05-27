<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function procesarInicioSesion($email, $password){

        $resultado = $this->database->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $resultado->bind_param("s", $email);

        $resultado->execute();
        $resultado = $resultado->get_result();

        //$resultado = $this->database->prepare("SELECT * FROM usuario WHERE email = ?");
        if ($resultado -> num_rows > 0) {
            $fila = $resultado -> fetch_assoc();
            if ($password == $fila["password"]){
                session_start();
                $_SESSION["email"] = $email;
                $_SESSION["user_id"] = $fila["id"];
                header("location: /perfil ");//Va primero al controlador
                exit();
            }else{
                echo "Contraseña incorrecta";
            }

        }else{
            echo "Usuario no encontrado";
        }

    }

    public function obtenerDatosUsuario($userId)
    {
        $stmt = $this->database->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
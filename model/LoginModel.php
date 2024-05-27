<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function procesarInicioSesion($email, $password){

        $seInicioSesion = false;
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
                $seInicioSesion =  true;
                //exit();
            }else{
                echo "Contraseña incorrecta";
            }

        }else{
            echo "Usuario no encontrado";
        }

        return $seInicioSesion;

    }

    public function obtenerDatosUsuario($userId)
    {
        $stmt = $this->database->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function agarrarUsuarioDeLaBaseDeDatosPorEmail($email)
    {
        $stmt = $this->database->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result(); // agarro el resultado de la consulta

        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
            // verifico si el numero de filas en el resultado es mayor que 0 y devuelvo la fila
        }
        return false;
    }


}
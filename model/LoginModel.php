<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function procesarInicioSesion($email, $password){

        /*session_start();*/

        $seInicioSesion = false;
        $resultado = $this->database->conn->prepare("SELECT * FROM Usuarios WHERE email = ?");
        $resultado->bind_param("s", $email);

        $resultado->execute();
        $resultado = $resultado->get_result();

        //$resultado = $this->database->prepare("SELECT * FROM usuario WHERE email = ?");
        if ($resultado -> num_rows > 0) {
            $fila = $resultado -> fetch_assoc();
            if ($password == $fila["password"]){
                $_SESSION["user_id"] = $fila["id"];
                $seInicioSesion =  true;
               //exit();
            }

        }

        return $seInicioSesion;

    }

    public function obtenerDatosUsuario($userId)
    {
        $stmt = $this->database->prepare("SELECT * FROM Usuarios WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function agarrarUsuarioDeLaBaseDeDatosPorEmail($email)
    {
        $stmt = $this->database->conn->prepare("SELECT * FROM Usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result(); // agarro el resultado de la consulta

        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
            // verifico si el numero de filas en el resultado es mayor que 0 y devuelvo la fila
        }
        return false;
    }

    public function obtenerUsuarioConNombrePaisPorId($idUsuario) {
        $consulta = "SELECT u.*, p.nombre AS nombre_pais 
                     FROM Usuarios u 
                     INNER JOIN Pais p ON u.id_pais = p.id 
                     WHERE u.id = ?";
        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }







}
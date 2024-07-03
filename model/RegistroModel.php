<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegistroModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrarUsuarioAlaBD($nombre, $anio_nacimiento, $sexo, $ciudad, $pais, $email, $password, $username, $foto, $latitud, $longitud)
    {
        $token = bin2hex(random_bytes(8));
        $habilitado = 0;
        $puntaje_acumulado = 0;
        $partidas_realizadas = 0;
        $nivel = 0.0;
        $qr = NULL;
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $consulta = "
        INSERT INTO Usuarios (nombre_completo, anio_nacimiento, sexo, ciudad, pais, email, password, username, token, foto, habilitado, puntaje_acumulado, partidas_realizadas, nivel, latitud, longitud, qr)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("sissssssssiididds", $nombre, $anio_nacimiento, $sexo, $ciudad, $pais, $email, $passwordHash, $username, $token, $foto, $habilitado, $puntaje_acumulado, $partidas_realizadas, $nivel, $latitud, $longitud, $qr);

        if ($stmt->execute()) {

            $idUsuario = $stmt->insert_id;
            $rolJugador = 3;
            $consultaRol = "INSERT INTO Usuario_Rol (id_usuario, id_rol) VALUES (?, ?)";
            $stmtRol = $this->database->prepare($consultaRol);
            $stmtRol->bind_param("ii", $idUsuario, $rolJugador);

            if (!$stmtRol->execute())
               return null;

            return $token;
        } else {
            return null;
        }
    }

    public function verificarYSubirLaFotoDePerfil($foto)
    {
        if ($foto['error'] !== UPLOAD_ERR_OK)
            return null;

        $direccionOrigen = $foto['tmp_name'];
        $direccionDestino = 'public/imagenes/' . basename($foto['name']);

        if (!$this->subirFotoAUnaDireccion($direccionOrigen, $direccionDestino))
            return null;

        return $direccionDestino;
    }

    private function subirFotoAUnaDireccion($direccionOrigen, $direccionDestino): bool
    {
        return move_uploaded_file($direccionOrigen, $direccionDestino);
    }

    public function validarCorreo($token)
    {

        $query = "SELECT * FROM Usuarios WHERE token = '$token' AND habilitado = 0";
        $result = $this->database->executeAndReturn($query);
        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();
            $updateQuery = "UPDATE Usuarios SET habilitado = 1 WHERE token = '$token'";
        } else {
            return null;
        }
    }
}
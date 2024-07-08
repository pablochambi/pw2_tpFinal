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
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $trampitasIniciales = 5;

        $consulta = "
    INSERT INTO Usuarios (nombre_completo, anio_nacimiento, sexo, ciudad, pais, email, password, username, token, foto, habilitado, puntaje_acumulado, partidas_realizadas, nivel, latitud, longitud, trampita)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("sissssssssiididdi", $nombre, $anio_nacimiento, $sexo, $ciudad, $pais, $email, $passwordHash, $username, $token, $foto, $habilitado, $puntaje_acumulado, $partidas_realizadas, $nivel, $latitud, $longitud, $trampitasIniciales);

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
        $stmt = $this->database->prepare("SELECT * FROM Usuarios WHERE token = ? AND habilitado = 0");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $stmt_update = $this->database->prepare("UPDATE Usuarios SET habilitado = 1 WHERE token = ?");
            $stmt_update->bind_param("s", $token);
            $stmt_update->execute();
            return true;
        } else {
            return null;
        }
    }

}
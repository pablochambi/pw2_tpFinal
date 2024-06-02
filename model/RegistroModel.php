<?php

class RegistroModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrarUsuarioAlaBD($nombre, $anio_nacimiento, $sexo, $pais, $ciudad, $email, $password, $username, $foto)
    {
        $token = bin2hex(random_bytes(8)); // Generar un token aleatorio
        $habilitado = 0;
        $puntaje_acumulado = 0;
        $partidas_realizadas = 0;
        $nivel = 0.0;
        $qr = NULL;

        // Preparar la consulta SQL
        $consulta = "
        INSERT INTO Usuarios (nombre_completo, anio_nacimiento, sexo, id_pais, ciudad, email, password, username, token, foto, habilitado, puntaje_acumulado, partidas_realizadas, nivel, qr)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

        $stmt = $this->database->prepare($consulta);
        // Vincular los parámetros
        $stmt->bind_param("sissssssssiidis", $nombre, $anio_nacimiento, $sexo, $pais, $ciudad, $email, $password, $username, $token, $foto, $habilitado, $puntaje_acumulado, $partidas_realizadas, $nivel, $qr);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            // Obtener el ID del usuario recién insertado
            $idUsuario = $stmt->insert_id;

            // Asignar el rol de 'Jugador' al nuevo usuario
            $rolJugador = 3; // Teniendo en cuenta que 3 es el ID para el rol 'Jugador'
            $consultaRol = "INSERT INTO Usuario_Rol (id_usuario, id_rol) VALUES (?, ?)";
            $stmtRol = $this->database->prepare($consultaRol);
            $stmtRol->bind_param("ii", $idUsuario, $rolJugador);

            if (!$stmtRol->execute()) {
                echo "Error al asignar el rol al usuario: " . $stmtRol->error;
            }
            return $token;
        } else {
            echo "Error al registrar al usuario: " . $stmt->error;
            return null;
        }
    }
/*
    public function registrarUsuarioAlaBD($nombre, $anio_nacimiento, $sexo,$pais,$ciudad,$email, $password,$username,$foto)
    {
        $token = bin2hex(random_bytes(8)); // Generar un token aleatorio
        $habilitado = 0;

        $resultado = $this->database->executeAndReturn(
            "INSERT INTO usuarios (nombre_completo, anio_nacimiento, sexo, id_pais, ciudad, email,password, username, foto,token,habilitado)
VALUES ('$nombre', '$anio_nacimiento', '$sexo', '$pais', '$ciudad', '$email', '$password', '$username', '$foto', '$token', '$habilitado')");

        if(!$resultado)
            echo "Error al registrar al usuario: " . mysqli_error($this->database->conn);

        return $token;
    }*/

    public function verificarYSubirLaFotoDePerfil($foto)
    {
        if ($foto['error'] !== UPLOAD_ERR_OK)
            echo "Error al cargar la imagen de perfil.";

        $direccionOrigen = $foto['tmp_name'];
        $direccionDestino = 'public/imagenes/' . basename($foto['name']);

        if (!$this->subirFotoAUnaDireccion($direccionOrigen, $direccionDestino))
            echo "Error al subir la imagen de perfil.";

        return $direccionDestino;
    }

    private function subirFotoAUnaDireccion($direccionOrigen,$direccionDestino) : bool
    {
        return move_uploaded_file($direccionOrigen, $direccionDestino);
    }

    public function enviarCorreoValidacion($email, $token){
        $mensaje = 'Por favor, haz clic en el siguiente enlace para validar tu cuenta:' . $email .'<br>';
        $mensaje .= '<a href="/registro/validar?token=' . $token . '">Validar Cuenta</a>';
        return $mensaje;
    }

    public function habilitarCuentaConToken($token){

        $sql = "SELECT * FROM usuarios WHERE token='$token' AND habilitado=0";
        $result = $this->database->query($sql);

        if (count($result) == 1) {
            $updateSql = "UPDATE usuarios SET habilitado=1 WHERE token='$token'";
            if ($this->database->executeAndReturn($updateSql))
                $mensaje =  "Cuenta validada correctamente.";
            else
                $mensaje = "Error al validar la cuenta.";
        } else {
            $mensaje =  "Token no válido o cuenta ya validada.";
        }

        return $mensaje;
    }

    public function obtenerPaises(){
        $paises = $this->database->query("SELECT * FROM pais");
        return $paises;
    }
}
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
        $token = bin2hex(random_bytes(8)); // Generar un token aleatorio
        $habilitado = 0;
        $puntaje_acumulado = 0;
        $partidas_realizadas = 0;
        $nivel = 0.0;
        $qr = NULL;

        $consulta = "
        INSERT INTO Usuarios (nombre_completo, anio_nacimiento, sexo, ciudad, pais, email, password, username, token, foto, habilitado, puntaje_acumulado, partidas_realizadas, nivel, latitud, longitud, qr)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("sissssssssiididds", $nombre, $anio_nacimiento, $sexo, $ciudad, $pais, $email, $password, $username, $token, $foto, $habilitado, $puntaje_acumulado, $partidas_realizadas, $nivel, $latitud, $longitud, $qr);

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

            // Enviar correo de validación
            $this->enviarEmail($token, $email);

            return $token;
        } else {
            echo "Error al registrar al usuario: " . $stmt->error;
            return null;
        }
    }

    public function verificarYSubirLaFotoDePerfil($foto)
    {
        if ($foto['error'] !== UPLOAD_ERR_OK) {
            echo "Error al cargar la imagen de perfil.";
            return null;
        }

        $direccionOrigen = $foto['tmp_name'];
        $direccionDestino = 'public/imagenes/' . basename($foto['name']);

        if (!$this->subirFotoAUnaDireccion($direccionOrigen, $direccionDestino)) {
            echo "Error al subir la imagen de perfil.";
            return null;
        }

        return $direccionDestino;
    }

    private function subirFotoAUnaDireccion($direccionOrigen, $direccionDestino): bool
    {
        return move_uploaded_file($direccionOrigen, $direccionDestino);
    }

    public function enviarEmail($token, $email)
    {
        $asunto = 'Confirmá tu email para empezar a jugar';
        $cuerpo = "Por favor, haz clic en el siguiente enlace para validar tu correo electrónico: ";
        $cuerpo .= "http://localhost/registro/validar?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'preguntadoswebii@gmail.com';
            $mail->Password = 'Preguntados2024!';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('preguntadoswebii@gmail.com');
            $mail->addAddress($email);
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;
            $mail->send();
        } catch (Exception $e) {
            echo "No se pudo enviar el correo. Error de PHPMailer: {$mail->ErrorInfo}";
        }
    }

    public function validarCorreo()
    {
        // Obtener el token desde la URL
        $token = $_GET['token'];

        $query = "SELECT * FROM Usuarios WHERE token = '$token' AND habilitado = 0";
        $result = $this->database->query($query);

        if ($result->num_rows == 1) {
            // El token es válido, marcar la cuenta de usuario como validada
            $usuario = $result->fetch_assoc();
            $email = $usuario['email'];
            $id = $usuario['id'];

            // Actualizar la columna de validación en la base de datos
            $updateQuery = "UPDATE Usuarios SET habilitado = 1 WHERE token = '$token'";
            $insertQuery = "INSERT INTO Usuario_Rol (id_usuario, id_rol) VALUES ('$id', '3')";

            if ($this->database->query($updateQuery) && $this->database->query($insertQuery)) {
                echo "Cuenta validada correctamente.";
            } else {
                echo "Error al validar la cuenta.";
            }
        } else {
            // El token no es válido
            echo "El token de validación no es válido.";
        }
    }
}
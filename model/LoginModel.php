<?php

class LoginModel extends BaseModel
{
    public function __construct($database)
    {
        parent:: __construct($database);
    }

    public function procesarInicioSesion($email, $password)
    {

        $seInicioSesion = false;
        $resultado = $this->database->conn->prepare("SELECT * FROM Usuarios WHERE email = ?");
        $resultado->bind_param("s", $email);

        $resultado->execute();
        $resultado = $resultado->get_result();

        if ($resultado->num_rows == 1) {
            $fila = $resultado->fetch_assoc();

            if (password_verify($password, $fila['password'])) {
                $seInicioSesion = true;
            }

        } elseif ($resultado->num_rows == 0) {
            die("No se encuentra el mail ingresado en la base de datos");
        } else {
            die("Hay mails repetidos en la base de datos");
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

        if ($resultado->num_rows > 0)
            return $resultado->fetch_assoc();
        // verifico si el numero de filas en el resultado es mayor que 0 y devuelvo la fila

        return false;
    }

    public function obtenerUsuarioConNombre($idUsuario)
    {
        $consulta = "
    SELECT u.*, 
           COALESCE(SUM(p.puntaje), 0) as puntaje_acumulado,
           COALESCE(COUNT(p.id), 0) as partidas_realizadas
    FROM Usuarios u
    LEFT JOIN Partida p ON u.id = p.id_usuario
    WHERE u.id = ?
    GROUP BY u.id
    ";
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

    public function obtenerUsuarioPorUsername($username)
    {
        // COALESCE devuelve la primera expresión no nula de una lista de expresiones
        $stmt = $this->database->prepare("
        SELECT u.*, 
               COALESCE(SUM(p.puntaje), 0) as puntaje_acumulado,
               COALESCE(COUNT(p.id), 0) as partidas_realizadas
        FROM Usuarios u
        LEFT JOIN Partida p ON u.id = p.id_usuario
        WHERE u.username = ?
        GROUP BY u.id
    ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarQRUsuario($username, $qrPath)
    {
        $query = "UPDATE Usuarios SET qr = ? WHERE username = ?";
        $stmt = $this->database->prepare($query);

        if ($stmt === false)
            throw new Exception("error u.u: " . $this->database->error);

        $stmt->bind_param("ss", $qrPath, $username);

        if (!$stmt->execute())
            throw new Exception("errorx2: " . $stmt->error);

        return true;
    }


}
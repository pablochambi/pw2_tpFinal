<?php
class HomeUsuarioModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function verificarDeQueRolEsElUsuario($idUsuario)
    {
        $consulta = "
        SELECT r.nombre AS rol
        FROM Usuarios u
        INNER JOIN Usuario_Rol ur ON u.id = ur.id_usuario
        INNER JOIN Rol r ON ur.id_rol = r.id
        WHERE u.id = ?;
    ";

        $stmt = $this->database->prepare($consulta);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->database->error);
        }

        $stmt->bind_param("i", $idUsuario);
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Obtener el resultado
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }


    public function sumarPuntajeAcumulado($idUsuario)
    {

        $consulta = "SELECT SUM(puntaje) AS puntaje_acumulado FROM Partida WHERE id_usuario = ?";

        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if($row['puntaje_acumulado'] == null){
            return 0;
        }

        return $row['puntaje_acumulado'];
    }

}

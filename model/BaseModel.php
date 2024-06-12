<?php
class BaseModel
{
    protected $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function ejecutarEnLaBD1($consulta,$tiposDeDatos,$parametro)
    {
        $stmt = $this->database->prepare($consulta);
        if (!$stmt)
            die("Error en la preparación de la consulta: " . $this->database->error);

        $stmt->bind_param($tiposDeDatos, $parametro);

        if (!$stmt->execute())
            die("Error al ejecutar la consulta: " . $stmt->error);

        return $stmt;
    }

    public function ejecutarEnLaBD2($consulta,$tiposDeDatos, $idUsuario, $idPregunta)
    {
        $stmt = $this->database->prepare($consulta);
        if (!$stmt)
            die("Error en la preparación de la consulta: " . $this->database->error);

        $stmt->bind_param($tiposDeDatos, $idUsuario, $idPregunta);
        if (!$stmt->execute())
            die("Error al ejecutar la consulta: " . $stmt->error);

        return $stmt;
    }



    public function obtenerResultados($stmt)
    {
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0)
            return $resultado->fetch_assoc();
        else{
            echo "No hay resultados  para la consulta";
            exit();
        }
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

        $stmt = $this->ejecutarEnLaBD1($consulta,"i",$idUsuario);
        return $this->obtenerResultados($stmt);
    }
}
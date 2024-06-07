<?php
class BaseModel
{
    protected $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function prepararConsulta($consulta)
    {
        $stmt = $this->database->prepare($consulta);
        if (!$stmt)
            die("Error en la preparación de la consulta: " . $this->database->error);

        return $stmt;
    }

    public function unirParametros($stmt,$tipoDeDato,$parametro)
    {
        $stmt->bind_param($tipoDeDato, $parametro);

        if (!$stmt->execute())
            die("Error al ejecutar la consulta: " . $stmt->error);
    }

    public function obtenerResultados($stmt)
    {
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0)
            return $resultado->fetch_assoc();
        else
            return null;
    }
}
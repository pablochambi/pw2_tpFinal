<?php
class BaseModel
{
    protected $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    protected function prepararConsulta($consulta)
    {
        $stmt = $this->database->prepare($consulta);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->database->error);
        }
        return $stmt;
    }

    protected function unirParametros($stmt,$tipoDeDato,$parametro)
    {
        $stmt->bind_param($tipoDeDato, $parametro);

        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }
    }

    protected function obtenerResultados($stmt)
    {
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }


}
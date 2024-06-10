<?php

class RespuestaModel {

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerRespuesta(){
        $respuesta = $this->database->query("SELECT * FROM Respuesta where id_pregunta = 1");
        return $respuesta;
    }

    public function crearRespuestaSugerida($texto, $id_pregunta, $es_correcta)
    {
        $query = "INSERT INTO Respuesta_Sugerida (texto, id_pregunta, es_correcta) 
              VALUES (?, ?, ?)";

        $stmt = $this->database->prepare($query);
        $stmt->bind_param('sii', $texto, $id_pregunta, $es_correcta);

        if ($stmt->execute())
            return true;
    }

}
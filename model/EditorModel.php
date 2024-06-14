<?php

class EditorModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function traerPreguntasSugeridas()

    {
        $query = "SELECT 
                        p.texto AS Pregunta, 
                        GROUP_CONCAT(CONCAT(r.texto, IF(r.es_correcta = 1, ' (correcta)', '')) SEPARATOR ', ') AS Respuestas
                    FROM 
                        respuesta r 
                    JOIN 
                        pregunta p 
                    ON 
                        p.id = r.id_pregunta 
                    WHERE 
                        p.valida = 0
                    GROUP BY 
                        p.texto;";


        $result = $this->database->executeAndReturn($query);

        if ($result === false)
            die('Error en la consulta: ' . $this->database->error);

        $preguntas = [];

        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }

        return $preguntas;
    }


}
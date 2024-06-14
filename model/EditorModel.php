<?php

class EditorModel extends BaseModel
{

    public function __construct($database)
    {
        parent::__construct($database);
    }

    public function traerPreguntasSugeridas()

    {
        $query = "SELECT 
                        p.id as idPregunta,
                        p.texto AS Pregunta, 
                        GROUP_CONCAT(CONCAT(r.texto, IF(r.es_correcta = 1, ' (correcta)', '')) SEPARATOR ', ') AS Respuestas
                    FROM 
                        respuesta r 
                    JOIN 
                        pregunta p 
                    ON 
                        p.id = r.id_pregunta 
                    WHERE 
                        p.activa = 0
                    GROUP BY 
                       p.id, p.texto;";


        $result = $this->database->executeAndReturn($query);

        if ($result === false)
            die('Error en la consulta: ' . $this->database->error);

        $preguntas = [];

        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }

        return $preguntas;
    }

    public function aceptarPreguntaSugerida($idPregunta)
    {
        $query = "UPDATE pregunta SET activa = 1 WHERE id = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();

        if ($stmt->affected_rows == 0)
            die('Error al aceptar la pregunta: ' . $this->database->error);

    }

    public function denegarPreguntaSugerida($idPregunta)
    {
        $query = "delete from pregunta where id = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();

        if ($stmt->affected_rows == 0)
            die('Error al denegar la pregunta: ' . $this->database->error);

       
    }


}
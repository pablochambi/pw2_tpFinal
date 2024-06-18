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
                        Respuesta r 
                    JOIN 
                        Pregunta p 
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
        $query = "UPDATE Pregunta SET activa = 1 WHERE id = ? ";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();

        if ($stmt->affected_rows == 0)
            die('Error al aceptar la pregunta: ' . $this->database->error);

    }

    public function denegarPreguntaSugerida($idPregunta)
    {
        $query = "delete from Pregunta where id = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();

        if ($stmt->affected_rows == 0)
            die('Error al denegar la pregunta: ' . $this->database->error);


    }

    public function traerTodasLasPreguntas()
    {
        $pregunta = $this->database->query("SELECT * FROM Pregunta");
        return $pregunta;
    }

    public function buscarPreguntasPorIdONombre($filtro)
    {
        $pregunta = "SELECT * FROM Pregunta WHERE id = '$filtro' OR texto like '%$filtro%'";
        $resultado = $this->database->executeAndReturn($pregunta);

        if ($resultado && $resultado->num_rows > 0) {
            $preguntasEncontradas = [];
            while ($row = $resultado->fetch_assoc()) {
                $preguntasEncontradas[] = $row;
            }
            return $preguntasEncontradas;
        }


    }

    public function actualizarPregunta($id, $nuevoTitulo)
    {
        $pregunta = "UPDATE Pregunta SET texto = ? WHERE id = ?";
        $stmt = $this->database->prepare($pregunta);
        $stmt->bind_param("si", $nuevoTitulo, $id);
        $stmt->execute();



    }


}
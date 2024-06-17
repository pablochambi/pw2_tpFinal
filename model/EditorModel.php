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
                    FROM Respuesta r 
                    JOIN Pregunta p 
                    ON p.id = r.id_pregunta 
                    WHERE p.activa = 0
                    GROUP BY p.id, p.texto;";

        $result = $this->database->executeAndReturn($query);

        if ($result === false)
            die('Error en la consulta: ' . $this->database->error);

        $preguntas = [];

        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }

        return $preguntas;
    }

    public function traerPreguntasReportadas()
    {
        $query = "
        SELECT 
            p.id AS idPregunta,
            p.texto AS Pregunta, 
            GROUP_CONCAT(CONCAT(r.texto, IF(r.es_correcta = 1, ' (correcta)', '')) SEPARATOR ', ') AS Respuestas,
            rp.descripcion AS DescripcionReporte
        FROM Respuesta r 
        JOIN Pregunta p ON p.id = r.id_pregunta 
        JOIN Reporte_Pregunta rp ON p.id = rp.id_pregunta 
        WHERE rp.revisada = 0
        GROUP BY p.id, p.texto, rp.descripcion;
    ";

        $result = $this->database->executeAndReturn($query);

        if ($result === false) {
            die('Error en la consulta: ' . $this->database->error);
        }

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

        if ($stmt->affected_rows === 0)
            die('Error al aceptar la pregunta: ' . $this->database->error);

    }

    public function eliminarPregunta($idPregunta)
    {
        $this->eliminarLasReferenciasEnPreguntasVistas($idPregunta);
        $this->eliminarLasReferenciasEnReportePreguntas($idPregunta);

        $query = "DELETE FROM Pregunta WHERE id = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param('i', $idPregunta);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            die('Error eliminando pregunta: ' . $this->database->error);
        }
    }

    public function eliminarPreguntaDeLaListaDeReportes($idPregunta,$idUsuario)
    {
        $query = "delete from Reporte_Pregunta
       where id_pregunta = ? and id_usuario = ? ";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("ii", $idPregunta,$idUsuario);

        if (!$stmt->execute())
            die('Error al eliminar una pregunta de la lista de reportes: ' . $this->database->error);
    }

    private function eliminarLasReferenciasEnPreguntasVistas($idPregunta)
    {
        $query = "DELETE FROM PreguntaVistas WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($query);
        if (!$stmt) {
            die('Error en la preparación de la consulta (PreguntaVistas): ' . $this->database->error);
        }
        $stmt->bind_param('i', $idPregunta);
        $stmt->execute();

        if ($stmt->error) {
            die('Error eliminando referencias en PreguntaVistas: ' . $stmt->error);
        }
    }

    private function eliminarLasReferenciasEnReportePreguntas($idPregunta)
    {
        $query = "DELETE FROM Reporte_Pregunta WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($query);

        if (!$stmt) die('Error en la preparación de la consulta: ' . $this->database->error);

        $stmt->bind_param('i', $idPregunta);

        if (!$stmt->execute()) die('Error ejecutando la consulta: ' . $stmt->error);
    }


}
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

    public function denegarPreguntaSugerida($idPregunta)
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

    public function buscarRespuestaPorIdPregunta($idPregunta)
    {
        $respuesta = "SELECT * FROM Respuesta WHERE id_pregunta = $idPregunta";
        $resultado = $this->database->executeAndReturn($respuesta);

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

    public function traerCategoriasPorId($pregunta)
    {
        $categoria = "SELECT * FROM Categoria WHERE id = $pregunta";
        $resultado = $this->database->executeAndReturn($categoria);
        if ($resultado && $resultado->num_rows > 0) {
            $preguntasEncontradas = [];
            while ($row = $resultado->fetch_assoc()) {
                $preguntasEncontradas[] = $row;
            }
            return $preguntasEncontradas;

        }
    }

    public function traerTodasLasCategorias()
    {
        $categoria = $this->database->query("SELECT * FROM Categoria");
        return $categoria;
    }


    public function actualizarCategoriaDeLaPregunta($id, $nuevaCategoria)
    {
        $categoria = "UPDATE Pregunta SET id_categoria = ? WHERE id = ?";
        $stmt = $this->database->prepare($categoria);
        $stmt->bind_param("ii", $nuevaCategoria, $id);
        $stmt->execute();

    }

    public function actualizarDificultadDeLaPregunta($id, $nuevaDificultad)
    {
        $dificultad = "UPDATE Pregunta SET nivel = ? WHERE id = ?";
        $stmt = $this->database->prepare($dificultad);
        $stmt->bind_param("ii", $nuevaDificultad, $id);
        $stmt->execute();

    }

    public function actualizarRespuestaCorrecta($id, $preguntaCorrecta)
    {
        $correcta = "UPDATE Respuesta SET texto = ? WHERE id_pregunta = ? AND es_correcta = 1";
        $stmt = $this->database->prepare($correcta);
        $stmt->bind_param("si", $preguntaCorrecta, $id);
        $stmt->execute();

    }

    public function obtenerRespuestasIncorrectas($idPregunta)
    {
        $query = "SELECT id, texto  
              FROM Respuesta 
              WHERE id_pregunta = ? AND es_correcta = 0 
              ORDER BY id ASC LIMIT 3";

        $stmt = $this->database->prepare($query);
        if (!$stmt) {
            die('Error en la preparación de la consulta obtenerRespuestasIncorrectas: ' . $this->database->error);
        }

        $stmt->bind_param("i", $idPregunta);

        $stmt->execute();

        $result = $stmt->get_result();

        $respuestas = array();
        while ($row = $result->fetch_assoc()) {

            $respuestas[] = [
                'id' => $row['id'],
                'texto' =>  $row['texto'],
            ];

        }

        return [
            'incorrecta1' => isset($respuestas[0]) ? $respuestas[0] : ['id' => '', 'texto' => ''],
            'incorrecta2' => isset($respuestas[1]) ? $respuestas[1] : ['id' => '', 'texto' => ''],
            'incorrecta3' => isset($respuestas[2]) ? $respuestas[2] : ['id' => '', 'texto' => ''],
        ];
    }

    public function actualizarRespuestaIncorrecta($idPregunta, $idRespuesta, $nuevaRespuestaIncorrecta)
    {
        $query = "UPDATE Respuesta 
              SET texto = ?
              WHERE id_pregunta = ? AND es_correcta = 0 and id = $idRespuesta
              ORDER BY id ASC LIMIT ?";

        $stmt = $this->database->prepare($query);
        $stmt->bind_param("sii", $nuevaRespuestaIncorrecta, $idPregunta, $idRespuesta);
        $stmt->execute();



    }

    public function eliminarLaPregunta($idPregunta)
    {

        $query = "UPDATE pregunta SET activa = 0 WHERE id = ? ";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param('i', $idPregunta);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            die('Error eliminando pregunta: ' . $this->database->error);
        }


    }





}
<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function traerPreguntaAleatoria() {
        $query = "SELECT * FROM Pregunta ORDER BY RAND() LIMIT 1";
        $pregunta = $this->database->query($query);
        return $pregunta;
    }

    public function traerRespuestasDesordenadas($idPregunta) {
        $query = "SELECT texto
                  FROM Respuesta
                  WHERE id_pregunta = $idPregunta";
        $respuestas = $this->database->query($query);

        shuffle($respuestas);
        return $respuestas;
    }

    public function esRespuestaCorrecta($textoRespuesta, $idPregunta)
    {
        $query = "Select es_correcta 
                    from Respuesta 
                    where texto = '$textoRespuesta' and id_pregunta = $idPregunta";

       $result = $this->database->query($query);

        if($result[0]['es_correcta'] == 1)
            return true;
        else
            return false;
    }

    public function getCategoriaPorIdDePregunta($idPregunta) {
        $consulta = "
        SELECT c.nombre
        FROM Pregunta p
        INNER JOIN Categoria c ON p.id_categoria = c.id
        WHERE p.id = ?;
    ";

        $stmt = $this->database->prepare($consulta);
        if (!$stmt) {die("Error en la preparación de la consulta: " . $this->database->error);}

        $stmt->bind_param("i", $idPregunta);
        if (!$stmt->execute()) {die("Error al ejecutar la consulta: " . $stmt->error);}

        // Obtener el resultado
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0)
            return $resultado->fetch_assoc();
        else
            return null;

    }

    public function getDescripcionDeLaPreguntaPorId($idPregunta) {
        $consulta = "
        SELECT texto
        FROM Pregunta p
        WHERE p.id = ?;
    ";

        $stmt = $this->database->prepare($consulta);
        if (!$stmt) {die("Error en la preparación de la consulta: " . $this->database->error);}

        $stmt->bind_param("i", $idPregunta);
        if (!$stmt->execute()) {die("Error al ejecutar la consulta: " . $stmt->error);}

        // Obtener el resultado
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0)
            return $resultado->fetch_assoc();
        else
            return null;

    }

    public function arrancarPartida($usuario)
    {
        $fecha = date('Y-m-d H:i:s');
        $arrancarPartida = "Insert into partida (id_usuario, fecha) values ($usuario, '$fecha')";
        echo "Consulta SQL: $arrancarPartida";
        $result  = $this->database->executeAndReturn($arrancarPartida);
       

        return $result;

    }

    public function obtenerUltimaPartida($id_usuario)
    {
        $query = "SELECT id FROM Partida WHERE id_usuario = $id_usuario ORDER BY fecha DESC LIMIT 1";
        $result = $this->database->executeAndReturn($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            return null;
        }
    }
    public function sumarPuntos($id_usuario, $idPartida)
    {
        $query = "UPDATE Partida set puntaje = puntaje + 1 where id_usuario = $id_usuario and id = $idPartida";
        return $this->database->executeAndReturn($query);
    }



}
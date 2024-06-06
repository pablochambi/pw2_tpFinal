<?php

class PartidaModel extends BaseModel
{
    public function __construct($database)
    {
        parent:: __construct($database);
    }

    /*public function traerPreguntaAleatoriaSinRepeticionDePregunta($idUsuario) {
        $consulta= "SELECT P.*
                    FROM Pregunta P
                    LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = '$idUsuario'
                    WHERE PV.id_usuario IS NULL
                    ORDER BY RAND()
                    LIMIT 1;";
        return $this->database->query($consulta);
    }*/

    public function traerPreguntaAleatoriaSinRepeticionDePregunta($idUsuario) {
        // Verificar si hay preguntas disponibles sin repetición para el usuario
        $consultaVerificar = "SELECT COUNT(*) AS total
                          FROM Pregunta P
                          LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = '$idUsuario'
                          WHERE PV.id_usuario IS NULL";

        $resultado = $this->database->query($consultaVerificar);
        //$fila = $resultado->fetch_assoc();No sirve

        // Verificamos que haya resultados
        if (!empty($resultado)) {
            // Accedemos al primer elemento del array
            $primerResultado = $resultado[0];

            // Accedemos al valor de la clave "total"
            $totalPreguntasDisponibles = $primerResultado["total"];

        } else {
            die ("No se conto la cantidad de preguntas que faltan verse");
        }

        if ($totalPreguntasDisponibles > 0) {
            // Si hay preguntas disponibles, obtener una pregunta aleatoria sin repetición
            $consulta = "SELECT P.*
                     FROM Pregunta P
                     LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = '$idUsuario'
                     WHERE PV.id_usuario IS NULL
                     ORDER BY RAND()
                     LIMIT 1";
            return $this->database->query($consulta);
        } else {
            // Si no hay preguntas disponibles, devolver un mensaje indicando que no hay más preguntas
            die ("No hay mas preguntas disponibles");
        }
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

        $stmt = $this->prepararConsulta($consulta);
        $this->unirParametros($stmt,"i", $idPregunta);
        return $this->obtenerResultados($stmt);

    }

    public function getDescripcionDeLaPreguntaPorId($idPregunta) {
        $consulta = "
        SELECT *
        FROM Pregunta p
        WHERE p.id = ?;
    ";

        $stmt = $this->prepararConsulta($consulta);
        $this->unirParametros($stmt,"i", $idPregunta);
        return $this->obtenerResultados($stmt);

    }

   public function registrarEnPreguntaVistaPorElUsuario($idPregunta,$idUsuario) {
        $consulta = " 
        INSERT INTO PreguntaVistas (id_usuario, id_pregunta) VALUES (?, ?);
         ";

        $stmt = $this->database->prepare($consulta);
        if (!$stmt) {die("Error en la preparación de la consulta: " . $this->database->error);}

        $stmt->bind_param("ii", $idUsuario,$idPregunta);
        if (!$stmt->execute()) {die("Error al ejecutar la consulta: " . $stmt->error);}

    }

    public function arrancarPartida($usuario)
    {
        $fecha = date('Y-m-d H:i:s');
        $arrancarPartida = "Insert into Partida (id_usuario, fecha) values ($usuario, '$fecha')";
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

    public function obtenerCantidadDePuntos($id_usuario)
    {
        $query = "SELECT puntaje FROM Partida WHERE id_usuario = $id_usuario ORDER BY fecha DESC LIMIT 1";
        $result = $this->database->executeAndReturn($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['puntaje'];
        } else {
            return 0;
        }
    }

    public function updateDatosPregunta($idPregunta)
    {
        $query = "UPDATE pregunta set vecesEntregadas = vecesEntregadas + 1 where id = $idPregunta";
        $result = $this->database->executeAndReturn($query);
        return $result;
    }

    public function updatePregBienRespondidas($idPregunta)
    {
        $query = "UPDATE pregunta set vecesCorrectas = vecesCorrectas + 1 where id = $idPregunta";
        $result = $this->database->executeAndReturn($query);
        return $result;
    }



}
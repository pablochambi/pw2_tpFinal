<?php

class PartidaModel extends BaseModel
{
    public function __construct($database){
        parent:: __construct($database);
    }

    public function traerPreguntaAleatoriaSinRepeticionDePregunta($idUsuario)
    {
        $totalPreguntasDisponibles = $this->contarCantidadDePreguntasNoVistasPorUnUsuario($idUsuario);

        if ($totalPreguntasDisponibles > 0) {
            return $this->retornarPreguntaAleatoriaQueNoSeHayaVisto($idUsuario);
        }else{
            $this->resetearPreguntasVistas($idUsuario);
            return $this->retornarPreguntaAleatoriaQueNoSeHayaVisto($idUsuario);
        }

    }

    public function traerRespuestasDesordenadas($idPregunta)
    {
        $query = "SELECT R.*
                  FROM Respuesta R
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

        if ($result[0]['es_correcta'] == 1)
            return true;
        else
            return false;
    }
    public function getCategoriaPorIdDePregunta($idPregunta)
    {
        $consulta = "
        SELECT c.nombre
        FROM Pregunta p
        INNER JOIN Categoria c ON p.id_categoria = c.id
        WHERE p.id = ?;
    ";

        $stmt = $this->ejecutarEnLaBD1($consulta,"i",$idPregunta);
        return $this->obtenerResultados($stmt);

    }
    public function getDescripcionDeLaPreguntaPorId($idPregunta)
    {
        $consulta = "
        SELECT *
        FROM Pregunta p
        WHERE p.id = ?;
    ";
        $stmt = $this->ejecutarEnLaBD1($consulta,"i",$idPregunta);
        return $this->obtenerResultados($stmt);
    }
    public function registrarEnPreguntaVistaPorElUsuario($idPregunta, $idUsuario)
    {
        if (!$this->estaVistaLaPregunta($idPregunta, $idUsuario)) {

            $consulta = " 
            INSERT INTO PreguntaVistas (id_usuario, id_pregunta)
            VALUES (?, ?);
            ";
            $this->ejecutarEnLaBD2($consulta,'ii', $idUsuario, $idPregunta);
        }else{
            echo "La pregunta ya esta respondida";
            exit();
        }

    }

    public function arrancarPartida($usuario)
    {
        $fecha = date('Y-m-d H:i:s');
        $arrancarPartida = "Insert into Partida (id_usuario, fecha) values ($usuario, '$fecha')";
        $result = $this->database->executeAndReturn($arrancarPartida);

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
        $query = "UPDATE Pregunta 
                 set vecesEntregadas = vecesEntregadas + 1
                  where id = $idPregunta";
        $result = $this->database->executeAndReturn($query);
        return $result;
    }
    public function updatePregBienRespondidas($idPregunta)
    {
        $query = "UPDATE Pregunta set vecesCorrectas = vecesCorrectas + 1 where id = $idPregunta";
        $result = $this->database->executeAndReturn($query);
        return $result;
    }

    public function sumarVecesEntregadasUnaPreguntaAUnUsuario($id_pregunta, $user_id)
    {//No usada
        $consulta = "UPDATE PreguntaVistas 
                     SET veces_entregadas = veces_entregadas + 1 
                     WHERE id_usuario = ? AND id_pregunta = ?";

        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("ii", $user_id, $id_pregunta);
        $stmt->execute();
    }
    public function sumarEnPreguntaVistaVecesAcertadasPorUnUsuario($id_pregunta, $user_id)
    {
        $consulta = "UPDATE PreguntaVistas SET veces_acertadas = veces_acertadas + 1
                      WHERE id_pregunta = ? AND id_usuario = ?";

        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("ii", $id_pregunta, $user_id);
        $stmt->execute();
    }
    public function manejarNivelDePregunta($idPregunta)
    {
        $query = "SELECT vecesEntregadas, vecesCorrectas From Pregunta where id = $idPregunta";

        $result = $this->database->executeAndReturn($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $vecesEntregadas = $row['vecesEntregadas'];
            $vecesCorrectas = $row['vecesCorrectas'];

            $nivel = $this->retornarNivel($vecesEntregadas, $vecesCorrectas, $idPregunta);

            return $nivel;
        }

    }
    private function verificarCantidadPuntos($resultadoDePuntaje): string
    {
        if (!empty($resultadoDePuntaje))
            $puntaje = $resultadoDePuntaje[0]['puntaje'];

        if ($puntaje < 1)
            $nivel = "FACIL";
        else if ($puntaje < 2)
            $nivel = "MEDIO";
        else if ($puntaje < 3)
            $nivel = "DIFICIL";
        return $nivel;
    }
    private function retornarNivel($vecesEntregadas, $vecesCorrectas, $idPregunta): string
    {
        $nivelActual = $this->obtenerNivelActualDesdeBD($idPregunta);

        if ($vecesEntregadas == 0)
            $nuevoNivel = "FACIL";
        else {
            $porcentaje = ($vecesCorrectas / $vecesEntregadas) * 100;
            if ($porcentaje >= 80)
                $nuevoNivel = "FACIL";
            else if ($porcentaje >= 50)
                $nuevoNivel = "MEDIO";
            else
                $nuevoNivel = "DIFICIL";
        }

        if ($nuevoNivel !== $nivelActual){
            $this->actualizarNivelDePreguntaEnBD($idPregunta, $nuevoNivel);
         }

    return $nuevoNivel;
    }
    private function obtenerNivelActualDesdeBD($idPregunta)
    {
        $query = "SELECT nivel FROM Pregunta WHERE id = $idPregunta";
        $result = $this->database->executeAndReturn($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['nivel'];
        } else {
            return null;
        }
    }
    private function actualizarNivelDePreguntaEnBd($idPregunta, $nuevoNivel)
    {
        $query = "UPDATE Pregunta SET nivel = '$nuevoNivel' WHERE id = $idPregunta";
        $this->database->executeAndReturn($query);
    }
    private function contarCantidadDePreguntasVistas($cant_veces_vistas, $idUsuario)
    {
        $consultaVerificar = "SELECT COUNT(*) AS total
                          FROM Pregunta P
                          LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = '$idUsuario'
                          WHERE PV.veces_entregadas =  $cant_veces_vistas ";

        $resultado = $this->database->query($consultaVerificar);

        return $this->retornarCantidadTotalDePreguntas($resultado);

    }
    private function retornarCantidadTotalDePreguntas($resultado)
    {
        if (isset($resultado) && !empty($resultado)) {
            $primerResultado = $resultado[0];
            $totalPreguntasDisponibles = $primerResultado["total"];
        } else {
            die ("No se conto la cantidad de preguntas que faltan verse");
        }
        return $totalPreguntasDisponibles;
    }
    private function traerUnaPreguntaAleatoriaQueNoSeHayaVisto($idUsuario)
    {
        $consulta = "SELECT P.*
                     FROM Pregunta P
                     LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = '$idUsuario'
                     WHERE PV.id_usuario IS NULL 
                     ORDER BY RAND()
                     LIMIT 1";

        return $this->database->query($consulta);
    }
    private function contarCantidadDePreguntasNoVistasPorUnUsuario($idUsuario)
    {

        $consultaVerificar = "SELECT COUNT(*) AS total
                          FROM Pregunta P
                          LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = '$idUsuario'
                          WHERE PV.id_usuario IS NULL";

        $resultado = $this->database->query($consultaVerificar);

        return $this->retornarCantidadTotalDePreguntas($resultado);
    }

    private function obtenerPuntajeDeUnaPartida($idPartida)
    {
        $consultarPuntaje = "SELECT puntaje
                                FROM Partida
                                WHERE id = $idPartida";

        return $this->database->query($consultarPuntaje);
    }

    private function estaVistaLaPregunta($idPregunta, $idUsuario)
    {
        // Consulta para verificar si ya existe un registro en la tabla PreguntaVistas para la pregunta y el usuario especificados
        $consulta = "SELECT COUNT(*) AS total FROM PreguntaVistas 
                    WHERE id_pregunta = ? AND id_usuario = ?";

        $stmt = $this->ejecutarEnLaBD2($consulta,'ii',$idPregunta, $idUsuario);

        $total_registros = $stmt->get_result()->fetch_assoc()['total'];

        return ($total_registros > 0);
    }

    private function retornarPreguntaAleatoriaQueNoSeHayaVisto($idUsuario)
    {
        $pregunta = $this->traerUnaPreguntaAleatoriaQueNoSeHayaVisto($idUsuario);

        if (!isset($pregunta) || empty($pregunta)) {
            echo "No se pudo traer una pregunta aleatoria que no  haya sido vista";
            echo "<a href='/homeUsuario' >Volver Al Home</a><br>";
            exit();
        } else {
            return $pregunta;
        }
    }

    private function resetearPreguntasVistas($idUsuario)
    {
        $consulta = "DELETE FROM preguntavistas
                      WHERE id_usuario = ? ";
        $this->ejecutarEnLaBD1($consulta,"i",$idUsuario);
    }


}
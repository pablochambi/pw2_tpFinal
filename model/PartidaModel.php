<?php

class PartidaModel extends BaseModel
{
    const CANTIDAD_DE_PREGUNTAS_FACILES_INICIALES = 8;

    public function __construct($database)
    {
        parent:: __construct($database);
    }

    public function traerPreguntaAleatoriaSinRepeticionDePregunta($idUsuario)
    {
        $preguntasEntregadas = $this->getCantidadDePreguntasEntregadasAUnUsuario($idUsuario); // guarda la cantidad de preguntas que respondió el usuario

        if ($preguntasEntregadas < self::CANTIDAD_DE_PREGUNTAS_FACILES_INICIALES) {
            return $this->retornarPreguntaAleatoriaQueNoSeHayaVistoYSeaDeNivelFacil($idUsuario);
        } // se devuelve una pregunta aleatoria de nivel fácil que el usuario no haya visto antes

        $totalPreguntasNiveladas = $this->contarCantidadDePreguntasNoVistasPorUnUsuarioYSeaDeSuNivel($idUsuario);

        if ($totalPreguntasNiveladas > 0) {
            // Si hay preguntas no vistas disponibles en el nivel del usuario, se devuelve una pregunta aleatoria de ese nivel
            return $this->retornarPreguntaAleatoriaQueNoSeHayaVistoYSeaDeSuNivel($idUsuario);
        } else {
            $this->resetearPreguntasVistasDelNivelDelUsuario($idUsuario);
            return $this->retornarPreguntaAleatoriaQueNoSeHayaVistoYSeaDeSuNivel($idUsuario);
        }

    }

    public function traerRespuestasDesordenadas($idPregunta)
    {
        $query = "SELECT R.*
                  FROM Respuesta R
                  WHERE id_pregunta = $idPregunta";
        $respuestas = $this->database->query($query);

        shuffle($respuestas); // tira un array aleatorio
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
        SELECT c.*
        FROM Pregunta p
        INNER JOIN Categoria c ON p.id_categoria = c.id
        WHERE p.id = ?;
    ";

        $stmt = $this->ejecutarEnLaBD1($consulta, "i", $idPregunta);
        return $this->obtenerResultados($stmt);

    }

    public function getPreguntaPorIdDePregunta($idPregunta)
    {
        $consulta = "
        SELECT *
        FROM Pregunta p
        WHERE p.id = ?;
    ";
        $stmt = $this->ejecutarEnLaBD1($consulta, "i", $idPregunta);
        return $this->obtenerResultados($stmt);
    }

    public function registrarEnPreguntaVistaPorElUsuario($idPregunta, $idUsuario)
    {
        if (!$this->estaVistaLaPregunta($idPregunta, $idUsuario)) {

            $consulta = " 
            INSERT INTO PreguntaVistas (id_usuario, id_pregunta)
            VALUES (?, ?);
            ";
            $this->ejecutarEnLaBD2($consulta, 'ii', $idUsuario, $idPregunta);
        } else {
            echo "La pregunta ya esta respondida";
            exit();
        }

    }

    public function arrancarPartida($usuario)
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
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
            return '0';
        }
    }

    public function updateDatosPregunta($idPregunta)
    {
        $query = "UPDATE Pregunta 
                 set vecesEntregadas = vecesEntregadas + 1
                  where id = $idPregunta";

        $this->actualizarNivelDePregunta($idPregunta);

        $result = $this->database->executeAndReturn($query);
        return $result;
    }

    public function actualizarPreguntasEntregadasAUnUsuario($id_usuario)
    {
        $query = "UPDATE Usuarios
                 set preguntas_entregadas = preguntas_entregadas + 1
                  where id = $id_usuario ";

        $this->actualizarNivelDelUsuario($id_usuario);

        $this->database->execute($query);
    }

    public function actualizarNivelDelUsuario($id_usuario)
    {
        $query = "SELECT preguntas_acertadas, preguntas_entregadas 
                    From Usuarios 
                    where id = ? ";

        $stmt = $this->ejecutarEnLaBD1($query, "i", $id_usuario);

        $fila = $this->obtenerResultados($stmt);

        if ($fila['preguntas_entregadas'] != 0) {
            $this->UpdateNivelDelUsuario($fila['preguntas_acertadas'], $fila['preguntas_entregadas'], $id_usuario);
        }
    }

    public function actualizarCantidadDePreguntasCorrectasAUnUsuario($id_usuario)
    {
        $query = "UPDATE Usuarios
                 set preguntas_acertadas = preguntas_acertadas + 1
                  where id = $id_usuario ";
        $this->database->execute($query);
    }

    public function updatePregBienRespondidas($idPregunta)
    {
        $query = "UPDATE Pregunta set vecesCorrectas = vecesCorrectas + 1 where id = $idPregunta";
        $result = $this->database->executeAndReturn($query);
        return $result;
    }

    public function actualizarNivelDePregunta($idPregunta)
    {
        $query = "SELECT vecesEntregadas, vecesCorrectas From Pregunta where id = $idPregunta";

        $result = $this->database->executeAndReturn($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $vecesEntregadas = $row['vecesEntregadas'];
            $vecesCorrectas = $row['vecesCorrectas'];

            return $this->retornarNivel($vecesEntregadas, $vecesCorrectas, $idPregunta);
        }
    }

    public function registrarReporte($idPregunta, $idUsuario, $razon)
    {
        if (!$this->estaRegistradoElReporte($idPregunta, $idUsuario)) {

            $registro = " 
            INSERT INTO Reporte_Pregunta (id_pregunta, id_usuario,descripcion)
            VALUES (?,?,?);
            ";
            $this->ejecutarEnLaBD3Parametros($registro, 'iis', $idPregunta, $idUsuario, $razon);
        } else {
            $actualizacion = "UPDATE Reporte_Pregunta
                 SET descripcion = '$razon'
                  WHERE id_usuario = $idUsuario AND id_pregunta = $idPregunta ";
            $this->database->execute($actualizacion);
        }
    }

    public function obtenerCantidadDeTrampas($idUsuario)
    {
        $query = "select trampita from Usuarios where id = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $result = $result->fetch_assoc();
            if ($result['trampita'] > 0)
                return $result['trampita'];
            else
                return 0;
        } else
            return 0;
    }

    public function restarUnaTrampaSiEsUsada($idUsuario)
    {
        $query = "UPDATE Usuarios
                 set trampita = trampita - 1
                  where id = $idUsuario ";
        $result = $this->database->executeAndReturn($query);
        return $result;
    }

    public function obtenerDosRespuestasAleatorias($idPregunta)
    {
        $query = "SELECT *
                  FROM Respuesta 
                  WHERE id_pregunta = $idPregunta and es_correcta = 0
                  ORDER BY RAND()
                  LIMIT 2";
        $respuestas = $this->database->executeAndReturn($query);

        return $respuestas;
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
            if ($porcentaje >= 66)
                $nuevoNivel = "FACIL";
            else if ($porcentaje >= 33)
                $nuevoNivel = "MEDIO";
            else
                $nuevoNivel = "DIFICIL";
        }

        if ($nuevoNivel !== $nivelActual) {
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
        $this->database->execute($query);
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
            $primerResultado = $resultado[0]; // primer resultado
            $totalPreguntasDisponibles = $primerResultado["total"];
        } else {
            die ("No se conto la cantidad de preguntas que faltan verse");
        }
        return $totalPreguntasDisponibles;
    }

    private function contarCantidadDePreguntasNoVistasPorUnUsuarioYSeaDeSuNivel($idUsuario)
    {
        $nivelDePregunta = $this->retornarNivelDePreguntaParaUnUsuario($idUsuario);

        $consulta = "SELECT COUNT(*) AS total
                          FROM Pregunta P
                          LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta
                          AND PV.id_usuario = $idUsuario
                          WHERE PV.id_usuario IS NULL AND P.nivel = '$nivelDePregunta' ";

        $resultado = $this->database->query($consulta);

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

        $stmt = $this->ejecutarEnLaBD2($consulta, 'ii', $idPregunta, $idUsuario);

        $total_registros = $stmt->get_result()->fetch_assoc()['total'];

        return ($total_registros > 0);
    }

    private function retornarPreguntaAleatoriaQueNoSeHayaVistoYSeaDeSuNivel($idUsuario)
    {
        $nivelDePregunta = $this->retornarNivelDePreguntaParaUnUsuario($idUsuario);

        $pregunta = $this->retornarPregunta($idUsuario, $nivelDePregunta);

        if (!isset($pregunta) || empty($pregunta)) {
            die("No hay una pregunta del nivel $nivelDePregunta ");
            //return $this->retornarPreguntaNoVistaSinImportarElNivelDeUsuario($idUsuario);
        } else {
            return $pregunta;
        }
    }

    private function resetearPreguntasVistas($idUsuario)
    {
        $consulta = "DELETE FROM Preguntavistas
                      WHERE id_usuario = ? ";
        $this->ejecutarEnLaBD1($consulta, "i", $idUsuario);
    }

    private function resetearPreguntasVistasDelNivelDelUsuario($idUsuario)
    {
        $nivelDePregunta = $this->retornarNivelDePreguntaParaUnUsuario($idUsuario);
        $consulta = "DELETE PV
                        FROM PreguntaVistas PV
                        JOIN Pregunta P ON PV.id_pregunta = P.id
                        JOIN Usuarios U ON PV.id_usuario = U.id
                        WHERE U.id = ? AND P.nivel = ? ";
        $this->ejecutarEnLaBD2($consulta, "is", $idUsuario, $nivelDePregunta);
    }

    private function retornarNivelDePreguntaParaUnUsuario($idUsuario): string
    {
        $consulta = "SELECT nivel
                     FROM Usuarios 
                     WHERE id = ? ";
        $stmt = $this->ejecutarEnLaBD1($consulta, "i", $idUsuario);

        $nivelDelUsuario = $stmt->get_result()->fetch_assoc()['nivel'];

        switch ($nivelDelUsuario) {
            case 'BAJO':
                $nivelPregunta = 'FACIL';
                break;
            case 'MEDIO':
                $nivelPregunta = 'MEDIO';
                break;
            case 'ALTO':
                $nivelPregunta = 'DIFICIL';
                break;
            default:
                $nivelPregunta = 'DESCONOCIDO';
                break;
        }
        return $nivelPregunta;
    }

    private function UpdateNivelDelUsuario($preguntasAcertadas, $preguntasEntregadas, $id_usuario)
    {
        $porcentaje = ($preguntasAcertadas / $preguntasEntregadas) * 100;

        if ($porcentaje >= 70.0)
            $nuevoNivel = 'ALTO';
        elseif ($porcentaje >= 40.0)
            $nuevoNivel = 'MEDIO';
        else
            $nuevoNivel = 'BAJO';

        $query = "UPDATE Usuarios
                    SET nivel = ?
                    WHERE id = ? ";
        $this->ejecutarEnLaBD2($query, "si", $nuevoNivel, $id_usuario);

    }

    private function retornarPregunta($idUsuario, string $nivelDePregunta)
    {
        $consulta = "SELECT P.*
                     FROM Pregunta P
                     LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = $idUsuario
                     WHERE PV.id_usuario IS NULL AND P.nivel = '$nivelDePregunta' and P.activa = 1
                     ORDER BY RAND()
                     LIMIT 1";

        return $this->database->query($consulta);
    }

    private function retornarPreguntaNoVistaSinImportarElNivelDeUsuario($idUsuario)
    {
        $consulta = "SELECT P.*
                     FROM Pregunta P
                     LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = $idUsuario
                     WHERE PV.id_usuario IS NULL and p.activa = 1
                     ORDER BY RAND()
                     LIMIT 1";

        return $this->database->query($consulta);
    }

    private function retornarPreguntaAleatoriaQueNoSeHayaVistoYSeaDeNivelFacil($idUsuario)
    {
        $totalPreguntasFaciles = $this->contarCantidadDePreguntasNoVistasPorUnUsuarioYSeaDeNivelFacil($idUsuario);

        if ($totalPreguntasFaciles > 0) {
            return $this->retornarPreguntaNivelFacil($idUsuario);
        } else {
            $this->resetearPreguntasFacilesVistasPorElUsuario($idUsuario);
            return $this->retornarPreguntaNivelFacil($idUsuario);
        }
    }

    private function contarCantidadDePreguntasNoVistasPorUnUsuarioYSeaDeNivelFacil($idUsuario)
    {
        // las preguntas no vistas no tienen idUsuario en la tabla PreguntasVistas
        $consulta = "SELECT COUNT(*) AS total
                          FROM Pregunta P
                          LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta
                          AND PV.id_usuario = $idUsuario
                          WHERE PV.id_usuario IS NULL AND P.nivel = 'FACIL' ";

        $resultado = $this->database->query($consulta);
        return $this->retornarCantidadTotalDePreguntas($resultado); // cantidad total de preguntas que faltan por verse
    }

    private function retornarPreguntaNivelFacil($idUsuario)
    {
        $nivelDePregunta = 'FACIL';
        $pregunta = $this->retornarPregunta($idUsuario, $nivelDePregunta);

        if (!isset($pregunta) || empty($pregunta)) {
            die("No hay una pregunta del nivel $nivelDePregunta ");
            //return $this->retornarPreguntaNoVistaSinImportarElNivelDeUsuario($idUsuario);
        } else {
            return $pregunta;
        }
    }

    private function resetearPreguntasFacilesVistasPorElUsuario($idUsuario)
    {
        $nivelDePregunta = 'FACIL';
        $consulta = "DELETE PV
                        FROM PreguntaVistas PV
                        JOIN Pregunta P ON PV.id_pregunta = P.id
                        JOIN Usuarios U ON PV.id_usuario = U.id
                        WHERE U.id = ? AND P.nivel = ? ";
        $this->ejecutarEnLaBD2($consulta, "is", $idUsuario, $nivelDePregunta);
    }

    private function getCantidadDePreguntasEntregadasAUnUsuario($idUsuario)
    {
        $consulta = "SELECT preguntas_entregadas
                     FROM Usuarios u
                        WHERE u.id = $idUsuario ";

        $resultado = $this->database->query($consulta);

        if (isset($resultado) && !empty($resultado)) {
            $primerResultado = $resultado[0];
            $totalPreguntasEntregadas = $primerResultado["preguntas_entregadas"];
        } else {
            die ("No se conto la cantidad de preguntas entregadas a un usuario");
        }
        return $totalPreguntasEntregadas;
    }

    private function estaRegistradoElReporte($idPregunta, $idUsuario)
    {
        // Consulta para verificar si ya existe un registro en la tabla PreguntaVistas para la pregunta y el usuario especificados
        $consulta = "SELECT COUNT(*) AS total FROM Reporte_Pregunta 
                    WHERE id_pregunta = ? AND id_usuario = ?";

        $stmt = $this->ejecutarEnLaBD2($consulta, 'ii', $idPregunta, $idUsuario);

        $total_registros = $stmt->get_result()->fetch_assoc()['total'];

        return ($total_registros > 0);
    }

}
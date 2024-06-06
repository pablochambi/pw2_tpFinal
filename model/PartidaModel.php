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

    /*public function traerPreguntaAleatoriaSinRepeticionDePregunta($idUsuario) {

        $totalPreguntasDisponibles = $this->contarCantidadDePreguntasNoVistasPorUnUsuario($idUsuario);

        if ($totalPreguntasDisponibles > 0) {

            return $this->traerUnaPreguntaAleatoriaQueNoSeHayaVisto($idUsuario);

        } else {
            $cant_veces_vistas = 1;
            $cant = $this->contarCantidadDePreguntasVistas($cant_veces_vistas,$idUsuario);
            if($cant > 0){
                return $this->traerUnaPreguntaAleatoriaQueSeHayaVisto($cant_veces_vistas,$idUsuario);
            }else{
                $cant_veces_vistas = 2;
                $cant = $this->contarCantidadDePreguntasVistas($cant_veces_vistas,$idUsuario);
                if($cant > 0) {
                    return $this->traerUnaPreguntaAleatoriaQueSeHayaVisto($cant_veces_vistas, $idUsuario);
                }else{
                    $cant_veces_vistas = 3;
                    $cant = $this->contarCantidadDePreguntasVistas($cant_veces_vistas,$idUsuario);
                    if($cant > 0) {
                        return $this->traerUnaPreguntaAleatoriaQueSeHayaVisto($cant_veces_vistas, $idUsuario);
                    }else{

                    }
                }
            }

            // Si no hay preguntas disponibles, devolver un mensaje indicando que no hay más preguntas
            die ("No hay mas preguntas disponibles");
        }
    }*/

    public function traerPreguntaAleatoriaSinRepeticionDePregunta($idUsuario) {
        // Contamos la cantidad de preguntas no vistas por el usuario
        $totalPreguntasDisponibles = $this->contarCantidadDePreguntasNoVistasPorUnUsuario($idUsuario);

        if ($totalPreguntasDisponibles > 0) {
            // Si hay preguntas no vistas, devolvemos una pregunta aleatoria sin repetición
            return $this->traerUnaPreguntaAleatoriaQueNoSeHayaVisto($idUsuario);
        }

        // Si no hay preguntas no vistas, intentamos obtener una pregunta aleatoria que se haya visto
        // Iteramos desde 1 hasta 3 para buscar preguntas vistas
        for ($cant_veces_vistas = 1; $cant_veces_vistas <= 3; $cant_veces_vistas++) {
            $cant = $this->contarCantidadDePreguntasVistas($cant_veces_vistas, $idUsuario);
            // Si encontramos una pregunta vista para la cantidad de veces indicada, la devolvemos
            if ($cant > 0) {
                return $this->traerUnaPreguntaAleatoriaQueSeHayaVisto($cant_veces_vistas, $idUsuario);
            }
        }

        // Si no encontramos ninguna pregunta disponible, mostramos un mensaje de error
        die("No hay más preguntas disponibles");
    }

    private function traerUnaPreguntaAleatoriaQueSeHayaVisto($cant_veces_vistas, $idUsuario)
    {
        $consulta = "SELECT P.*
                     FROM Pregunta P
                     LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = $idUsuario
                     WHERE PV.veces_entregadas = $cant_veces_vistas
                     ORDER BY RAND()
                     LIMIT 1";

        return $this->database->query($consulta);
    }


    private function contarCantidadDePreguntasVistas($cant_veces_vistas, $idUsuario)
    {
        $consultaVerificar = "SELECT COUNT(*) AS total
                          FROM Pregunta P
                          LEFT JOIN PreguntaVistas PV ON P.id = PV.id_pregunta AND PV.id_usuario = '$idUsuario'
                          WHERE PV.veces_entregadas =  $cant_veces_vistas ";

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
        return $totalPreguntasDisponibles;
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

        if(!$this->estaRegistrado($idPregunta,$idUsuario)){

            $consulta = " 
            INSERT INTO PreguntaVistas (id_usuario, id_pregunta) VALUES (?, ?);
            ";

            $stmt = $this->database->prepare($consulta);
            if (!$stmt) {die("Error en la preparación de la consulta: " . $this->database->error);}

            $stmt->bind_param("ii", $idUsuario,$idPregunta);
            if (!$stmt->execute()) {die("Error al ejecutar la consulta: " . $stmt->error);}
        }

    }

    private function estaRegistrado($idPregunta, $idUsuario)
    {
        // Consulta para verificar si ya existe un registro en la tabla PreguntaVistas para la pregunta y el usuario especificados
        $consulta = "SELECT COUNT(*) AS total FROM PreguntaVistas 
                    WHERE id_pregunta = ? AND id_usuario = ?";

        // Preparar la consulta
        $stmt = $this->database->prepare($consulta);
        if (!$stmt) {die("Error en la preparación de la consulta: " . $this->database->error);}

        // Asignar los parámetros y ejecutar la consulta
        $stmt->bind_param("ii", $idPregunta, $idUsuario);
        if (!$stmt->execute()) {die("Error al ejecutar la consulta: " . $stmt->error);}

        // Obtener el resultado
        $result = $stmt->get_result();

        // Obtener el valor del total de registros
        $total_registros = $result->fetch_assoc()['total'];

        // Devolver true si ya está registrado, false si no
        return ($total_registros > 0);
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
    public function sumarVecesEntregadasUnaPreguntaAUnUsuario($id_pregunta, $user_id) {
        $consulta = "UPDATE PreguntaVistas SET veces_entregadas = veces_entregadas + 1 
                WHERE id_usuario = ? AND id_pregunta = ?";

        // Ejecutar la consulta preparada
        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("ii", $user_id, $id_pregunta);
        $stmt->execute();
    }

    public function sumarEnPreguntaVistaVecesAcertadasPorUnUsuario($id_pregunta, $user_id) {

        $consulta = "UPDATE PreguntaVistas SET veces_acertadas = veces_acertadas + 1
                      WHERE id_pregunta = ? AND id_usuario = ?";

        // Ejecutar la consulta preparada
        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("ii", $id_pregunta, $user_id);
        $stmt->execute();

    }




}
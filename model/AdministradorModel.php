<?php

class AdministradorModel extends BaseModel
{
    public function __construct($database)
    {
        parent::__construct($database);
    }

    public function getPorcentajeRespuestasCorrectasPorUsuario()
    {
        $consulta = "SELECT u.username, 
                    (SUM(CASE WHEN r.es_correcta = 1 THEN 1 ELSE 0 END) / COUNT(r.id)) * 100 AS porcentaje_correctas
             FROM Usuarios u
             JOIN Respuesta r ON u.id = r.id
             GROUP BY u.username";

        $resultConsulta = $this->database->executeAndReturn($consulta);

        $dataPorcentajeCorrectas = [];
        if ($resultConsulta && $resultConsulta->num_rows > 0) {
            while ($row = $resultConsulta->fetch_assoc()) {
                $dataPorcentajeCorrectas[] = [
                    'username' => $row['username'],
                    'porcentaje' => $row['porcentaje_correctas']
                ];
            }
        } else {
            die("No se pudo obtener el porcentaje de respuestas correctas por usuario");
        }

        return $dataPorcentajeCorrectas;
    }

    public function getPorcentajeRespuestasCorrectasPorUsuarioPorDia($userId) {
        $sql = "
            SELECT 
                DATE(r.fecha) as dia,
                (SUM(CASE WHEN r.es_correcta = TRUE THEN 1 ELSE 0 END) / COUNT(*)) * 100 as porcentaje_correctas
            FROM Respuesta r
            JOIN Pregunta p ON r.id_pregunta = p.id
            WHERE p.usuario_creador = :userId
            GROUP BY DATE(r.fecha)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCantidadesDeUsuariosPorPais()
    {
        $consulta = "SELECT pais, COUNT(*) AS cantidad_usuarios
                        FROM usuarios
                        WHERE habilitado = 1
                        GROUP BY pais
                        ORDER BY cantidad_usuarios DESC;
                    ";
        $resultConsulta = $this->database->executeAndReturn($consulta);
        //$dataSexoCantidad = $this->inicializarSexoCantidad();
        $dataPaisCantidad = $this->llenarConCantidadesALosPaises($resultConsulta);
        return $this->retornarArrayParaQueSeSeVeanLosDatosPorPais($dataPaisCantidad);
    }

    public function getCantidadesDeUsuariosPorGrupoDeEdad()
    {
        $consulta = "SELECT 
                        CASE
                            WHEN YEAR(CURDATE()) - anio_nacimiento < 18 THEN 'menores'
                            WHEN YEAR(CURDATE()) - anio_nacimiento BETWEEN 18 AND 59 THEN 'medio'
                            ELSE 'jubilados'
                        END AS grupo_edad,
                        COUNT(*) AS cantidad
                    FROM Usuarios
                    GROUP BY grupo_edad;";

        $resultConsulta = $this->database->executeAndReturn($consulta);
        $dataGrupoCantidad = $this->inicializarGrupoCantidad();
        $dataGrupoCantidad = $this->llenarConCantidadesALosGrupos($resultConsulta, $dataGrupoCantidad);
        return $this->retornarArrayParaQueSeSeVeanLosDatosPorGrupos($dataGrupoCantidad);
    }

    public function getCantidadesDeUsuariosPorSexo()
    {
        $consulta = "SELECT  sexo , 
                            COUNT(*) AS cantidad 
                        FROM Usuarios 
                        WHERE sexo IN ('M','F','X')
                        GROUP BY sexo
                        
                    ";
        $resultConsulta = $this->database->executeAndReturn($consulta);
        $dataSexoCantidad = $this->inicializarSexoCantidad();
        $dataSexoCantidad = $this->llenarConCantidadesALosSexos($resultConsulta, $dataSexoCantidad);
        return $this->retornarArrayParaQueSeSeVeanLosDatosPorSexo($dataSexoCantidad);
    }

    public function getCantidadDeJugadores()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_jugadores
                        FROM Usuarios
                        INNER JOIN Usuario_Rol ON Usuarios.id = Usuario_Rol.id_usuario
                        INNER JOIN Rol ON Usuario_Rol.id_rol = Rol.id
                        WHERE Rol.nombre = 'Jugador'  ";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_jugadores'];
        } else {
            die("No se conto la cantidad de jugadores");
        }
    }

    public function getCantidadDeJugadoresPdf()
    {//Cuento solo a los jugadores que no sean admin y editor
        $consulta = "SELECT COUNT(*) AS cantidad_jugadores
                        FROM Usuarios
                        INNER JOIN Usuario_Rol ON Usuarios.id = Usuario_Rol.id_usuario
                        INNER JOIN Rol ON Usuario_Rol.id_rol = Rol.id
                        WHERE Rol.nombre = 'Jugador' ";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_jugadores'];
        } else {
            die("No se conto la cantidad de jugadores");
        }
    }

    public function getCantidadDePartidasJugadas()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_partidas FROM Partida WHERE fecha >= CURDATE() AND fecha < CURDATE() + INTERVAL 1 DAY";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_partidas'];
        } else {
            die("No se conto la cantidad de partidas");
        }
    }

    public function getCantidadDePartidasJugadasPdf()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_partidas FROM Partida";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_partidas'];
        } else {
            die("No se conto la cantidad de partidas");
        }
    }

    public function getCantidadDePreguntasActivas()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_preguntas FROM Pregunta WHERE activa = 1";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_preguntas'];
        } else {
            die("No se conto la cantidad de preguntas");
        }
    }

    public function getCantidadDePreguntasCreadasActivas()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_preguntas_creadas FROM Pregunta 
                    WHERE fecha_creacion >= CURDATE() AND fecha_creacion < CURDATE() + INTERVAL 1 DAY";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_preguntas_creadas'];
        } else {
            die("No se conto la cantidad de preguntas creadas");
        }
    }

    public function getCantidadDePreguntasCreadasActivasPdf()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_preguntas_creadas FROM Pregunta 
                    WHERE activa = 1 AND usuario_creador is not null";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_preguntas_creadas'];
        } else {
            die("No se conto la cantidad de preguntas creadas");
        }
    }

    public function getCantidadDePartidasJugadasPorPeriodo($timeframe)
    {
        switch ($timeframe) {
            case 'day':
                $consulta = "SELECT COUNT(*) AS cantidad_partidas FROM Partida WHERE fecha >= CURDATE() AND fecha < CURDATE() + INTERVAL 1 DAY";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) AS cantidad_partidas FROM Partida WHERE YEARWEEK(fecha) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) AS cantidad_partidas FROM Partida WHERE MONTH(fecha) = MONTH(CURDATE())";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) AS cantidad_partidas FROM Partida WHERE YEAR(fecha) = YEAR(CURDATE())";
                break;
            default:
                die("No se pudo obtener la cantidad de partidas jugadas por periodo");

        }
        $result = $this->database->executeAndReturn($consulta);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_partidas'];
        } else {
            die("No se pudo obtener la cantidad de partidas jugadas por periodo");
        }
    }

    public function obtenerLasCantidadesDePreguntasCredasPorDia($fechas): array
    {
        $fechas_string = "'" . implode("','", $fechas) . "'";

        $consulta = "SELECT  DATE(fecha_creacion) AS fecha, 
                            COUNT(*) AS cantidad 
                        FROM Pregunta 
                        WHERE DATE(fecha_creacion) IN ($fechas_string)
                        GROUP BY DATE(fecha_creacion)
                        ORDER BY fecha;
                    ";
        $resultConsulta = $this->database->executeAndReturn($consulta);
        $dataFechaCantidad = $this->inicializarFechaCantidadDeLosUltimosSieteDias($fechas);
        $dataFechaCantidad = $this->llenarConCantidadesALasFechas($resultConsulta, $dataFechaCantidad);
        return $this->retornarArrayParaQueSeSeVeanLosDatosPorDia($dataFechaCantidad);
    }

    public function obtenerLasCantidadesDeUsuariosNuevosPorDia($fechas): array
    {
        $fechas_string = "'" . implode("','", $fechas) . "'";

        $consulta = "SELECT  DATE(fecha_registro) AS fecha, 
                            COUNT(*) AS cantidad 
                        FROM Usuarios 
                        WHERE DATE(fecha_registro) IN ($fechas_string)
                        GROUP BY DATE(fecha_registro)
                        ORDER BY fecha;
                    ";
        $resultConsulta = $this->database->executeAndReturn($consulta);
        $dataFechaCantidad = $this->inicializarFechaCantidadDeLosUltimosSieteDias($fechas);
        $dataFechaCantidad = $this->llenarConCantidadesALasFechas($resultConsulta, $dataFechaCantidad);
        return $this->retornarArrayParaQueSeSeVeanLosDatosPorDia($dataFechaCantidad);
    }

    public function obtenerLasCantidadesDePartidasPorDia($fechas): array
    {
        $fechas_string = "'" . implode("','", $fechas) . "'";

        $consulta = "SELECT  DATE(fecha) AS fecha, 
                            COUNT(*) AS cantidad 
                        FROM Partida 
                        WHERE DATE(fecha) IN ($fechas_string)
                        GROUP BY DATE(fecha)
                        ORDER BY fecha;
                    ";
        $resultConsulta = $this->database->executeAndReturn($consulta);
        $dataFechaCantidad = $this->inicializarFechaCantidadDeLosUltimosSieteDias($fechas);
        $dataFechaCantidad = $this->llenarConCantidadesALasFechas($resultConsulta, $dataFechaCantidad);
        return $this->retornarArrayParaQueSeSeVeanLosDatosPorDia($dataFechaCantidad);
    }

    public function obtenerPreguntasActivasPorPeriodo($timeframe)
    {
        $currentDate = date('Y-m-d'); // Obtener la fecha actual en formato YYYY-MM-DD

        switch ($timeframe) {
            case 'day':
                $consulta = "SELECT COUNT(*) AS cantidad_preguntas 
                 FROM Pregunta 
                 WHERE (
                     (fecha_comienzoActivo <= '$currentDate' AND (fecha_finActivo IS NULL OR fecha_finActivo >= '$currentDate'))
                     OR
                     (fecha_comienzoActivo <= '$currentDate' AND fecha_finActivo >= '$currentDate')
                 )";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) AS cantidad_preguntas 
                 FROM Pregunta 
                 WHERE (
                     YEARWEEK(fecha_comienzoActivo) = YEARWEEK('$currentDate')
                     OR
                     (fecha_comienzoActivo <= '$currentDate' AND fecha_comienzoActivo > DATE_SUB('$currentDate', INTERVAL 1 WEEK))
                 )";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) AS cantidad_preguntas 
                 FROM Pregunta 
                 WHERE (
                     MONTH(fecha_comienzoActivo) = MONTH('$currentDate')
                     OR
                     (fecha_comienzoActivo <= '$currentDate' AND fecha_comienzoActivo > DATE_SUB('$currentDate', INTERVAL 1 MONTH))
                 )";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) AS cantidad_preguntas 
                 FROM Pregunta 
                 WHERE (
                     (YEAR(fecha_comienzoActivo) = YEAR('$currentDate') - 1 AND fecha_comienzoActivo > DATE_SUB('$currentDate', INTERVAL 1 YEAR))
                     OR
                     (fecha_comienzoActivo <= '$currentDate' AND (fecha_finActivo IS NULL OR fecha_finActivo >= '$currentDate'))
                 )";
                break;
            default:
                die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }

        $result = $this->database->executeAndReturn($consulta);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_preguntas']; // Ajustado el índice al nombre correcto del alias
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerPreguntasCreadasPorPeriodo($timeframe)
    {
        switch ($timeframe) {
            case 'day':
                $consulta = "SELECT COUNT(*) AS cantidad_preguntasCreadas FROM Pregunta WHERE fecha_creacion >= CURDATE() AND fecha_creacion < CURDATE() + INTERVAL 1 DAY";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) AS cantidad_preguntasCreadas FROM Pregunta WHERE YEARWEEK(fecha_creacion) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) AS cantidad_preguntasCreadas FROM Pregunta WHERE MONTH(fecha_creacion) = MONTH(CURDATE())";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) AS cantidad_preguntasCreadas FROM Pregunta WHERE YEAR(fecha_creacion) = YEAR(CURDATE())";
                break;
            default:
                die("No se pudo obtener la cantidad de partidas jugadas por periodo");

        }
        $result = $this->database->executeAndReturn($consulta);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_preguntasCreadas'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosHombresRegistradosPorPeriodo($timeframe)
    {
        switch ($timeframe) {
            case 'day':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_hombres 
                 FROM Usuarios  WHERE sexo = 'M' 
                 AND fecha_registro >= CURDATE() 
                 AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_hombres 
                 FROM Usuarios 
                 WHERE sexo = 'M' 
                 AND YEARWEEK(fecha_registro) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_hombres FROM Usuarios WHERE sexo = 'M' AND YEAR(fecha_registro) = YEAR(CURDATE()) AND MONTH(fecha_registro) = MONTH(CURDATE())";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_hombres FROM Usuarios WHERE sexo = 'M' AND YEAR(fecha_registro) = YEAR(CURDATE())";
                break;
            default:
                die("No se pudo obtener la cantidad de usuarios hombres registrados por periodo");
        }
        $result = $this->database->executeAndReturn($consulta);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_hombres'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosMujeresRegistradosPorPeriodo($timeframe)
    {
        switch ($timeframe) {
            case 'day':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_mujeres FROM Usuarios WHERE sexo = 'F' AND fecha_registro >= CURDATE() AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_mujeres FROM Usuarios WHERE sexo = 'F' AND YEARWEEK(fecha_registro) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_mujeres 
                 FROM Usuarios 
                 WHERE sexo = 'F' 
                AND YEAR(fecha_registro) = YEAR(CURDATE())          
                 AND MONTH(fecha_registro) = MONTH(CURDATE())";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_mujeres 
                 FROM Usuarios 
                 WHERE sexo = 'F' 
                 AND YEAR(fecha_registro) = YEAR(CURDATE())";
                break;
            default:
                die("No se pudo obtener la cantidad de usuarios mujeres registrados por periodo");
        }
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_mujeres'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }

    }

    public function obtenerUsuariosDelDiaRegistrado()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_usuarios_hombres FROM Usuarios WHERE fecha_registro >= CURDATE() AND sexo = 'M' AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_hombres'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }
    public function obtenerCantidadDeUsuariosMasculinosRegistradosYValidadosPdf()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_usuarios_hombres FROM Usuarios WHERE sexo = 'M' ";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_hombres'];
        } else {
            die("No se pudo obtener la cantidad de usuarios hombres");
        }
    }

    public function obtenerUsuariosDelDiaMujeresRegistrado()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_usuarios_mujeres FROM Usuarios WHERE fecha_registro >= CURDATE() AND sexo = 'F' AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_mujeres'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosMujeresRegistradosYValidadosPdf()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_usuarios_mujeres FROM Usuarios WHERE sexo = 'F' ";
        $result = $this->database->executeAndReturn($consulta);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_mujeres'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerResultadosDeUsuariosNoDecididosPorPeriodo($timeframe)
    {
        switch($timeframe) {
            case 'day':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_nodecididos FROM Usuarios WHERE sexo = 'X' AND fecha_registro >= CURDATE() AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_nodecididos FROM Usuarios WHERE sexo = 'X' AND YEARWEEK(fecha_registro) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_nodecididos 
                 FROM Usuarios 
                 WHERE sexo = 'X' 
                AND YEAR(fecha_registro) = YEAR(CURDATE())          
                 AND MONTH(fecha_registro) = MONTH(CURDATE())";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_nodecididos 
                 FROM Usuarios 
                 WHERE sexo = 'X' 
                 AND YEAR(fecha_registro) = YEAR(CURDATE())";
                break;
            default:
                die("No se pudo obtener la cantidad de usuarios mujeres registrados por periodo");
        }
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_nodecididos'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosNoDecididosRegistradosDelDia()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_usuarios_nodecididos from Usuarios where fecha_registro >= CURDATE() AND sexo = 'X' AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_nodecididos'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosDeArgentinaPorPeriodo($timeframe)
    {
        switch ($timeframe){
            case 'day':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_argentinos FROM Usuarios WHERE pais = 'Argentina' AND fecha_registro >= CURDATE() AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_argentinos FROM Usuarios WHERE pais = 'Argentina' AND YEARWEEK(fecha_registro) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_argentinos 
                 FROM Usuarios 
                 WHERE pais = 'Argentina' 
                AND YEAR(fecha_registro) = YEAR(CURDATE())          
                 AND MONTH(fecha_registro) = MONTH(CURDATE())";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) AS cantidad_usuarios_argentinos 
                 FROM Usuarios 
                 WHERE pais = 'Argentina' 
                 AND YEAR(fecha_registro) = YEAR(CURDATE())";
                break;
        }
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_argentinos'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosArgentinosPorDia()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_usuarios_argentinos FROM Usuarios WHERE pais = 'Argentina' AND fecha_registro >= CURDATE() AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_argentinos'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosPorRangoDeEdadDia()
    {
        $consulta = "SELECT COUNT(*) as cantidad_usaurios_menores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento < 18 AND fecha_registro < CURDATE() + INTERVAL 1 DAY ";
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usaurios_menores'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosPorRangoDeEdadPeriodoMenores($timeframe)
    {
        switch($timeframe) {
            case 'day':
            $consulta = "SELECT COUNT(*) as cantidad_usaurios_menores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento < 18 AND fecha_registro >= CURDATE() AND fecha_registro < CURDATE() + INTERVAL 1 DAY ";
        break;
        case 'week':
            $consulta = "SELECT COUNT(*) as cantidad_usuarios_menores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento < 18 AND YEARWEEK(fecha_registro) = YEARWEEK(CURDATE())";
        break;
        case 'month':
            $consulta = "SELECT COUNT(*) as cantidad_usuarios_menores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento < 18 AND YEAR(fecha_registro) = YEAR(CURDATE()) AND MONTH(fecha_registro) = MONTH(CURDATE())";
        break;
        case 'year':
            $consulta = "SELECT COUNT(*) as cantidad_usuarios_menores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento < 18 AND YEAR(fecha_registro) = YEAR(CURDATE())";
            break;

        }
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_menores'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }

    }

    public function obtenerUsuariosPorRangoDeEdadPeriodoMedio($timeframe)
    {
        switch($timeframe) {
            case 'day':
                $consulta = "SELECT COUNT(*) as cantidad_usuarios_medio From Usuarios WHERE YEAR(NOW()) - anio_nacimiento > 18 AND YEAR(NOW()) - anio_nacimiento < 60 AND fecha_registro >= CURDATE() AND fecha_registro < CURDATE() + INTERVAL 1 DAY ";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) as cantidad_usuarios_medio From Usuarios WHERE YEAR(NOW()) - anio_nacimiento > 18 AND YEAR(NOW()) - anio_nacimiento < 60 AND YEARWEEK(fecha_registro) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) as cantidad_usuarios_medio From Usuarios WHERE YEAR(NOW()) - anio_nacimiento > 18 AND YEAR(NOW()) - anio_nacimiento < 60 AND YEAR(fecha_registro) = YEAR(CURDATE()) AND MONTH(fecha_registro) = MONTH(CURDATE())";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) as cantidad_usuarios_medio From Usuarios WHERE YEAR(NOW()) - anio_nacimiento > 18 AND YEAR(NOW()) - anio_nacimiento < 60 AND YEAR(fecha_registro) = YEAR(CURDATE())";
                break;

        }
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_medio'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }

    }

    public function obtenerUsuariosPorRangoDeEdadPeriodoMayores($timeframe)
    {
        switch($timeframe) {
            case 'day':
                $consulta = "SELECT COUNT(*) as cantidad_usuarios_mayores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento > 60 AND fecha_registro >= CURDATE() AND fecha_registro < CURDATE() + INTERVAL 1 DAY ";
                break;
            case 'week':
                $consulta = "SELECT COUNT(*) as cantidad_usuarios_mayores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento > 60 AND YEARWEEK(fecha_registro) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $consulta = "SELECT COUNT(*) as cantidad_usuarios_mayores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento > 60 AND YEAR(fecha_registro) = YEAR(CURDATE()) AND MONTH(fecha_registro) = MONTH(CURDATE())";
                break;
            case 'year':
                $consulta = "SELECT COUNT(*) as cantidad_usuarios_mayores From Usuarios WHERE YEAR(NOW()) - anio_nacimiento > 60 AND YEAR(fecha_registro) = YEAR(CURDATE())";
                break;

        }
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_mayores'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }

    }


    private function inicializarFechaCantidadDeLosUltimosSieteDias($fechas): array
    {//[21-06]= 0
        $dataFechaCantidad = [];

        for ($i = 0; $i <= 6; $i++) {
            $dataFechaCantidad[date('d-m', strtotime($fechas[$i]))] = 0;
        }
        return $dataFechaCantidad;
    }

    private function inicializarSexoCantidad(): array
    {
        $dataSexoCantidad['M'] = 0;
        $dataSexoCantidad['F'] = 0;
        $dataSexoCantidad['X'] = 0;
        return $dataSexoCantidad;
    }

    private function llenarConCantidadesALasFechas($result, $dataFechaCantidad): array
    {
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dataFechaCantidad[date('d-m', strtotime($row['fecha']))] = $row['cantidad'];
            }
        } else {
            die("No hay fecha y cantidad para hacer el grafico");
        }
        return $dataFechaCantidad;
    }

    private function retornarArrayParaQueSeSeVeanLosDatosPorDia($data): array
    {
        $final_array = [];
        foreach ($data as $fechaIndex => $item) {
            $final_array[] = [
                'fecha' => $fechaIndex,
                'cantidad' => $item
            ];
        }
        return $final_array;
    }

    private function llenarConCantidadesALosSexos($result, $dataSexoCantidad): array
    {
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dataSexoCantidad[$row['sexo']] = $row['cantidad'];
            }
        } else {
            die("No hay sexo y cantidad para hacer el grafico");
        }
        return $dataSexoCantidad;
    }


    private function inicializarGrupoCantidad(): array
    {
        $dataGrupoCantidad['menores'] = 0;
        $dataGrupoCantidad['medio'] = 0;
        $dataGrupoCantidad['jubilados'] = 0;
        return $dataGrupoCantidad;
    }

    private function llenarConCantidadesALosGrupos($result, $dataGrupoCantidad): array
    {
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dataGrupoCantidad[$row['grupo_edad']] = $row['cantidad'];
            }
        } else {
            die("No hay grupoEdad y cantidad para hacer el grafico");
        }
        return $dataGrupoCantidad;
    }

    private function retornarArrayParaQueSeSeVeanLosDatosPorSexo($dataSexoCantidad): array
    {
        $final_array = [];
        foreach ($dataSexoCantidad as $sexoIndex => $itemCantidad) {
            $final_array[] = [
                'sexo' => $sexoIndex,
                'cantidad' => $itemCantidad
            ];
        }
        return $final_array;
    }

    private function retornarArrayParaQueSeSeVeanLosDatosPorGrupos($dataGrupoCantidad): array
    {
        $final_array = [];
        foreach ($dataGrupoCantidad as $grupoIndex => $itemCantidad) {
            $final_array[] = [
                'grupo_edad' => $grupoIndex,
                'cantidad' => $itemCantidad
            ];
        }
        return $final_array;
    }

    private function llenarConCantidadesALosPaises($result): array
    {
        $dataPaisCantidad = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dataPaisCantidad[$row['pais']] = $row['cantidad_usuarios'];
            }
        } else {
            die("No hay pais y cantidad para hacer el grafico");
        }
        return $dataPaisCantidad;
    }

    private function retornarArrayParaQueSeSeVeanLosDatosPorPais($dataPaisCantidad)
    {
        $final_array = [];
        foreach ($dataPaisCantidad as $paisIndex => $itemCantidad) {
            $final_array[] = [
                'pais' => $paisIndex,
                'cant_usuarios' => $itemCantidad
            ];
        }
        return $final_array;
    }
}
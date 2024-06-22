<?php

class AdministradorModel extends BaseModel
{
    protected $grafica;

    public function __construct($database, $grafica)
    {
        parent::__construct($database);
        $this->grafica = $grafica;
    }

    public function crearGrafico($datos) { $this->grafica->graficar($datos); }
    public function graficarCantidadDeUsuariosPorSexo($datos) { $this->grafica->usuariosPorSexo($datos); }
    public function graficarCantidadDeUsuariosPorGrupo($datos) { $this->grafica->usuariosPorGrupo($datos); }
    public function graficarCantidadDeUsuariosPorPais($datos) { $this->grafica->usuariosPorPais($datos); }
    public function graficarPorcentajeDeCorrectasPorUsuarios($datos) { $this->grafica->porcentajeUsuarioCorrectas($datos); }

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
        $consulta = "SELECT COUNT(*) AS cantidad_jugadores FROM Usuarios ";
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

<<<<<<< Updated upstream
=======
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
        switch($timeframe) {
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
        if($result && $result->num_rows > 0) {
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
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_hombres'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }

    public function obtenerUsuariosDelDiaMujeresRegistrado()
    {
        $consulta = "SELECT COUNT(*) AS cantidad_usuarios_mujeres FROM Usuarios WHERE fecha_registro >= CURDATE() AND sexo = 'F' AND fecha_registro < CURDATE() + INTERVAL 1 DAY";
        $result = $this->database->executeAndReturn($consulta);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_usuarios_mujeres'];
        } else {
            die("No se pudo obtener la cantidad de preguntas activas por periodo");
        }
    }




>>>>>>> Stashed changes
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

    private function llenarConCantidadesALosSexos($result, $dataSexoCantidad):array
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

    private function retornarArrayParaQueSeSeVeanLosDatosPorSexo($dataSexoCantidad):array
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
}
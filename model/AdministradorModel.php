<?php
class AdministradorModel extends BaseModel
{
    protected $grafica;
    public function __construct($database,$grafica)
    {
        parent::__construct($database);
        $this->grafica = $grafica;
    }

    public function crearGrafico($arrayDeDatos)
    {
        $this->grafica->graficar($arrayDeDatos);
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
                    WHERE usuario_creador is not null AND activa = 1 ";
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

        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad_partidas'];
        } else {
            die("No se pudo obtener la cantidad de partidas jugadas por periodo");
        }
    }

    public function obtenerLasCantidadesDePreguntasCredasPorDia($fechas) :array
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

    private function inicializarFechaCantidadDeLosUltimosSieteDias($fechas)
    {
        $dataFechaCantidad = [];

        for ($i = 0; $i <= 6; $i++) {
            $dataFechaCantidad[date('d-m', strtotime($fechas[$i]))] = 0;
        }
        return $dataFechaCantidad;
    }

    private function llenarConCantidadesALasFechas($result,$dataFechaCantidad)
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

    private function retornarArrayParaQueSeSeVeanLosDatosPorDia($data):array
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


}
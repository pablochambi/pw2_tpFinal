<?php
class AdministradorModel extends BaseModel
{
    protected $grafica;
    public function __construct($database,$grafica)
    {
        parent::__construct($database);
        $this->grafica = $grafica;
    }

    public function crearGrafico()
    {
        $this->grafica->graficar();
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
        $consulta = "SELECT COUNT(*) AS cantidad_partidas FROM Partida ";
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

    public function obtenerFechaDeHoy()
    {
        $query = "SELECT CURDATE() as fecha";
        $result = $this->database->executeAndReturn($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['fecha'];
        } else {
            die("No se pudo obtener la fecha de hoy");
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



}
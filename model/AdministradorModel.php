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



}
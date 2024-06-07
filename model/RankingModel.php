<?php

class RankingModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerRanking()

    {
        $query = "SELECT U.username AS username, MAX(P.puntaje) AS puntaje 
                  FROM Partida P 
                  JOIN Usuarios U ON U.id = P.id_usuario 
                  GROUP BY username 
                  ORDER BY puntaje DESC 
                  LIMIT 10";

        $result = $this->database->executeAndReturn($query);

        if ($result === false)
            die('Error en la consulta: ' . $this->database->error);

        $ranking = [];

        while ($row = $result->fetch_assoc()) {
            $ranking[] = $row;
        }

        return $ranking;
    }

}
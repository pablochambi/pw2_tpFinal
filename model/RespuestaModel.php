<?php

class RespuestaModel {

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerRespuesta(){
        $respuesta = $this->database->query("SELECT * FROM respuesta where id_pregunta = 1");
        return $respuesta;
    }
}
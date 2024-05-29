<?php

class PreguntaModel {

    private $database;
    private $preguntaFacil;
    private $preguntaMedia;
    private $preguntaDificil;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPregunta(){
        $pregunta = $this->database->query("SELECT * FROM pregunta");
        return $pregunta;
    }



}
<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function traerPreguntaAleatoria() {
        $query = "SELECT * FROM pregunta ORDER BY RAND() LIMIT 1";
        $pregunta = $this->database->query($query);
        return $pregunta;
    }

    public function traerRespuestasDesordenadas($idPregunta) {
        $query = "SELECT texto
                  FROM respuesta
                  WHERE id_pregunta = $idPregunta";
        $respuestas = $this->database->query($query);


        shuffle($respuestas);
        return $respuestas;
    }

    public function esRespuestaCorrecta($textoRespuesta, $idPregunta)
    {
        $query = "Select es_correcta from respuesta where texto = '$textoRespuesta' and id_pregunta = $idPregunta";

       $result = $this->database->query($query);

        if($result[0]['es_correcta'] == 1){
            return true;
        }else{
            return false;
        }


    }

}
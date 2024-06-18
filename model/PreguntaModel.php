<?php

class PreguntaModel Extends BaseModel{


    private $preguntaFacil;
    private $preguntaMedia;
    private $preguntaDificil;

    public function __construct($database){
        parent:: __construct($database);
    }

    public function obtenerPregunta(){
        $pregunta = $this->database->query("SELECT * FROM Pregunta");
        return $pregunta;
    }

    public function crearPreguntaSugerida($texto, $id_categoria, $usuario_creador)
    {
        $query = "INSERT INTO Pregunta (texto, id_categoria, nivel, usuario_creador, revisada, valida) 
              VALUES (?, ?, 0.0, ?, false, 0)";

        $stmt = $this->database->prepare($query);
        $stmt->bind_param('sii', $texto, $id_categoria, $usuario_creador);

        if ($stmt->execute())
            $idPregunta = $stmt->insert_id;
            return $idPregunta;
    }

    public function getCategorias()
    {
        $categorias = $this->database->query("SELECT id, nombre FROM Categoria");

        return $categorias;
    }



}
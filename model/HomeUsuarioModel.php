<?php
class HomeUsuarioModel extends BaseModel
{
    public function __construct($database)
    {
       parent::__construct($database);
    }

    public function sumarPuntajeAcumulado($idUsuario)
    {

        $consulta = "SELECT SUM(puntaje) AS puntaje_acumulado FROM Partida WHERE id_usuario = ?";

        $stmt = $this->database->prepare($consulta);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if($row['puntaje_acumulado'] == null){
            return 0;
        }

        return $row['puntaje_acumulado'];
    }

}

<?php
class HomeUsuarioModel extends BaseModel
{
    public function __construct($database)
    {
       parent::__construct($database);
    }

    public function verificarDeQueRolEsElUsuario($idUsuario)
    {
        $consulta = "
        SELECT r.nombre AS rol
        FROM Usuarios u
        INNER JOIN Usuario_Rol ur ON u.id = ur.id_usuario
        INNER JOIN Rol r ON ur.id_rol = r.id
        WHERE u.id = ?;
    ";

        $stmt = $this->prepararConsulta($consulta);

        $this->unirParametros($stmt,"i", $idUsuario);

        return $this->obtenerResultados($stmt);
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

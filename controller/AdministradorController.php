<?php
class AdministradorController extends BaseController
{
    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }
    public function get()
    {
        $idUsuario = $this->checkSessionYTraerIdUsuario();
        $datos = $this->datosAEnviarALaVistaAdministrador($idUsuario);
        $this->presenter->render('view/vistaAdministrador/administrador.mustache', $datos);
    }

    public function generarGrafico()
    {
        $this->model->crearGrafico();
    }

    private function datosAEnviarALaVistaAdministrador($idUsuario): array
    {
        $rol = $this->verificarDeQueRolEsElUsuario($idUsuario);
        $cantJugadores = $this->model->getCantidadDeJugadores();
        $cantPartidasJugadas = $this->model->getCantidadDePartidasJugadas();
        $cantPreguntas = $this->model->getCantidadDePreguntasActivas();
        $cantPreguntasCreadas = $this->model->getCantidadDePreguntasCreadasActivas();

        return [
            'rol' => $rol['rol'],
            'cant_jugadores' => $cantJugadores,
            'cantidad_partidas' => $cantPartidasJugadas,
            'cantidad_preguntas' => $cantPreguntas,
            'cantidad_preguntas_creadas' => $cantPreguntasCreadas];
    }

}
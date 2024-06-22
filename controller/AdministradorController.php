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

    public function graficoDePreguntasCreadas()
    {
        $arrayDefechas = $this->obtenerLosUltimosSieteDiasDeLaSemanaHastaHoy();
        $arrayDeDatos = $this->model->obtenerLasCantidadesDePreguntasCredasPorDia($arrayDefechas);
        $this->model->crearGrafico($arrayDeDatos);
    }
    private function obtenerLosUltimosSieteDiasDeLaSemanaHastaHoy():array
    {
        $fechas = array();
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $hoy = strtotime('today');

        // Generar las fechas de los últimos 6 días hasta hoy
        for ($i = 6; $i >= 0; $i--) {
            $fecha = date('Y-m-d', strtotime("-$i days", $hoy));
            $fechas[] = $fecha;
        }

        return $fechas;
    }


    public function manejoDeCambioDeFechaCantPartida()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe'] ?? 'day';

            $cantidad_partidas = $this->model->getCantidadDePartidasJugadasPorPeriodo($timeframe);

            header('Content-Type: application/json');
            echo json_encode(['cantidad_partidas' => $cantidad_partidas]);
            exit();
        }
    }
    private function datosAEnviarALaVistaAdministrador($idUsuario): array
    {
        $rol = $this->verificarDeQueRolEsElUsuario($idUsuario);
        $cantJugadores = $this->model->getCantidadDeJugadores();
        $cantPartidasJugadas = $this->model->getCantidadDePartidasJugadas();
        $cantPreguntas = $this->model->getCantidadDePreguntasActivas();
        $cantPreguntasCreadas = $this->model->getCantidadDePreguntasCreadasActivas();
        $timeframe = $_GET['timeframe'] ?? 'day';

        $cantidad_partidas = $this->model->getCantidadDePartidasJugadasPorPeriodo($timeframe);


        return [
            'rol' => $rol['rol'],
            'cant_jugadores' => $cantJugadores,
            'cantidad_partidas' => $cantPartidasJugadas,
            'cantidad_preguntas' => $cantPreguntas,
            'cantidad_partidasPeriodo' => $cantidad_partidas,
            'cantidad_preguntas_creadas' => $cantPreguntasCreadas];
    }


}
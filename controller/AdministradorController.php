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

    public function graficoDeUsuariosNuevos()
    {
        $arrayDefechas = $this->obtenerLosUltimosSieteDiasDeLaSemanaHastaHoy();
        $arrayDeDatos = $this->model->obtenerLasCantidadesDeUsuariosNuevosPorDia($arrayDefechas);
        $this->model->crearGrafico($arrayDeDatos);
    }

    public function graficoDePartidas()
    {
        $arrayDefechas = $this->obtenerLosUltimosSieteDiasDeLaSemanaHastaHoy();
        $arrayDeDatos = $this->model->obtenerLasCantidadesDePartidasPorDia($arrayDefechas);
        $this->model->crearGrafico($arrayDeDatos);
    }
    public function graficoDeUsuariosPorSexo()
    {
        $arrayDeDatos = $this->model->getCantidadesDeUsuariosPorSexo();
        $this->model->graficarCantidadDeUsuariosPorSexo($arrayDeDatos);
    }
    public function graficoDeUsuariosPorGrupo()
    {
        $arrayDeDatos = $this->model->getCantidadesDeUsuariosPorGrupoDeEdad();
        $this->model->graficarCantidadDeUsuariosPorGrupo($arrayDeDatos);
    }
    public function graficoDeUsuariosPorPais()
    {
        $arrayDeDatos = $this->model->getCantidadesDeUsuariosPorPais();
        $this->model->graficarCantidadDeUsuariosPorPais($arrayDeDatos);
    }


//graficoPorcentajeCorrectoUsuarios

    private function obtenerLosUltimosSieteDiasDeLaSemanaHastaHoy(): array
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
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe'] ?? 'day';

            $cantidad_partidas = $this->model->getCantidadDePartidasJugadasPorPeriodo($timeframe);

            header('Content-Type: application/json');
            echo json_encode(['cantidad_partidas' => $cantidad_partidas]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaPreguntaActiva()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe2 = $_GET['timeframe2'] ?? 'day';
            $cantidad_preguntasActivas = $this->model->obtenerPreguntasActivasPorPeriodo($timeframe2);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_preguntasActivas' => $cantidad_preguntasActivas]);
            exit();
        }
    }
    public function manejoDeCambioDeFechaPreguntaCreadas()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe3'] ?? 'day';
            $cantidad_preguntasCreadas = $this->model->obtenerPreguntasCreadasPorPeriodo($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_preguntasCreadas' => $cantidad_preguntasCreadas]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosHombres()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe4'] ?? 'day';
            $cantidad_usuariosHombres = $this->model->obtenerUsuariosHombresRegistradosPorPeriodo($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_masculinos' => $cantidad_usuariosHombres]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosMujeres()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe4'] ?? 'day';
            $cantidad_usuariosMujeres = $this->model->obtenerUsuariosMujeresRegistradosPorPeriodo($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_femeninos' => $cantidad_usuariosMujeres]);
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
        $cantidadUsuariosHombres = $this->model->obtenerUsuariosDelDiaRegistrado();
        $cantidad_partidas = $this->model->getCantidadDePartidasJugadasPorPeriodo($timeframe);
        $cantidadUsuariosMujeres = $this->model->obtenerUsuariosDelDiaMujeresRegistrado();

        return [
            'rol' => $rol['rol'],
            'cant_jugadores' => $cantJugadores,
            'cantidad_partidas' => $cantPartidasJugadas,
            'cantidad_preguntas' => $cantPreguntas,
            'cantidad_partidasPeriodo' => $cantidad_partidas,
            'cantidad_preguntas_creadas' => $cantPreguntasCreadas,
            'cantidad_usuarios_masculinos' => $cantidadUsuariosHombres,
            'cantidad_usuarios_femeninos' => $cantidadUsuariosMujeres];
    }
}
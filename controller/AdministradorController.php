<?php

class AdministradorController extends BaseController
{
    protected $pdfCreator;
    protected $mustache;
    protected $grafica;

    public function __construct($model, $presenter, $pdfCreator, $mustache, $grafica)
    {
        session_start();
        parent::__construct($model, $presenter);
        $this->pdfCreator = $pdfCreator;
        $this->mustache = $mustache;
        $this->grafica = $grafica;
    }

    public function get()
    {
        $this->checkSession();
        $rol = $this->verificarDeQueRolEsElUsuario($_SESSION["username"]['id']);
        if ($rol['rol'] != 'Administrador') {
            header('Location: /homeUsuario');
        }
        $idUsuario = $this->checkSessionYTraerIdUsuario();
        $datos = $this->datosAEnviarALaVistaAdministrador($idUsuario);
        $this->presenter->render('view/vistaAdministrador/administrador.mustache', $datos);
    }

    public function grafico()
    {
        $idGraf = $_GET['id'] ?? "";

        switch ($idGraf) {
            case 1:
                $this->graficoDePreguntasCreadas();
                break;//
            case 2:
                $this->graficoDeUsuariosNuevos();
                break;
            case 3:
                $this->graficoDePartidas();
                break;//
            case 4:
                $this->graficoDeUsuariosPorSexo();
                break;
            case 5:
                $this->graficoDeUsuariosPorGrupo();
                break;
            case 6:
                $this->graficoDeUsuariosPorPais();
                break;
            case 7:
                $this->graficoPorcentajeCorrectoUsuarios();
                break;
            default:
                die("No se envio un id reconocido");
        }
    }

    public function graficoDePreguntasCreadas()
    {
        $arrayDefechas = $this->obtenerLosUltimosSieteDiasDeLaSemanaHastaHoy();
        $arrayDeDatos = $this->model->obtenerLasCantidadesDePreguntasCredasPorDia($arrayDefechas);
        $this->grafica->preguntasCreadasPorDia($arrayDeDatos);
    }

    public function graficoDeUsuariosNuevos()
    {
        $arrayDefechas = $this->obtenerLosUltimosSieteDiasDeLaSemanaHastaHoy();
        $arrayDeDatos = $this->model->obtenerLasCantidadesDeUsuariosNuevosPorDia($arrayDefechas);
        $this->grafica->usuariosNuevosPorDia($arrayDeDatos);
    }

    public function graficoDePartidas()
    {
        $arrayDefechas = $this->obtenerLosUltimosSieteDiasDeLaSemanaHastaHoy();
        $arrayDeDatos = $this->model->obtenerLasCantidadesDePartidasPorDia($arrayDefechas);
        $this->grafica->partidasPorDia($arrayDeDatos);
    }

    public function graficoDeUsuariosPorSexo()
    {
        $arrayDeDatos = $this->model->getCantidadesDeUsuariosPorSexo();
        $this->grafica->usuariosPorSexo($arrayDeDatos);
    }

    public function graficoDeUsuariosPorGrupo()
    {
        $arrayDeDatos = $this->model->getCantidadesDeUsuariosPorGrupoDeEdad();
        $this->grafica->usuariosPorGrupo($arrayDeDatos);
    }

    public function graficoDeUsuariosPorPais()
    {
        $arrayDeDatos = $this->model->getCantidadesDeUsuariosPorPais();
        $this->grafica->usuariosPorPais($arrayDeDatos);
    }

    public function pdf()
    {
        $datos = $this->datosAEnviarALaVistaPdf();
        $html = $this->mustache->generateHtmlSimple('view/vistaPdf.mustache', $datos);
        $this->pdfCreator->crear($html);
    }

    public function graficoPorcentajeCorrectoUsuarios()
    {
        $arrayDeDatos = $this->model->getPorcentajeRespuestasCorrectasPorUsuario();
        $this->grafica->porcentajeUsuarioCorrectas($arrayDeDatos);
    }

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
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe2 = $_GET['timeframe2'] ?? 'day';
            $cantidad_preguntasActivas = $this->model->obtenerPreguntasActivasPorPeriodo($timeframe2);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_preguntasActivas' => $cantidad_preguntasActivas]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaPreguntaCreadas()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe3'] ?? 'day';
            $cantidad_preguntasCreadas = $this->model->obtenerPreguntasCreadasPorPeriodo($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_preguntasCreadas' => $cantidad_preguntasCreadas]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosHombres()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe4'] ?? 'day';
            $cantidad_usuariosHombres = $this->model->obtenerUsuariosHombresRegistradosPorPeriodo($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_masculinos' => $cantidad_usuariosHombres]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosMujeres()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe4'] ?? 'day';
            $cantidad_usuariosMujeres = $this->model->obtenerUsuariosMujeresRegistradosPorPeriodo($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_femeninos' => $cantidad_usuariosMujeres]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosNoDecididos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe4'] ?? 'day';
            $cantidad_usuariosNoDecididos = $this->model->obtenerResultadosDeUsuariosNoDecididosPorPeriodo($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_nodecididos' => $cantidad_usuariosNoDecididos]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosArgentinos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe5'] ?? 'day';
            $cantidad_usuariosArgentinos = $this->model->obtenerUsuariosDeArgentinaPorPeriodo($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_argentinos' => $cantidad_usuariosArgentinos]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosMayores()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe6'] ?? 'day';
            $cantidad_usuariosMayores = $this->model->obtenerUsuariosPorRangoDeEdadPeriodoMayores($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_mayores' => $cantidad_usuariosMayores]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosMedio()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe6'] ?? 'day';
            $cantidad_usuariosMayores = $this->model->obtenerUsuariosPorRangoDeEdadPeriodoMedio($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_medio' => $cantidad_usuariosMayores]);
            exit();
        }
    }

    public function manejoDeCambioDeFechaUsuariosMenores()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $timeframe = $_GET['timeframe6'] ?? 'day';
            $cantidad_usuariosMayores = $this->model->obtenerUsuariosPorRangoDeEdadPeriodoMenores($timeframe);
            header('Content-Type: application/json');
            echo json_encode(['cantidad_usuarios_menores' => $cantidad_usuariosMayores]);
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
        $cantidadUsuariosNodecididos = $this->model->obtenerUsuariosNoDecididosRegistradosDelDia();
        $cantidadUsuariosArgentinos = $this->model->obtenerUsuariosArgentinosPorDia();
        $datosPorcentajeCorrectas = $this->model->getPorcentajeRespuestasCorrectasPorUsuario();

        return [
            'rol' => $rol['rol'],
            'cant_jugadores' => $cantJugadores,
            'cantidad_partidas' => $cantPartidasJugadas,
            'cantidad_preguntas' => $cantPreguntas,
            'cantidad_partidasPeriodo' => $cantidad_partidas,
            'cantidad_preguntas_creadas' => $cantPreguntasCreadas,
            'cantidad_usuarios_masculinos' => $cantidadUsuariosHombres,
            'cantidad_usuarios_nodecididos' => $cantidadUsuariosNodecididos,
            'cantidad_usuarios_argentinos' => $cantidadUsuariosArgentinos,
            'cantidad_usuarios_femeninos' => $cantidadUsuariosMujeres,
            'porcentaje_correctas' => $datosPorcentajeCorrectas,];
    }

    private function datosAEnviarALaVistaPdf(): array
    {
        $cantJugadores = $this->model->getCantidadDeJugadoresPdf();
        $cantPartidasJugadas = $this->model->getCantidadDePartidasJugadasPdf();
        $cantPreguntas = $this->model->getCantidadDePreguntasActivas();
        $cantPreguntasCreadas = $this->model->getCantidadDePreguntasCreadasActivasPdf();
        $timeframe = $_GET['timeframe'] ?? 'day';

        $usuariosPorSexo = $this->model->getCantidadesDeUsuariosPorSexo();
        $usuariosPorGrupo = $this->model->getCantidadesDeUsuariosPorGrupoDeEdad();
        $usuariosPorPais = $this->model->getCantidadesDeUsuariosPorPais();

        $datosPorcentajeCorrectas = $this->model->getPorcentajeRespuestasCorrectasPorUsuario();

        return [
            'cant_jugadores' => $cantJugadores,
            'cantidad_partidas' => $cantPartidasJugadas,
            'cantidad_preguntas' => $cantPreguntas,
            'cantidad_preguntas_creadas' => $cantPreguntasCreadas,
            'usuarios_por_sexo' => $usuariosPorSexo,
            'usuarios_por_grupoEdad' => $usuariosPorGrupo,
            'usuarios_por_Pais' => $usuariosPorPais,
            'porcentaje_correctas' => $datosPorcentajeCorrectas,
        ];
    }
}
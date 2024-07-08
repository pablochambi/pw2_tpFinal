<?php

class PartidaController extends BaseController
{
    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        $id_usuario = $this->checkSessionYTraerIdUsuario();
        $this->model->arrancarPartida($id_usuario);
        $pregunta = $this->traerUnaPreguntaYActualizarDatos($id_usuario);
        $this->mostrarPreguntaYRespuestasPosibles($pregunta);
    }

    public function procesarRespuesta()
    {
        $this->checkSession();
        $this->manejoDeElProcesoDeRespuesta();
    }

    public function siguientePregunta()
    {
        $user_id = $this->checkSessionYTraerIdUsuario();
        $pregunta = $this->traerUnaPreguntaYActualizarDatos($user_id);
        $this->mostrarPreguntaYRespuestasPosibles($pregunta);
    }

    public function reportarPregunta()
    {
        $user_id = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($user_id);
        $idPregunta = isset($_POST['idPregunta']) ? $_POST['idPregunta'] : die("No se trajo el id de pregunta");
        $perdiste = isset($_POST['perdiste']) ? (string)$_POST['perdiste'] : die("No se sabe si perdiste o no, error 1");

        $this->presenter->render("view/reporteDePregunta.mustache", ['idPregunta' => $idPregunta, 'perdiste' => $perdiste, 'rol' => $rol['rol']]);
    }

    public function cancelarReporte()
    {
        $perdiste = isset($_GET['perdiste']) ? (string)$_GET['perdiste'] : die("No se sabe si perdiste o no, error 2");

        if ($perdiste == 0)
            header("Location:/partida/siguientePregunta");
        elseif ($perdiste == 1)
            header("Location:/homeUsuario");
    }

    public function procesarReporte()
    {
        $idUsuario = $this->checkSessionYTraerIdUsuario();
        if (isset($_POST['idPregunta']) && isset($_POST['reason'])
            && isset($_POST['otherReasonText']) && isset($_POST['perdiste'])) {
            $idPregunta = $_POST['idPregunta'];
            $razonReporteRadio = $_POST['reason'];
            $otraRazonReporteText = $_POST['otherReasonText'];
            $perdiste = $_POST['perdiste'];
        } else {
            die('No se enviaron los datos del formulario correctamente');
        }
        $razon = $this->determinarLaRazonFinalDelReporte($razonReporteRadio, $otraRazonReporteText);
        $this->model->registrarReporte($idPregunta, $idUsuario, $razon);
        if ($perdiste == 0) {
            header("Location:/partida/siguientePregunta");
        } elseif ($perdiste == 1) {
            header("Location:/homeUsuario");
        }
    }

    public function usarTrampa() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $idUsuario = $this->checkSessionYTraerIdUsuario();
        $trampasDisponibles = $this->model->obtenerCantidadDeTrampas($idUsuario);
        if ($trampasDisponibles > 0 && isset($_SESSION['idPregunta'])) {
            $idPregunta = $_SESSION['idPregunta'];
            $respuestasIncorrectas = $this->model->obtenerDosRespuestasAleatoriasIncorrectas($idPregunta);
            $this->model->restarUnaTrampaSiEsUsada($idUsuario);

            echo json_encode(['success' => true, 'respuestasIncorrectas' => $respuestasIncorrectas, 'trampitas' => $trampasDisponibles - 1]);
            return;
        }
        echo json_encode(['success' => false, 'messege' => 'No tiene trampas']);
    }

    public function comprarTrampa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $idUsuario = $this->checkSessionYTraerIdUsuario();
        $this->model->agregarUnaTrampa($idUsuario);
        echo json_encode(['success' => true, 'message' => 'Trampa comprada exitosamente']);
    }

    private function mostrarPreguntaYRespuestasPosibles($pregunta) {
        $id_usuario = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($id_usuario);
        $idPregunta = $pregunta[0]['id'];
        $_SESSION['idPregunta'] = $idPregunta;
        $categoria = $this->model->getCategoriaPorIdDePregunta($pregunta[0]['id']);
        $respuestas = $this->model->traerRespuestasDesordenadas($pregunta[0]['id']);
        $trampitas = $this->model->obtenerCantidadDeTrampas($id_usuario);
        $tiempo = time();
        //var_dump($trampitas);
        $this->presenter->render("view/partida.mustache", [
            'pregunta' => $pregunta,
            'categoria' => $categoria,
            'respuestas' => $respuestas,
            "rol" => $rol['rol'],
            "trampitas" => $trampitas,
            "tiempo" => $tiempo
        ]);

    }

    private function determinarLaRazonFinalDelReporte($razonReporteRadio, $otraRazonReporteText)
    {
        switch ($razonReporteRadio) {
            case '1':
                $razon = "Contenido ofensivo";
                break;
            case '2':
                $razon = "Error ortográfico o gramática";
                break;
            case '3':
                $razon = "Respuesta incorrecta";
                break;
            case '4':
                $razon = "Pregunta mal formulada";
                break;
            case '5':
                $razon = "Categoria incorrecta";
                break;
            case 'otro':
                $razon = $otraRazonReporteText;
                break;
            default:
                die("No se envio ninguna razon");
        }
        return $razon;
    }

    private function getDatos($nombre, $valor_respuesta, $pregunta): array
    {
        $datos = [
            "categoria" => $nombre,
            "valor_respuesta" => $valor_respuesta == 1 ? "Correcta" : "Incorrecta",
            "pregunta" => $pregunta['texto'],
            "id_pregunta" => $pregunta['id']

        ];
        return $datos;
    }

    private function handleQuestionTimeout()
    {
        $id_usuario = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($id_usuario);
        $pregunta = $this->model->getPreguntaPorIdDePregunta($_POST['pregunta']);
        $categoria = $this->model->getCategoriaPorIdDePregunta($_POST['pregunta']);
        $puntaje = $this->model->obtenerCantidadDePuntos($id_usuario);

        $this->presenter->render("view/vistasPostAccion/mostrarPuntajeDespuesPerder.mustache", ['puntaje' => $puntaje, 'pregunta' => $pregunta, 'categoria' => $categoria, "rol" => $rol['rol']]);
    }

    private function traerUnaPreguntaYActualizarDatos($id_usuario)
    {
        $pregunta = $this->model->traerPreguntaAleatoriaSinRepeticionDePregunta($id_usuario);
        $this->model->registrarEnPreguntaVistaPorElUsuario($pregunta[0]['id'], $id_usuario);
        $this->model->updateDatosPregunta($pregunta[0]['id']);//sumar vecesEntregadas
        $this->model->actualizarPreguntasEntregadasAUnUsuario($id_usuario);
        return $pregunta;
    }

    private function respuestaCorrectaPath($id_pregunta)
    {
        $id_usuario = $this->checkSessionYTraerIdUsuario();

        $this->model->updatePregBienRespondidas($id_pregunta);//sumar en vecesCorrectas
        $this->model->actualizarCantidadDePreguntasCorrectasAUnUsuario($id_usuario);
        $this->model->actualizarNivelDePregunta($id_pregunta);
        $partida = $this->model->obtenerUltimaPartida($id_usuario);
        $this->model->sumarPuntos($id_usuario, $partida);
    }

    private function manejoDeElProcesoDeRespuesta()
    {
        $user_id = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($user_id);

        if($_POST['pregunta'] == null) {
            header("Location: /homeUsuario");
        }
        $categoria = $this->model->getCategoriaPorIdDePregunta($_POST['pregunta']);
        $idPregunta = $_POST['pregunta'];
        $tiempoDeInicio = $_POST['tiempo_inicial'];
        $tiempoActual = time();
        $tiempoTranscurrido = $tiempoActual- $tiempoDeInicio;



        if($tiempoTranscurrido > 10) {

            $this->handleQuestionTimeout();
            return;
        }




/*        // Iniciar el temporizador si no está iniciado
        if (!isset($_SESSION['question_start_time'][$idPregunta])) {
            $_SESSION['question_start_time'][$idPregunta] = time();
        }

        // Verificar si el tiempo ha expirado
        if (isset($_SESSION['question_start_time'][$idPregunta])) {
            $tiempoDeInicio = $_SESSION['question_start_time'][$idPregunta];
            $tiempoTranscurrido = time() - $tiempoDeInicio;
            $tiempoRestante = $duracion - $tiempoTranscurrido;

            if ($tiempoRestante <= 0) { //SI EL TIEMPO DEL BACK Y EL DEL FRONT SON 0 MANEJA EL ERROR
                $this->handleQuestionTimeout(); // Manejar caso cuando el tiempo se acaba
                return;
            }
        }*/
        if (isset($_POST['respuesta']) && isset($_POST['pregunta'])) {
            $respuesta = $_POST['respuesta'];
            $pregunta = $this->model->getPreguntaPorIdDePregunta($idPregunta);

            if ($this->model->esRespuestaCorrecta($respuesta, $idPregunta)) {
                $this->respuestaCorrectaPath($idPregunta);
                $this->model->actualizarNivelDelUsuario($user_id);
                $this->presenter->render("view/esRespuestaCorrecta.mustache", ['pregunta' => $pregunta, 'categoria' => $categoria, "rol" => $rol['rol']]);
            } else {
                $puntaje = (string)$this->model->obtenerCantidadDePuntos($user_id);
                $this->presenter->render("view/vistasPostAccion/mostrarPuntajeDespuesPerder.mustache", ['puntaje' => $puntaje, 'pregunta' => $pregunta, 'categoria' => $categoria, "rol" => $rol['rol']]);
            }
        } else{
            $error = "No se encontró la respuesta o la pregunta en el formulario.";
            $this->presenter->render("view/vistasPostAccion/mostrarPuntajeDespuesPerder.mustache", ['error' => $error]);
        }
    }
}
<?php
class EditorController extends BaseController
{
    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        $user_id = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($user_id);
        $pregunta = $this ->model->traerPreguntasSugeridas();
        $data = ['pregunta' => $pregunta, 'rol' => $rol['rol']];
        $this->presenter->render('view/vistaEditor/editor.mustache', $data);
    }
    public function aceptar()
    {
        $this->checkSession();
        $idPregunta = $_GET['id'];
        $this->model->aceptarPreguntaSugerida($idPregunta);
        header('Location: /editor');
    }
    public function denegar()
{
    $this->checkSession();
    $idPregunta = $_GET['id'];
    $this->model->eliminarPregunta($idPregunta);
    header('Location: /editor');
}
    public function mostrarPreguntasReportadas()
    {
        $idUsuario = $this->checkSessionYTraerIdUsuario();
        $datos = $this->datosAEnviarALaVistaDeReportes($idUsuario);
        $this->presenter->render("view/vistaEditor/preguntasReportadas.mustache",$datos);
    }

    public function mostrarTodasLasPreguntas()
    {//Hacer
        $user_id = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($user_id);

        $preguntas = $this ->model->traerTodasLasPreguntas(); // Hacer metodo

        $data = ['preguntas' => $preguntas, 'rol' => $rol['rol']];
        $this->presenter->render("view/vistaEditor/todasLasPreguntas.mustache",$data);
    }

    public function aprobarPregunta()
    {
        $idUsuario = $this->checkSessionYTraerIdUsuario();
        $idPregunta = $_GET['id'];
        $this->model->eliminarPreguntaDeLaListaDeReportes($idPregunta,$idUsuario);

        $datos = $this->datosAEnviarALaVistaDeReportes($idUsuario);
        $this->presenter->render("view/vistaEditor/preguntasReportadas.mustache",$datos);
    }
    public function eliminarPregunta()
    {
        $idUsuario = $this->checkSessionYTraerIdUsuario();
        $idPregunta = $_GET['id'];

        $this->model->eliminarPreguntaDeLaListaDeReportes($idPregunta,$idUsuario);
        $this->model->eliminarPregunta($idPregunta);

        $datos = $this->datosAEnviarALaVistaDeReportes($idUsuario);

        $this->presenter->render("view/vistaEditor/preguntasReportadas.mustache",$datos);
    }
    public function modificarPregunta()
    {//Preguntar si se hace o no
        $idUsuario = $this->checkSessionYTraerIdUsuario();
        $idPregunta = $_GET['id'];
    }


    private function datosAEnviarALaVistaDeReportes($idUsuario): array
    {
        $rol = $this->verificarDeQueRolEsElUsuario($idUsuario);
        $preguntas = $this->model->traerPreguntasReportadas();
        return ['preguntas' => $preguntas, 'rol' => $rol['rol']];
    }


}
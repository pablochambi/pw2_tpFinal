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
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);
        $this->presenter->render('view/menu-editor.mustache', ['rol' => $rol['rol']]);

    }

    public function verSugeridas()
    {
        $this->checkSession();
        $user = $_SESSION['username'];

        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);
        $pregunta = $this->model->traerPreguntasSugeridas();
        $data = [
            'pregunta' => $pregunta,
            'rol' => $rol['rol'],
        ];

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

        $this->model->denegarPreguntaSugerida($idPregunta);

        header('Location: /editor');
    }

    public function buscarParaEditar()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);
        $preguntas = $this->model->traerTodasLasPreguntas();
        $this->presenter->render('view/editarPregunta.mustache', ['rol' => $rol['rol'], 'preguntas' => $preguntas]);
    }

    public function buscarPregunta()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['term'])) {
            $term = $_GET['term'];
            $preguntaEncontrada = $this->model->buscarPreguntasPorIdONombre($term);
            $this->presenter->render('view/editarPregunta.mustache', ['rol' => $rol['rol'], 'preguntaEncontrada' => $preguntaEncontrada]);

        } else {

            $this->presenter->render('view/editarPregunta.mustache', [
                'rol' => $rol['rol'],
                'preguntasEncontradas' => []
            ]);
        }
    }
}
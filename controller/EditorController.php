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
        $this -> checkSession();
        $user = $_SESSION['username'];

        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);
        $pregunta = $this ->model->traerPreguntasSugeridas();
        $data = [
            'pregunta' => $pregunta,
            'rol' => $rol['rol'],


        ];
        ;

        $this->presenter->render('view/editor.mustache', $data);

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
}
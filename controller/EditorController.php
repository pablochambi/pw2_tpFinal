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

    public function buscarParaEditar()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);
        $preguntas = $this->model->traerTodasLasPreguntas();
        $this->presenter->render('view/buscarPregunta.mustache', ['rol' => $rol['rol'], 'preguntas' => $preguntas]);
    }

    public function buscarPregunta()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['term'])) {
            $term = $_GET['term'];
            $preguntaEncontrada = $this->model->buscarPreguntasPorIdONombre($term);
            $respuestas = $this->model->buscarRespuestaPorIdPregunta($preguntaEncontrada[0]['id']);
            $categoria = $this->model-> traerCategoriasPorId($preguntaEncontrada[0]['id_categoria']);
            $allCategorias = $this->model->traerTodasLasCategorias();

            $this->presenter->render('view/editarPregunta.mustache', [
                'rol' => $rol['rol'],
                'preguntaEncontrada' => $preguntaEncontrada,
                'respuestas' => $respuestas,
                'categoria' => $categoria,
                'allCategorias' => $allCategorias

            ]);

        } else {

            $this->presenter->render('view/editarPregunta.mustache', [
                'rol' => $rol['rol'],
                'preguntasEncontradas' => []
            ]);
        }
    }

    public function editarPregunta()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pregunta_id']) && isset($_POST['nuevo_texto'])
        && isset($_POST['nueva_categoria']) && isset($_POST['nueva_dificultad']) && isset($_POST['nueva_respuesta'])
        && isset($_POST['nueva_respuesta_incorrecta3']) && isset($_POST['nueva_respuesta_incorrecta1']) && isset($_POST['nueva_respuesta_incorrecta2'])){
            $id = $_POST['pregunta_id'];
            $nuevoTexto = $_POST['nuevo_texto'];
            $nuevaCategoria = $_POST['nueva_categoria'];
            $nuevaDificultad = $_POST['nueva_dificultad'];
            $nuevaRespuesta = $_POST['nueva_respuesta'];
            $nuevaRespuestaIncorrecta1 = $_POST['nueva_respuesta_incorrecta1'];
            $nuevaRespuestaIncorrecta2 = $_POST['nueva_respuesta_incorrecta2'];
            $nuevaRespuestaIncorrecta3 = $_POST['nueva_respuesta_incorrecta3'];



            $respuestasIncorrectasActuales = $this->model->obtenerRespuestasIncorrectas($id);

            $this->model->actualizarPregunta($id, $nuevoTexto);
            $this->model->actualizarCategoriaDeLaPregunta($id, $nuevaCategoria);
            $this->model->actualizarDificultadDeLaPregunta($id, $nuevaDificultad);
            $this->model->actualizarRespuestaCorrecta($id, $nuevaRespuesta);

            if ($nuevaRespuestaIncorrecta1 !== $respuestasIncorrectasActuales['incorrecta1']) {
                $this->model->actualizarRespuestaIncorrecta($id, 1, $nuevaRespuestaIncorrecta1);
            }
            if ($nuevaRespuestaIncorrecta2 !== $respuestasIncorrectasActuales['incorrecta2']) {
                $this->model->actualizarRespuestaIncorrecta($id, 2, $nuevaRespuestaIncorrecta2);
            }
            if ($nuevaRespuestaIncorrecta3 !== $respuestasIncorrectasActuales['incorrecta3']) {
                $this->model->actualizarRespuestaIncorrecta($id, 3, $nuevaRespuestaIncorrecta3);
            }

            var_dump();

            header('Location: /editor/buscarParaEditar');
        } else {
            echo "no fue post";
        }
    }



}
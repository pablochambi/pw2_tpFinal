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
        $this->presenter->render('view/buscarPregunta.mustache', ['rol' => $rol['rol'], 'preguntas' => $preguntas]);
    }

    public function buscarParaEliminar()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);
        $preguntas = $this->model->traerTodasLasPreguntas();
        $this->presenter->render('view/buscarParaEliminar.mustache', ['rol' => $rol['rol'], 'preguntas' => $preguntas]);
    }



    public function buscarPregunta()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['term'])) {
            $term = $_GET['term'];
            $preguntaEncontrada = $this->model->buscarPreguntasPorIdONombre($term);
                if($preguntaEncontrada){
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

                    $this->presenter->render('view/vistasPostAccion/editarPreguntaVistaError.mustache', [
                        'rol' => $rol['rol'],

                    ]);
                }
            }  else {
            header('Location: /editor/buscarParaEditar');


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
            $respuestaIncorrecta1 = $respuestasIncorrectasActuales['incorrecta1']['id'];
            $respuestaIncorrecta2 = $respuestasIncorrectasActuales['incorrecta2']['id'];
            $respuestaIncorrecta3 = $respuestasIncorrectasActuales['incorrecta3']['id'];

            $this->model->actualizarPregunta($id, $nuevoTexto);
            $this->model->actualizarCategoriaDeLaPregunta($id, $nuevaCategoria);
            $this->model->actualizarDificultadDeLaPregunta($id, $nuevaDificultad);
            $this->model->actualizarRespuestaCorrecta($id, $nuevaRespuesta);
           $this->model->actualizarRespuestaIncorrecta($id, $respuestaIncorrecta1, $nuevaRespuestaIncorrecta1);
           $this->model->actualizarRespuestaIncorrecta($id, $respuestaIncorrecta2, $nuevaRespuestaIncorrecta2);
           $this->model->actualizarRespuestaIncorrecta($id, $respuestaIncorrecta3, $nuevaRespuestaIncorrecta3);

            $this->presenter->render('view/vistasPostAccion/editarPreguntaAviso.mustache', [
                'rol' => $rol['rol'],
            ]);
        } else {
             $this->presenter->render('view/vistasPostAccion/manejarErrorGeneral.mustache', [
                'rol' => $rol['rol'],
                 ]);
        }
    }

    public function buscarEliminarPregunta()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['param'])) {
            $term = $_GET['param'];
            $preguntaEncontrada = $this->model->buscarPreguntasPorIdONombre($term);

            if($preguntaEncontrada){
                $respuestas = $this->model->buscarRespuestaPorIdPregunta($preguntaEncontrada[0]['id']);
                $categoria = $this->model-> traerCategoriasPorId($preguntaEncontrada[0]['id_categoria']);
                $allCategorias = $this->model->traerTodasLasCategorias();

                $this->presenter->render('view/eliminarPregunta.mustache', [
                    'rol' => $rol['rol'],
                    'preguntaEncontrada' => $preguntaEncontrada,
                    'respuestas' => $respuestas,
                    'categoria' => $categoria,
                    'allCategorias' => $allCategorias

                ]);
            } else {
                $this->presenter->render('view/vistasPostAccion/editarPreguntaVistaError.mustache', [
                    'rol' => $rol['rol'],

                ]);
            }
        } else {
            header('Location: /editor/buscarPreguntaParaEliminar');
        }

    }


    public function eliminarPregunta()
    {
        $this->checkSession();
        $user = $_SESSION['username'];
        $rol = $this->verificarDeQueRolEsElUsuario($user['id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pregunta_id'])) {
            $id = $_POST['pregunta_id'];
            $this->model->eliminarLaPregunta($id);
           $this->presenter->render('view/vistasPostAccion/eliminarExitoso.mustache', [
                'rol' => $rol['rol'],
            ]);
        } else {
            $this->presenter->render('view/vistasPostAccion/manejarErrorGeneral.mustache', [
                'rol' => $rol['rol'],
            ]);
        }
    }




}
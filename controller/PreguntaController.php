<?php

class PreguntaController extends BaseController
{

    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        $this->checkSession();
        $userId = $_SESSION["username"];
        $rol = $this->verificarDeQueRolEsElUsuario($userId['id']);
        $categorias = $this->model->getCategorias();

        $this->presenter->render("view/crearPregunta.mustache", ['categorias' => $categorias, "rol" => $rol['rol']]);
    }

    public function crearPreguntaSugerida()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $texto = $_POST['texto'];
            $id_categoria = $_POST['id_categoria'];
            $usuario_creador = $this->checkSessionYTraerIdUsuario();

            $resultado = $this->model->crearPreguntaSugerida($texto, $id_categoria, $usuario_creador);

            if ($resultado) {
                $rol = $this->verificarDeQueRolEsElUsuario($usuario_creador);
                $this->presenter->render("view/crearRespuesta.mustache", ['id_pregunta' => $resultado, 'rol' => $rol['rol']]);
                exit();
            } else {
                header("location: login");
                exit();
            }

            $categorias = $this->model->obtenerCategorias();
            echo $this->mustache->render('crearPregunta', ['categorias' => $categorias]);
        } else {
            echo "error";
        }
    }
}
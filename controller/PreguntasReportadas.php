<?php
class PreguntasReportadas extends BaseController
{
    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        $userId = $this->checkSessionYTraerIdUsuario();
        $rol = $this->verificarDeQueRolEsElUsuario($userId);
        $categorias = $this->model->getCategorias();

        $this->presenter->render("view/crearPregunta.mustache", ['categorias' => $categorias, "rol"=> $rol['rol']]);

    }


}
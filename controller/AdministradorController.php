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

    public function generarGrafico()
    {
        $this->model->crearGrafico();
    }

    private function datosAEnviarALaVistaAdministrador($idUsuario): array
    {
        $rol = $this->verificarDeQueRolEsElUsuario($idUsuario);
        //$preguntas = $this->model->traerPreguntasReportadas();
        return ['rol' => $rol['rol']];
    }

}
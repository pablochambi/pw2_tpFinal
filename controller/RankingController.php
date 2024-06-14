<?php

class RankingController extends BaseController
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
        $data = [
            'usuarios' => $this->model->obtenerRanking(),
            'rol' => $rol['rol']
        ];

        $this->presenter->render('view/ranking.mustache', ['usuarios' => $data['usuarios'], 'rol' => $data['rol']]);

    }
}
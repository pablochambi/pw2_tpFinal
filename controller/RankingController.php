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

        $data = [
            'usuarios' => $this->model->obtenerRanking()
        ];

        $this->presenter->render('view/ranking.mustache', $data);

    }
}
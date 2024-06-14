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

        $data = [
            'pregunta' => $this ->model->traerPreguntasSugeridas()
        ];

        $this->presenter->render('view/editor.mustache', $data);

    }
}
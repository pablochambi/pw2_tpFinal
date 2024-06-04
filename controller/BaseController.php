<?php
class BaseController
{
    protected $model;
    protected $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    protected function startSession()
    {
        session_start();
    }

    protected function checkSession()
    {
        if (!isset($_SESSION) || empty($_SESSION)) {
            header("location: /login");
            exit();
        }
    }

    protected function getUsername()
    {
        return $_SESSION["username"];
    }

}
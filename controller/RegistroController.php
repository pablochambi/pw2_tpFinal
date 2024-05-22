<?php
namespace controller;

class RegistroController
{
    private $presenter;

    public function __construct($presenter)
    {
        $this->presenter = $presenter;
    }

    public function get()
    {
        $this->presenter->render("view/registro.mustache");
    }

}
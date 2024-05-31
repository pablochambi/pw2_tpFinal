<?php

class Presenter
{

    public function __construct()
    {
    }

    public function render($view, $data = [])
    {

        if (isset($_SESSION)){
            include_once("view/template/headerLoged.mustache");
            include_once($view);
            include_once("view/template/footer.mustache");

        }else{

            include_once("view/template/header.mustache");
            include_once($view);
            include_once("view/template/footer.mustache");

        }


    }
}
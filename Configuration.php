<?php

use controller\RegistroController;
use model\RegistroModel;

include_once ("controller/RegistroController.php");

include_once ("model/RegistroModel.php");

include_once ("helper/Database.php");
include_once ("helper/Router.php");

include_once ("helper/Presenter.php");
include_once ("helper/MustachePresenter.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{

    // CONTROLLERS
    public static function getRegistroController()
    {
        return new RegistroController(self::getPresenter());
    }

    // MODELS
    private static function getRegistroModel()
    {
        return new RegistroModel(self::getDatabase());
    }

    // HELPERS
    public static function getDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }

    private static function getConfig()
    {
        return parse_ini_file("config/config.ini");
    }

//Controlador  por Defecto
    public static function getRouter()
    {
        return new Router("getRegistroController", "get");
    }

//Este son las plantillas
    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }
}
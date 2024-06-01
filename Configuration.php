<?php

include_once ("controller/RegistroController.php");
include_once ("controller/LoginController.php");
include_once ("controller/PerfilController.php");
include_once ("controller/HomeUsuarioController.php");
include_once ("controller/PartidaController.php");

include_once ("model/RegistroModel.php");
include_once ("model/LoginModel.php");
include_once ("model/PartidaModel.php");
include_once ("model/HomeUsuarioModel.php");

include_once ("helper/Database.php");
include_once ("helper/Router.php");

include_once ("helper/Presenter.php");
include_once ("helper/MustachePresenter.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    // CONTROLLERS
    public static function getPartidaController()
    {
        return new PartidaController(self::getPartidaModel(), self::getPresenter());
    }
    public static function getRegistroController()
    {
        return new RegistroController(self::getRegistroModel(),self::getPresenter());
    }

    public static function getPerfilController()
    {
        return new PerfilController(self::getLoginModel(), self::getPresenter());
    }

    public static function getLoginController()
    {
        return new LoginController (self::getLoginModel(), self::getPresenter());
    }

    public static function getHomeUsuarioController()
    {
        return new HomeUsuarioController(self::getHomeUsuarioModel(), self::getPresenter());
    }

    private static function getLoginModel()
    {
        return new LoginModel(self::getDatabase());

    }

    // MODELS

    private static function getPartidaModel()
    {
        return new PartidaModel(self::getDatabase());
    }
    private static function getRegistroModel()
    {
        return new RegistroModel(self::getDatabase());
    }
    private static function getHomeUsuarioModel()
    {
        return new HomeUsuarioModel(self::getDatabase());
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
        return new Router("getLoginController", "get");
    }

//Este son las plantillas
    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }
}
<?php
include_once ("controller/SongsController.php");
include_once ("controller/ToursController.php");
include_once ("controller/LaBandaController.php");

include_once ("model/SongsModel.php");
include_once ("model/ToursModel.php");

include_once ("helper/Database.php");
include_once ("helper/Router.php");

include_once ("helper/Presenter.php");
include_once ("helper/MustachePresenter.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{

    // CONTROLLERS
    public static function getLaBandaController()
    {
        return new LaBandaController(self::getPresenter());
    }


    public static function getToursController()
    {
        //Le pasamos la Logica y la Presentacion
        return new ToursController(self::getToursModel(), self::getPresenter());
    }

    public static function getSongsController()
    {
        return new SongsController(self::getSongsModel(), self::getPresenter());
    }

    // MODELS
    private static function getToursModel()
    {
        return new ToursModel(self::getDatabase());
    }

    private static function getSongsModel()
    {
        return new SongsModel(self::getDatabase());
    }


    // HELPERS//Ayundantes
    public static function getDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }

    private static function getConfig()
    {
        return parse_ini_file("config/config.ini");
    }

//Controlador  por DEfecto
    public static function getRouter()
    {
        return new Router("getLaBandaController", "get");
    }

//Este son las plantillas
    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }
}
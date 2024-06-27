<?php

include_once ("controller/BaseController.php");
include_once ("controller/RegistroController.php");
include_once ("controller/LoginController.php");
include_once ("controller/PerfilController.php");
include_once ("controller/HomeUsuarioController.php");
include_once ("controller/PartidaController.php");
include_once ("controller/PreguntaController.php");
include_once ("controller/RespuestaController.php");
include_once ("controller/RankingController.php");
include_once ("controller/EditorController.php");
include_once ("controller/AdministradorController.php");

include_once ("model/BaseModel.php");
include_once ("model/RegistroModel.php");
include_once ("model/LoginModel.php");
include_once ("model/PartidaModel.php");
include_once ("model/HomeUsuarioModel.php");
include_once ("model/PreguntaModel.php");
include_once ("model/RespuestaModel.php");
include_once ("model/RankingModel.php");
include_once ("model/EditorModel.php");
include_once ("model/AdministradorModel.php");

include_once ("helper/Database.php");
include_once ("helper/Router.php");
include_once ("helper/Presenter.php");
include_once ("helper/MustachePresenter.php");
include_once ("helper/GraficoCreator.php");
include_once ("helper/PdfCreator.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');
//require_once('third-party/dompdf-example/dompdf/autoload.inc.php');
require_once('third-party/jpgraph-example/jpgraph/src/jpgraph.php');
require_once('third-party/jpgraph-example/jpgraph/src/jpgraph_bar.php');
require_once('third-party/jpgraph-example/jpgraph/src/jpgraph_line.php');
include_once("third-party/phpqrcode-2010100721_1.1.4/phpqrcode/qrlib.php");

require 'third-party/PHPMailer/src/Exception.php';
require 'third-party/PHPMailer/src/PHPMailer.php';
require 'third-party/PHPMailer/src/SMTP.php';

class Configuration
{
    // CONTROLLERS

    public static function getBaseController()
    {
        return new BaseController(self::getBaseModel(), self::getPresenter());
    }
    public static function getAdministradorController()
    {
        return new AdministradorController(self::getAdministradorModel(), self::getPresenter(),self::getPdfCreator(),self::getMustache(),self::getGraficoCreator());
    }

    public static function getEditorController()
    {
        return new EditorController(self::getEditorModel(), self::getPresenter());
    }

    public static function getRankingController()
    {
        return new RankingController(self::getRankingModel(), self::getPresenter());
    }

    public static function getPreguntaController()
    {
        return new PreguntaController(self::getPreguntaModel(), self::getPresenter());
    }

    public static function getRespuestaController()
    {
        return new RespuestaController(self::getRespuestaModel(), self::getPresenter());
    }

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



    // MODELS

    private static function getBaseModel()
    {
        return new BaseModel(self::getDatabase());
    }
    private static function getAdministradorModel()
    {
        return new AdministradorModel(self::getDatabase());
    }
    private static function getEditorModel()
    {
        return new EditorModel(self::getDatabase());
    }

    private static function getLoginModel()
    {
        return new LoginModel(self::getDatabase());
    }
    private static function getRankingModel()
    {
        return new RankingModel(self::getDatabase());
    }
    private static function getPartidaModel()
    {
        return new PartidaModel(self::getDatabase());
    }
    private static function getPreguntaModel()
    {
        return new PreguntaModel(self::getDatabase());
    }

    private static function getRespuestaModel()
    {
        return new RespuestaModel(self::getDatabase());
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
        return new Database($config["servername"] . ":" . $config['port'], $config["username"], $config["password"], $config["dbname"]);
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
    public static function getMustache()
    {
        return new MustachePresenter();
    }

    public static function getPdfCreator()
    {
        return new PdfCreator();
    }
    public static function getGraficoCreator()
    {
        return new GraficoCreator();
    }


}
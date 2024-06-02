<?php
session_start();
include_once ("Configuration.php");
$router = Configuration::getRouter();
//NO esta logueado agregar msj
//Si la pagina es login no preguntar por la sesion

$controller = isset($_GET["controller"]) ? $_GET["controller"] : "" ;
$action = isset($_GET["action"]) ? $_GET["action"] : "" ;

$router->route($controller, $action);

var_dump($_SESSION);
// index.php?controller=tours&action=get
// tours/get
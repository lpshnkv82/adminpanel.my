<?php
use core\base\controller\ContrException;
use core\base\controller\RouteController;
use libraries\AjaxController;

define('VELES', true);

header('Content-Type:text/html;charset=utf-8');
session_start();

//error_reporting(0);

require_once 'config.php';
require_once 'libraries/functions.php';


spl_autoload_register(function ($class_name) {

    /*Не производим автоматической загрузки своих классов, если подключаются классы библиотеки PHPExcel*/
    if (strpos($class_name, 'PHPExcel') === 0 || strpos($class_name, 'PHPWord') === 0) {
        return;
    }
    /*Не производим автоматической загрузки своих классов, если подключаются классы библиотеки PHPExcel*/
    $class_name = str_replace('\\', '/', $class_name);
    $class_name = str_replace('_', '/', $class_name);
    //exit($class_name);
    if (!@include_once ($class_name.'.php')) {
        try {
            throw new ContrException('Не корректное имя файла для подключения - '. $class_name);
        }
        catch (ContrException $e) {
            echo $e->getMessage();
        }
    }
});

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax'])) {
    $ajax = new AjaxController();
}

try {
    $object = RouteController::getInstance();
    $object->route();
}
catch (ContrException $e) {
    return;
}
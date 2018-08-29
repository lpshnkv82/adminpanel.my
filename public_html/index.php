<?php
use core\base\controller\ContrException;
use core\base\controller\RouteController;

define('VELES', true);

header('Content-Type:text/html;charset=utf-8');
session_start();

//error_reporting(0);

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';


try {
    $object = RouteController::getInstance();
    $object->route();
}
catch (ContrException $e) {
    return;
}
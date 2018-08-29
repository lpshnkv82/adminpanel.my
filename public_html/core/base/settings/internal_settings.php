<?php
defined('VELES') or die('Access denied');

const TEMPLATE = 'template/default/';
const ADMIN_TEMPLATE = 'template/admin/';
const CORE =  'core';
const USER_PATH = 'user';
const ADMIN_PATH = 'admin';
const BASE_PATH = 'base';
const BASE_CONTROLLER = 'controller';
const USER_CONTROLLER = 'controller';
const ADMIN_CONTROLLER = 'controller';

/*Безопасность*/
const VERSION = '1.1.0';
const CRYPT_KEY = 'SDF*9879790SDDFsdf098098*&&^$%&^sdfsddsfsfDSFSF-';
const COOKIE_TIME = 60;
const WARNING_TIME = 15;
/*Безопасность*/

const BLOCK_TIME = 3;
const QTY = 10;
const QTY_LINKS = 3;
const UPLOAD_DIR = 'images/';

const MAX_FOTO_SIZE = '10M';
const CROP = ['cut_1' => [940, 790], 'cut_2' => [16, 9]];
const THUMB_PRIORITY = true;

const ADMIN_CSS_JS = [
                    'styles' => ['css/main.css', 'css/jquery.Jcrop.min.css'],
                    'scripts' => ['js/jquery-3.2.1.min.js',
                        'js/jquery-ui.min.js',
                        'js/scripts.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tiny_script.js',
                        'js/jcrop/jquery.Jcrop.min.js',
                        'js/jcrop/jcrop_set.js'
                    ]
];

use core\base\controller\ContrException;

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
    $ajax = new \libraries\AjaxController();
}


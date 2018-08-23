<?php
defined('VELES') or die('Access denied');

define('LIB', 'libraries/');
define('TEMPLATE', 'template/default/');
define('ADMIN_TEMPLATE', 'template/admin/');
define('PATH', '/');
define('CORE', 'core');
define('USER_PATH', 'user');
define('ADMIN_PATH', 'admin');
define('BASE_PATH', 'base');
define('BASE_CONTROLLER', 'controller');
define('USER_CONTROLLER', 'controller');
define('ADMIN_CONTROLLER', 'controller');
/*Безопасность*/
define('VERSION', '1.1.0');
define('CRYPT_KEY', 'SDF*9879790SDDFsdf098098*&&^$%&^sdfsddsfsfDSFSF-');
define('COOKIE_TIME', 60);
/*Безопасность*/
define('BLOCK_TIME', 3);
define('QTY', 10);
define('QTY_LINKS', 3);
define('UPLOAD_DIR', 'images/');

//define('MAIN_TITLE', 'Дом на берегу озера - ');
define('MAX_FOTO_SIZE', '10M');

const CROP = ['cut_1' => [940, 790], 'cut_2' => [16, 9]];
const THUMB_PRIORITY = false;
const EXCEPTION_TABLES = ['users', 'users_types', 'fealtures', 'settings'];

define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DB_MANE', 'empty');

const SCRIPTS_STYLES = array(
    'styles' => array('css/style.css'),
    'styles_admin' => array('css/main.css', 'css/jquery.Jcrop.min.css'),
    'scripts' => array('js/jquery-3.2.1.min.js',
                        'js/scripts.js'
                        ),
    'scripts_admin' => array('js/scripts.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tiny_script.js',
                        'js/jcrop/jquery.Jcrop.min.js',
                        'js/jcrop/jcrop_set.js'
                        )
);
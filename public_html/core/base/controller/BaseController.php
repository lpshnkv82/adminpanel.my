<?php
namespace core\base\controller;
use core\base\model\ModelUser;

abstract class BaseController
{
    protected $request_url;
    protected $controller;
    protected $parameters;
    protected $user_type;
    protected $user_name;
    protected $user_id;

    protected $style;
    protected $script;

    protected $page;
    protected $errors;

    public function route(){

        $error = '';
        $log_error = '';

        if(is_readable($_SERVER['DOCUMENT_ROOT'].PATH.$this->controller.'.php')){
            $controller = str_replace('/', '\\', $this->controller);
            try{
                $object = new \ReflectionMethod($controller, 'request');
                $object->invoke(new $controller(), $this->getParameters());
                $error = '';
                return;
            }
            catch(\ReflectionException $e){
                $this->writeError('Несуществующий класс - ' . $e);
                $this->redirect(PATH);
            }
        }else{
            $log_error .= 'Неверный файл для подключения - ' . PATH.$this->controller."\r\n";
            $error = 'Такой страницы не существует';
        }


        if($error){
            $this->writeError($log_error);
            throw new ContrException($error);
        }
    }

    protected function init($admin = false)
    {
        if(!$admin){
            if (!empty(USER_CSS_JS['styles'])) {
                foreach (USER_CSS_JS['styles'] as $style) {
                    $this->style[] = PATH.TEMPLATE.trim($style, '/');
                }
            }
            if (!empty(USER_CSS_JS['scripts'])) {
                foreach (USER_CSS_JS['scripts'] as $script) {
                    $this->script[] = PATH.TEMPLATE.trim($script, '/');
                }
            }
        }else{
            if (!empty(ADMIN_CSS_JS['styles'])) {
                foreach (ADMIN_CSS_JS['styles'] as $style) {
                    $this->style[] = PATH.ADMIN_TEMPLATE.trim($style, '/');
                }
            }
            if (!empty(ADMIN_CSS_JS['scripts'])) {
                foreach (ADMIN_CSS_JS['scripts'] as $script) {
                    $this->script[] = PATH.ADMIN_TEMPLATE.trim($script, '/');
                }
            }
        }

    }

    protected function getController()
    {
        return $this->controller;
    }

    protected function getParameters()
    {
        return $this->parameters;
    }

    protected function inputData()
    {

    }

    protected function outputData()
    {

    }

    public function request($parameters = array())
    {
        $this->inputData($parameters);
        $this->page = $this->outputData();

        if (!empty($this->errors)) {
            $this->writeError($this->errors);
        }

        $this->getPage();
    }

    protected function getPage()
    {
        echo $this->page;
    }

    protected function render($path, $parameters = array())
    {
        extract($parameters); //Создает переменные в памяти: имя переменной - ключ массива, значение переменной = значение

        ob_start();
        if (!include $path . '.php') {
            throw new ContrException('Данного шаблона не найдено', $path);
        }
        return ob_get_clean();
    }

    protected function clearStr($var){

        if (is_array($var)) {
            $row = array();
            foreach ($var as $key => $value) {
                $row[$key] = trim(strip_tags(htmlspecialchars($value)));
            }
            return $row;
        } else {
            return trim(strip_tags($var));
        }
    }

    protected function clearInt($var){
        return (int)$var;
    }

    protected function isPost(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            return true;
        }
        return false;
    }

    protected function redirect($http = false){
        if($http) $redirect = $http;
        else $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
        header("Location: $redirect");
        exit;
    }

    protected function checkAuth(){
        try{
            $cookie = ModelUser::getInstance();
            $cookie->checkIdUser();
            $cookie->validateCookie();
            $this->user_type = $cookie->getUserType();
            $this->user_name = $cookie->getUserName();
            $this->user_id = $cookie->getUserId();
        }
        catch(AuthException $e){
            $this->errors = "Ошибка авторизации | ";
            $this->errors .= $e->getMessage();
            $this->writeError($this->errors);
            $this->redirect(PATH.'login');
        }
    }

    protected function writeError($error, $file = 'log.txt', $event = 'Fault'){
        $time = date('d-m-Y G:i:s');

        $str = $event.': '.$time.' - '.$error."\r\n";
        file_put_contents('log/'.$file, $str, FILE_APPEND);
    }
}

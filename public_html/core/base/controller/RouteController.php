<?php
namespace core\base\controller;

class RouteController extends BaseController{
    static $_instance;

    static function getInstance(){

        if(self::$_instance instanceof self){
            return self::$_instance;
        }else{
            return self::$_instance = new self;
        }
    }

    private function __construct(){

        $adress_str = $_SERVER['REQUEST_URI'];
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));

        if($path === PATH){

            if(strpos($adress_str, ADMIN_PATH) === strlen(PATH)){
                $this->controller = CORE . '/' . ADMIN_PATH . '/' . ADMIN_CONTROLLER . '/';
                $this->request_url = substr($adress_str, strlen(PATH . ADMIN_PATH));
                if(strpos($this->request_url, '/') === 0){
                    $this->request_url = substr($this->request_url, 1);
                }
            }else{
                if(strpos($adress_str, 'error') === strlen(PATH)){
                    $this->controller = CORE . '/' . BASE_PATH . '/' . BASE_CONTROLLER . '/';
                }else{
                    $this->controller = CORE . '/' . USER_PATH . '/' . USER_CONTROLLER . '/';
                }

                $this->request_url = substr($adress_str, strlen(PATH));
            }

            if(strpos($this->controller, '//') !== false){
                $this->controller = str_replace('//', '/', $this->controller);
            }

            if(strrpos($this->request_url, '/') == strlen($this->request_url) - 1 && strrpos($this->request_url, '/') != 0){
                $this->redirect(rtrim($adress_str , '/'));
            }

            $url = explode('/', rtrim($this->request_url, '/'));

            if(!empty($url[0])){
                $this->controller .= ucfirst($url[0].'Controller');
            }else{
                $this->controller .= 'IndexController';
            }

            $count = count($url);
            if(!empty($url[1])){
                $key = array();
                $value = array();

                for($i = 1; $i < $count; $i++){
                    if($i % 2 != 0){
                        $key[] = $url[$i];
                    }else{
                        $value[] = $url[$i];
                    }
                }

                foreach($key as $k => $v){
                    $this->parameters[$v] = $value[$k];
                }

            }

        }else{
            try{
                throw new \Exception('<p>Некорректный адрес сайта</p>');
            }
            catch(\Exception $e){
                echo $e->getMessage();
                exit();
            }
        }
    }
}
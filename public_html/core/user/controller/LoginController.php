<?php
namespace core\user\controller;
use core\base\controller\AuthException;

class LoginController extends \core\base\controller\BaseController {

    protected $object_model_user;

    protected function inputData($parameters = array()){
       //parent::inputData();
        $this->display_flag = true;
        $this->object_model_user = \core\base\model\ModelUser::getInstance();

        if(isset($parameters['logout'])){
            $logout = $this->clearInt($parameters['logout']);
            if($logout){
                $this->checkAuth();
                $user_log = 'Выход пользователя - '.$this->user_name.' с правами - '.$this->user_type;
                $this->writeError($user_log, 'log_user.txt', 'LogoutUser');

                $res = $this->object_model_user->logout();
                if($res){
                    $this->redirect(PATH);
                }
            }
        }

        $ip_user = $_SERVER['REMOTE_ADDR'];
        $time_clean = new \DateTime();

        $time_clean->modify("-".BLOCK_TIME." hour");
        $this->object_model_user->deleteFealtures($time_clean->format("Y-m-d H:i:s"));

        $fealtures = $this->object_model_user->getFealturies($ip_user);

        if($this->isPost()){
            if(isset($_POST['login']) && isset($_POST['password']) && $fealtures < 3){
                $login = $this->clearStr($_POST['login']);
                $password = md5($this->clearStr($_POST['password']));

                try{
                    $user_data = $this->object_model_user->getUser($login, $password);
                    if(!$user_data) $this->redirect();
                    $id = $user_data['user_id'];
                    $this->user_type = $user_data['user_type'];
                    $this->user_name = $user_data['login'];
                    $this->object_model_user->checkIdUser($id, $this->user_type, $this->user_name);
                    $this->object_model_user->set();
                    $user_log = 'Вход пользователя - '.$this->user_name.' с правами - '.$this->user_type;
                    $this->writeError($user_log, 'log_user.txt', 'AccessUser');
                    $this->redirect(PATH.'admin');
                }
                catch(AuthException $e){
                    $this->errors = 'Ошибка авторизации | ';
                    $this->errors .= $e->getMessage();
                    $user_log = $this->errors.' пользователь - '.$_POST['login'].' с адреса - '.$ip_user;
                    $this->writeError($user_log, 'log_user.txt', 'AccessDenied');
                    if($fealtures == NULL){
                        $this->object_model_user->insertFealtures($login, $ip_user);
                    }elseif($fealtures > 0){
                        $this->object_model_user->updateFealtures($login, $ip_user, $fealtures);
                    }
                }
            }elseif($fealtures == 3){
                $user_log = 'Вход пользователя с первышением попыток ввода пароля - '.$_POST['login'].' с адреса - '.$ip_user;
                $this->writeError($user_log, 'log_user.txt', 'AccessDenied');
                try{
                    throw new AuthException('Превышено максимальное количество попыток ввода пароля');
                }
                catch (AuthException $e){
                    $this->errors = 'Ошибка авторизации | ';
                    $this->errors .= $e->getMessage();
                }
            }
        }
    }

    protected function outputData(){

        $this->page = $this->render(ADMIN_TEMPLATE.'login_page', array(
                                                            'error' => $_SESSION['auth']
                                                            ));
        unset($_SESSION['auth']);

        return $this->page;

    }
}
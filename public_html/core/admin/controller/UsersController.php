<?php
namespace core\admin\controller;

class UsersController extends BaseAdmin{
    protected $users;
    protected $usersTypes;

    protected function inputData($parameters){


        parent::inputData();

        if(isset($parameters['delete'])){
            $delete_user = $this->clearInt($parameters['delete']);
            if($this->user_id == $delete_user){
                $_SESSION['res']['answer'] = '<div class="error">Нельзя удалить самого себя</div>';
                $this->redirect();
            }else{
                if($delete_user){
                    $res = $this->object_model->delete('users', $delete_user, false, 'user_id');
                    if($res){
                        $_SESSION['res']['answer'] = '<div class="success">Пользователь успешно удален</div>';
                        $this->redirect();
                    }
                }
            }
        }

        if($this->isPost()){
            $this->editData();
            $this->redirect();
        }

        $this->users = $this->object_model->get('users');
        $this->usersTypes = $this->object_model->get('users_types');

    }

    protected function outputData(){
                                    $this->content = $this->render(ADMIN_TEMPLATE.'users', array(
                                        'users' => $this->users,
                                        'usersTypes' => $this->usersTypes
                                    ));

        $this->page = parent::outputData();
        return $this->page;
    }

    private function editData(){
        if($this->clearPostFields($_POST)){
            if(isset($_POST['addGroup'])){
                $user_type = $this->clearStr($_POST['user_type']);
                $res = $this->object_model->add('users_types', array('user_type' => $user_type));
                if($res){
                    $_SESSION['res']['answer'] = '<div class="success">Группа успешно добавлена</div>';
                }
                $this->redirect();
            }

            if(empty($_POST['login'])){
                $_SESSION['res']['answer'] = '<div class="error">Заполните имя пользователя</div>';
                $this->redirect();
            }

            if(isset($_POST['user_id'])){
                if(empty($_POST['password'])){
                    unset($_POST['password']);
                }else{
                    $_POST['password'] = md5($_POST['password']);
                }
                unset($_POST['change']);
                $res = $this->object_model->edit('users');
                if($res){
                    $_SESSION['res']['answer'] = '<div class="success">Пользователь успешно изменен</div>';
                }
            }elseif(isset($_POST['addUser'])){
                if(empty($_POST['password'])){
                    $_SESSION['res']['answer'] = '<div class="error">Пароль не может быть пустым</div>';
                    $this->redirect();
                }else{
                    $_POST['password'] = md5($_POST['password']);
                }
                unset($_POST['addUser']);
                $res = $this->object_model->add('users');
                if($res){
                    $_SESSION['res']['answer'] = '<div class="success">Пользователь успешно добавлен</div>';
                }
            }
        }
    }
}
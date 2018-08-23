<?php
namespace core\base\controller;

abstract class BaseError extends BaseController{

    protected $messageError;
    protected $title;

    protected function inputData(){

        $this->title = 'Страница 404';

    }
    protected function outputData(){

        $page = $this->render(ADMIN_TEMPLATE.'404', array(
                                'title' => $this->title,
                                'error' => $this->messageError
                                ));
        return $page;
    }

}
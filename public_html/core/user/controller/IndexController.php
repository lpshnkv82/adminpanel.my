<?php
namespace core\user\controller;
use core\base\controller\ContrException;

class IndexController extends BaseUser{


    protected function inputData($parameters){

        parent::inputData();

        /*Метаполя*/
        $this->title = $this->set['type'];
        $this->keywords = $this->set['keywords'];
        $this->description = $this->set['description'];
        /*Метаполя*/



    }

    protected function outputData(){
        
        $this->content = $this->render(TEMPLATE.'main', array(
                                        'pages' => $this->pageItem
                                        ));

        return parent::outputData();
    }

}
<?php
namespace core\user\controller;
use core\base\controller\ContrException;

class CategoriesController extends BaseUser{

    protected $parentItem;

    protected function inputData($parameters){

        parent::inputData();

        /*Метаполя*/
        $this->title = $this->set['type'];
        $this->keywords = $this->set['keywords'];
        $this->description = $this->set['description'];
        /*Метаполя*/


        if(THUMB_PRIORITY){
            $this->pageItem = $this->createImgThumbs($this->pageItem);
        }

    }

    protected function outputData(){
        
        $this->content = $this->render(TEMPLATE.$this->template, array(
                                        'pages' => $this->pageItem
                                        ));

        return parent::outputData();
    }

}
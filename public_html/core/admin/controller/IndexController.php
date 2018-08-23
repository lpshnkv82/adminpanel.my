<?php
namespace core\admin\controller;

class IndexController extends BaseAdmin{

    protected function inputData($parameters){

        $this->redirect(PATH.ADMIN_PATH . '/show');
    }

    protected function outputData(){
        
        $this->content = $this->render(ADMIN_TEMPLATE.'all', array(
                                                'data' => $this->data,
                                                'edit' => $this->edit
                                                ));

        $this->page = parent::outputData();
        return $this->page;

    }
}
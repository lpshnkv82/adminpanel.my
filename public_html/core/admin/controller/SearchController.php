<?php
namespace core\admin\controller;

class SearchController extends BaseAdmin{

    protected function inputData($parameters){

        parent::inputData();

        if($this->isPost()){
            $data = $this->clearStr($_POST['search']);
            $this->data = $this->object_model->adminSearch($data);
        }
    }

    protected function outputData(){
        
        $this->content = $this->render(ADMIN_TEMPLATE.'search', array(
                                                'data' => $this->data
                                                ));

        $this->page = parent::outputData();
        return $this->page;

    }
}
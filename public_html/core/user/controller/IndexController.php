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

        $this->main_class = 'main_page';
        $this->display_flag = true;

        $this->pageItem = $this->object_model->get('pages', [
            'fields' => ['id', 'name', 'img', 'thumbnails', 'short_content'],
            'where' => ['visible' => '1', 'parent_id' => 0],
            'order' => ['menu_pos']
        ]);

        foreach($this->pageItem as $key => $item){
            if($item['thumbnails']){
                $this->pageItem[$key]['img'] = PATH.UPLOAD_DIR.$item['thumbnails'];
            }else{
                $this->pageItem[$key]['img'] = PATH.UPLOAD_DIR.$item['img'];
            }
        }

        /*if($this->set['gallery_img']){
            $this->set['gallery_img'] = explode("|", $this->set['gallery_img']);
        }*/
    }

    protected function outputData(){
        
        $this->content = $this->render(TEMPLATE.'main', array(
                                        'set' => $this->set,
                                        'pages' => $this->pageItem
                                        ));

        $this->page = parent::outputData();

        return $this->page;
    }

}
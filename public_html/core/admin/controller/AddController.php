<?php
namespace core\admin\controller;

class AddController extends BaseAdmin{

    protected function inputData($parameters){

        parent::inputData();

        if($this->isPost()){
            $this->clearPostFields($_POST);
            $this->editData();
        }

        $this->table = array_keys($parameters)[0];
        $res = $this->object_model->showColumns($this->table);

        if($res){
             $this->createOutputData($res);
        }

        if(in_array('menu_pos', $this->columns)){
            $this->menu_pos = $this->object_model->get($this->table,
                ['fields' => ['COUNT(*) AS count']
                ])[0]['count'] + 1;
        }

    }

    protected function outputData(){

        $this->content = $this->render(ADMIN_TEMPLATE.'addpage_new', array(
                                                'main_pages' => $this->main_pages,
                                                'table' => $this->table,
                                                'columns' => $this->columns,
                                                'templateArr' => $this->templateArr,
                                                'translate' => $this->translate
                                                ));

        $this->page = parent::outputData();
        return $this->page;

    }

    private function editData(){

        $table = $_POST['table'];

        unset($_POST['table']);

        $fileEdit = new \libraries\FileEdit();
        $this->fileArray = $fileEdit->addFile();

        /*if($this->fileArray['img'] && $_SESSION['crop_image']){
            foreach ($_SESSION['crop_image'] as $item) {
                $item['img'] = $_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$this->fileArray['img'];
                $this->fileArray['thumbnails'][] = $fileEdit->createJsThumbnail($item, CROP[$item['ratio']], $item['ratio']);
            }
        }*/

        $res = $this->object_model->add($table, [
            'files' => $this->fileArray
        ]);

        if($res){
            $_SESSION['res']['answer'] = '<div class="success">Страница успешно добавлена</div>';
            $this->redirect();
        }
    }
}
<?php
namespace core\admin\controller;

class SettingsController extends BaseAdmin{

    protected $set;

    protected function inputData($parameters){

        parent::inputData();

        if($this->isPost()){
            $this->clearPostFields($_POST);
            $this->editData();
        }

        $this->set = $this->object_model->get('settings')[0];
        $this->table = 'settings';
    }

    protected function outputData(){

        $this->content = $this->render(ADMIN_TEMPLATE.'settings', array(
                                            'set' => $this->set,
                                            'table' => $this->table
                                                ));

        $this->page = parent::outputData();
        return $this->page;

    }

    private function editData(){

        $fileEdit = new \libraries\FileEdit();
        $this->fileArray = $fileEdit->addFile();

        if($this->fileArray['gallery_img']){
            foreach ($this->fileArray['gallery_img'] as $item) {
                $fileEdit->createThumbnail($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$item, array('cut' => '1230|750'));
            }
            $gallery = $this->object_model->get('settings', [
                'fields' => ['gallery_img']
            ])[0]['gallery_img'];


            if($gallery){
                $this->fileArray{'gallery_img'}[] = $gallery;
            }
        }
        if($this->fileArray['img']){
            $images = $this->object_model->get('pages', [
                'fields' => ['img', 'thumbnails'],
                'where' => ['id' => $_POST['id']]
            ])[0];

            if($images){
                foreach($images as $image){
                    @unlink($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$image);
                }
            }
        }

        $res = $this->object_model->edit('settings', [
            'files' => $this->fileArray
        ]);

        if($res){
            $_SESSION['res']['answer'] = '<div class="success">Данные изменены</div>';
        }
        $this->redirect();

    }
}
<?php
namespace core\admin\controller;

class EditController extends BaseAdmin{

    protected $id_row;

    protected function inputData($parameters){

        parent::inputData();
        
        if($this->isPost()){
            $this->clearPostFields($_POST);

            $this->id_row = array_keys($_POST)[0];

            if(array_key_exists('menu_pos', $_POST)){
                $this->object_model->updateMenuPosition($_POST['table'], 'menu_pos', [$this->id_row => $_POST[$this->id_row]], $_POST['menu_pos']);
            }

            $this->editData();
        }

        if(empty($parameters)){
            $this->redirect(PATH.ADMIN_PATH);
        }

        $this->table = array_keys($parameters)[0];
        $id = $this->clearInt($parameters[$this->table]);

        $res = $this->object_model->showColumns($this->table);

        if($id){
            if($res){
                $this->id_row = $this->createOutputData($res);
            }

            if($this->id_row){
                $this->data = $this->object_model->get($this->table, ['where' => [$this->id_row => $id]])[0];
            }else{
                $this->data = $this->object_model->get($this->table)[0];
            }

            if(in_array('menu_pos', $this->columns)){
                $this->menu_pos = $this->object_model->get($this->table,
                        ['fields' => ['COUNT(*) AS count']
                        ])[0]['count'];
            }

        }else{
            $this->redirect(PATH.ADMIN_PATH . '/'. $this->table);
        }

    }

    protected function outputData(){

        $this->content = $this->render(ADMIN_TEMPLATE.'editpage_new', array(
                                            'data' => $this->data,
                                            'table' => $this->table,
                                            'columns' => $this->columns,
                                            'templateArr' => $this->templateArr,
                                            'translate' => $this->translate,
                                            'menu_pos' => $this->menu_pos,
                                            'id_row' => $this->id_row
                                        ));

        $this->page = parent::outputData();
        return $this->page;

    }

    private function editData(){

        if($_POST[$this->id_row]){

            $id = $_POST[$this->id_row];
            $table = $_POST['table'];
            unset($_POST['table']);
            unset($_POST[$this->id_row]);

            $fileEdit = new \libraries\FileEdit();
            $this->fileArray = $fileEdit->addFile();

            if($this->fileArray{'gallery_img'}){
//                foreach ($this->fileArray['gallery_img'] as $item) {
//                    $fileEdit->createThumbnail($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$item, array('cut' => '1230|750'));
//                }
                $gallery = $this->object_model->get($table, ['fields' => ['gallery_img'], 'where' => [$this->id_row => $id]])[0]['gallery_img'];
                if($gallery){
                    $this->fileArray{'gallery_img'}[] = $gallery;
                }
            }

            if($this->fileArray['img']){
                $images = $this->object_model->get($table, [
                    'fields' => ['img', 'thumbnails'],
                    'where' => [$this->id_row => $id]
                ])[0];
                if($images){
                    foreach($images as $image){
                        @unlink($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$image);
                    }
                }
            }

            $res = $this->object_model->edit($table, ['files' => $this->fileArray, 'where' => [$this->id_row => $id]]);
            if($res){
                $_SESSION['res']['answer'] = '<div class="success">Данные изменены</div>';
            }
            $this->redirect();
        }else{
            $_SESSION['res']['answer'] = '<div class="error">Не корректный идентификатор</div>';
            $this->redirect();
        }
    }
}
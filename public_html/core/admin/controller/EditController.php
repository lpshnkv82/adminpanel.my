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
                $res_arr = $this->createOutputData($res);
                $this->id_row = $res_arr['id_row'];
            }

            if($this->id_row){
                $this->data = $this->object_model->get($this->table, ['where' => [$this->id_row => $id]])[0];
            }else{
                $this->data = $this->object_model->get($this->table)[0];
            }

            if(!$this->data){
                if($this->table){
                    $this->redirect(PATH.ADMIN_PATH . '/show/' . $this->table);
                }else{
                    $this->redirect(PATH.ADMIN_PATH);
                }
            }

            if($res_arr['menu_pos']){
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

            foreach($this->blockNeedle as $index => $value){
                if(strpos($index, 'img') !== false){
                    foreach($value as $item){
                        if(strpos($item, 'gallery') !== false){
                            if($this->fileArray{$item}){
            //                foreach ($this->fileArray['gallery_img'] as $item) {
            //                    $fileEdit->createThumbnail($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$item, array('cut' => '1230|750'));
            //                }
                                $gallery = $this->object_model->get($table, ['fields' => [$item], 'where' => [$this->id_row => $id]])[0][$item];
                                if($gallery){
                                    $this->fileArray[$item][] = $gallery;
                                }
                                continue;
                            }
                        }else{
                            if($this->fileArray[$item]){
                                $images = $this->object_model->get($table, [
                                    'fields' => [$item],
                                    'where' => [$this->id_row => $id]
                                ])[0];

                                if($images){
                                    foreach($images as $image){
                                        @unlink($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$image);
                                    }
                                }
                            }
                        }
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
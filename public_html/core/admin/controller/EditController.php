<?php
namespace core\admin\controller;

class EditController extends BaseAdmin{

    protected $id_row;
    protected $resArr;

    protected function inputData($parameters){

        parent::inputData();
        
        if($this->isPost()){
            $this->clearPostFields($_POST);

            $this->id_row = array_keys($_POST)[0];

            if(array_key_exists('menu_pos', $_POST)){
                if($_POST['table'] == 'pages'){
                    $this->object_model->updateMenuPosition($_POST['table'], 'menu_pos', [$this->id_row => $_POST[$this->id_row]], $_POST['menu_pos'], ['where' => 'parent_id']);
                }else{
                    $this->object_model->updateMenuPosition($_POST['table'], 'menu_pos', [$this->id_row => $_POST[$this->id_row]], $_POST['menu_pos']);
                }
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
                $this->resArr = $this->createOutputData($res);
                $this->id_row = $this->resArr['id_row'];
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


            if($this->resArr['parent_id']){

                if($this->resArr['menu_pos']){
                    $this->menu_pos = $this->object_model->get($this->table, [
                            'fields' => ['COUNT(*) AS count'],
                            'where' => ['parent_id' => $this->data['parent_id']]
                        ])[0]['count'];
                }

                $this->parents = $this->object_model->get($this->table, [
                    'fields' => [$this->resArr['id_row'], $this->resArr['name_row']],
                    'where' => ['parent_id' => 0, $this->id_row => $id],
                    'operand' => ['=', '<>']
                ]);

            }elseif($this->resArr['menu_pos']){

                $this->menu_pos = $this->object_model->get($this->table,
                    ['fields' => ['COUNT(*) AS count']
                    ])[0]['count'];

            }

        }else{
            $this->redirect(PATH.ADMIN_PATH . '/show/'. $this->table);
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
                                            'id_row' => $this->id_row,
                                            'parents' => $this->parents,
                                            'res_arr' => $this->resArr
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

            if($_SESSION['ajax_image']){
                unset($_FILES[$_SESSION['ajax_image']]);
                unset($_SESSION['ajax_image']);
            }

            $fileEdit = new \libraries\FileEdit();
            $this->fileArray = $fileEdit->addFile();

            foreach($this->fileArray as $key => $value){
                if(is_array($value)){
                    $gallery = $this->object_model->get($table, ['fields' => [$key], 'where' => [$this->id_row => $id]])[0][$key];
                    if($gallery){
                        $this->fileArray[$key][] = $gallery;
                    }
                }else{
                    $image = $this->object_model->get($table, [
                        'fields' => [$key],
                        'where' => [$this->id_row => $id]
                    ])[0][$key];

                    if($image){
                        @unlink($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$image);
                    }
                }
            }

            /*foreach($this->blockNeedle as $index => $value){
                if(strpos($index, 'img') !== false){
                    foreach($value as $item){
                        if(strpos($item, 'gallery') !== false){
                            if($this->fileArray{$item}){
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
            }*/

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
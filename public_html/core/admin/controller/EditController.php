<?php
namespace core\admin\controller;

class EditController extends BaseAdmin{

    protected $fileArray = [];
    protected $pageItem = [];
    protected $main_pages = [];
    protected $menu_pos;
    protected $columns;

    protected function inputData($parameters){

        parent::inputData();
        
        if($this->isPost()){
            $this->clearPostFields($_POST);

            $this->object_model->updateMenuPosition($_POST['table'], 'menu_pos', ['id' => $_POST['id']], $_POST['menu_pos']);

            $this->editData();
        }

        $this->table = array_keys($parameters)[0];
        $id = $this->clearInt($parameters[$this->table]);

        $res = $this->object_model->showColumns($this->table);

        if($id){
            if($res){

                $id_row = false;

                foreach($res as $col){
                    $insert = false;
                    $default = false;
                    foreach($this->blockNeedle as $key => $item){
                        if(empty($item)){
                            $default = $key;
                            continue;
                        }
                        if(in_array($col['Field'], $item)){
                            $this->columns[$key][] = $col['Field'];
                            $insert = true;
                            break;
                        }
                    }
                    if(!$insert){
                        if($default){
                            $this->columns[$default][] = $col['Field'];
                        }else{
                            $this->columns['default'][] = $col['Field'];
                        }
                    }

                    if(!array_key_exists($col['Field'], $this->translate)){
                        $this->translate[$col['Field']][0] = $col['Field'];
                        //$this->translate[$col['Field']][0] = $this->yaTranslate($col['Field'], true);
                    }

                    if($col['Key'] == 'PRI') $id_row = $col['Field'];
                }
                ksort($this->columns);
                reset($this->columns);
            }

            if($id_row){
                $this->data = $this->object_model->get($this->table, ['where' => [$id_row => $id]])[0];
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

        $this->content = $this->render(ADMIN_TEMPLATE.'addpage_new', array(
                                            'data' => $this->data,
                                            'table' => $this->table,
                                            'columns' => $this->columns,
                                            'templateArr' => $this->templateArr,
                                            'translate' => $this->translate,
                                            'menu_pos' => $this->menu_pos
                                        ));

        $this->page = parent::outputData();
        return $this->page;

    }

    private function editData(){

        if($_POST['id']){

            $table = $_POST['table'];
            unset($_POST['table']);

            $fileEdit = new \libraries\FileEdit();
            $this->fileArray = $fileEdit->addFile();

            if($this->fileArray{'gallery_img'}){
//                foreach ($this->fileArray['gallery_img'] as $item) {
//                    $fileEdit->createThumbnail($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$item, array('cut' => '1230|750'));
//                }
                $gallery = $this->object_model->get($table, ['fields' => ['gallery_img'], 'where' => ['id' => $_POST['id']]])[0]['gallery_img'];
                if($gallery){
                    $this->fileArray{'gallery_img'}[] = $gallery;
                }
            }

            if($this->fileArray['img']){
                $images = $this->object_model->get($table, [
                    'fields' => ['img', 'thumbnails'],
                    'where' => ['id' => $_POST['id']]
                ])[0];
                if($images){
                    foreach($images as $image){
                        @unlink($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$image);
                    }
                }
            }

            $res = $this->object_model->edit($table, ['files' => $this->fileArray]);
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
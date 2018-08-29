<?php
namespace core\admin\controller;

class AddController extends BaseAdmin{

    protected $resArr;
    protected function inputData($parameters){

        parent::inputData();

        if($this->isPost()){
            $this->clearPostFields($_POST);

            if(array_key_exists('menu_pos', $_POST)){
                if($_POST['table'] == 'pages'){
                    $this->object_model->updateMenuPosition($_POST['table'], 'menu_pos', false, $_POST['menu_pos'], ['where' => 'parent_id']);
                }else{
                    $this->object_model->updateMenuPosition($_POST['table'], 'menu_pos', false, $_POST['menu_pos']);
                }
            }

            $this->editData();
        }

        $this->table = array_keys($parameters)[0];
        $res = $this->object_model->showColumns($this->table);

        if($res){
             $this->resArr = $this->createOutputData($res);
        }

        if($this->resArr['parent_id']){

            if($this->resArr['menu_pos']){
                $this->menu_pos = $this->object_model->get($this->table, [
                    'fields' => ['COUNT(*) AS count'],
                    'where' => ['parent_id' => $this->data['parent_id']]
                ])[0]['count'] + 1;
            }

            $this->parents = $this->object_model->get($this->table, [
                'fields' => [$this->resArr['id_row'], $this->resArr['name_row']],
                'where' => ['parent_id' => 0]
            ]);

        }elseif($this->resArr['menu_pos']){

            $this->menu_pos = $this->object_model->get($this->table,
                ['fields' => ['COUNT(*) AS count']
                ])[0]['count'] + 1;

        }

        return;
    }

    protected function outputData(){

        $this->content = $this->render(ADMIN_TEMPLATE.'addpage_new', array(
                                                'main_pages' => $this->main_pages,
                                                'table' => $this->table,
                                                'columns' => $this->columns,
                                                'templateArr' => $this->templateArr,
                                                'translate' => $this->translate,
                                                'menu_pos' => $this->menu_pos,
                                                'parents' => $this->parents,
                                                'res_arr' => $this->resArr
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
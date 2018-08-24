<?php
namespace core\admin\controller;

class DeleteController extends BaseAdmin{

    protected function inputData($parameters){

        parent::inputData();

        if(!empty($parameters)){

            $table = $parameters['table'];
            $id_row = $parameters['id_row'];
            $id = $parameters['id'];

            if ($id && $id_row && $table) {

                $res = $this->object_model->get($table, ['where' => [$id_row => $id]])[0];

                if ($res) {

                    foreach($this->blockNeedle as $index => $value){
                        if(strpos($index, 'img') !== false){
                            foreach($value as $item){
                                if(strpos($item, 'gallery') !== false){
                                    if ($res[$item]) {
                                        $gal_img = explode('|', $res[$item]);
                                        foreach ($gal_img as $img) {
                                            @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $img);
                                        }
                                    }
                                }else{
                                    if ($res[$item]) {
                                        @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $res[$item]);
                                    }
                                }
                            }
                        }
                    }

                    if ($res['menu_pos']) {

                        if(array_key_exists('parent_id', $res)){
                            $pos = $this->object_model->get($table,
                                ['fields' => ['COUNT(*) AS count'],
                                    'where' => ['parent_id' => $res['parent_id']]
                                ])[0]['count'];
                            $this->object_model->updateMenuPosition($table, 'menu_pos', [$id_row => $id], $pos, ['where' => ['parent_id']]);
                        }else{
                            $pos = $this->object_model->get($table,
                                ['fields' => ['COUNT(*) AS count']
                                ])[0]['count'];

                            $this->object_model->updateMenuPosition($table, 'menu_pos', [$id_row => $id], $pos);
                        }
                    }
                }

                $this->object_model->delete($table, $id, $id_row);
                $this->redirect(PATH . ADMIN_PATH . '/show/'. $table);
            }
        }
    }
}
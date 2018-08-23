<?php
namespace core\admin\controller;

class DeleteController extends BaseAdmin{

    protected function inputData($parameters){

        parent::inputData();

        if(!empty($parameters)){

            foreach($parameters as $key => $value){
                $table = $this->clearStr($key);
                $id = $this->clearInt($value);
                break;
            }

            if ($id && $table) {

                $res = $this->object_model->get($table, ['where' => ['id' => $id]])[0];

                if ($res) {
                    if ($res['img']) {
                        @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $res['img']);
                    }
                    if ($res['thumbnails']) {
                        $thumbs = explode("|", $res['thumbnails']);
                        foreach($thumbs as $thumb){
                            @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $thumb);
                        }
                    }
                    if ($res['gallery_img']) {
                        $gal_img = explode('|', $res['gallery_img']);
                        foreach ($gal_img as $img) {
                            @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $img);
                        }
                    }

                    if ($res['menu_pos']) {

                        if($table == 'pages'){
                            $pos = $this->object_model->get($table,
                                ['fields' => ['COUNT(*) AS count'],
                                    'where' => ['parent_id' => $res['parent_id']]
                                ])[0]['count'];
                        }else{
                            $pos = $this->object_model->get($table,
                                ['fields' => ['COUNT(*) AS count']
                                ])[0]['count'];
                        }

                        if ($res['parent_id']) {
                            $this->object_model->updateMenuPosition($table, 'menu_pos', ['id' => $id], $pos, ['where' => ['parent_id']]);
                        } else {
                            $this->object_model->updateMenuPosition($table, 'menu_pos', ['id' => $id], $pos);
                        }
                    }
                }

                $this->object_model->delete($table, $id);
                $this->redirect(PATH . ADMIN_PATH);
            }
        }
    }
}
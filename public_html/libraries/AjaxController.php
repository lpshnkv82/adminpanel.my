<?php
namespace libraries;

use core\base\controller\BaseController;
use core\admin\model\Model;
use core\user\model\Model as ModelUser;

class AjaxController extends BaseController{

    protected $ajaxData;
    protected $object_model;
    protected $admin_model;

    public function __construct(){
        $this->object_model = ModelUser::getInstance();
        $this->admin_model = Model::getInstance();

        switch($_POST['ajax']){
            case 'delete_gallery_img':
                $this->ajaxData = json_decode($_POST['data'], true);
                exit(json_encode($this->deleteGalleryImg()));
                break;

            case 'sort_gallery_img':
                $this->ajaxData = json_decode($_POST['data'], true);
                exit(json_encode($this->sortGalleryImg()));
                break;

            case 'sort_table':
                $this->ajaxData = json_decode($_POST['data'], true);
                exit(json_encode($this->sortTable()));
                break;

            case 'search':
                exit(json_encode($this->ajaxSearch()));
                break;

            case 'change_parent':
                $this->ajaxData = json_decode($_POST['data'], true);
                exit(json_encode($this->changeParent()));
                break;

            case 'send_mail':
                exit(json_encode($this->sendMail()));
                break;

            case 'crop':
                $this->ajaxData = json_decode($_POST['data'], true);
                exit($this->createJsThumbnail());
                break;

            case 'upload_image':
                exit($this->uploadImage());
                break;
        }
    }

    protected function sortTable(){

        $columns = $this->admin_model->showColumns($this->ajaxData['table']);

        foreach($columns as $column){
            if($column['Field'] == $this->ajaxData['current']){
                $type = $column['Type'];
                break;
            }
        }

        if($this->ajaxData['first']){
            $query = "ALTER TABLE {$this->ajaxData['table']} MODIFY COLUMN {$this->ajaxData['current']} $type FIRST";
        }else{
            $query = "ALTER TABLE {$this->ajaxData['table']} MODIFY COLUMN {$this->ajaxData['current']} $type AFTER {$this->ajaxData['after']}";
        }

        return $this->admin_model->customQuery($query);
    }

    protected function uploadImage(){
        $fileEdit = new FileEdit();
        $file = $fileEdit->addFile();
        $res = $this->admin_model->edit($_POST['table'], [
            'fields' => $file,
            'where' => [$_POST['id_row'] => $_POST['id']]
        ]);

        if($res){
            $thumbs = $this->admin_model->get($_POST['table'], [
                'fields' => ['thumbnails'],
                'where' => [$_POST['id_row'] => $_POST['id']]
            ])[0]['thumbnails'];

            if($thumbs){
                $thumbs = explode("|", $thumbs);

                foreach($thumbs as $index => $thumb){
                    if(strpos($thumb,$_POST['img_row'].'thumbnails_') === 0){
                        unset($thumbs[$index]);
                        @unlink($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$thumb);
                        break;
                    }
                }

                $new_thumb['thumbnails'] = implode("|", $thumbs);

                $this->admin_model->edit($_POST['table'], [
                    'fields' => $new_thumb,
                    'where' => [$_POST['id_row'] => $_POST['id']]
                ]);
            }
        }
        $_SESSION['ajax_image'] = $_POST['img_row'];
        return PATH.UPLOAD_DIR.reset($file);
    }

    protected function changeParent(){
        return $this->admin_model->get($this->ajaxData['table'], [
                'fields' => ['COUNT(*) AS count'],
                'where' => ['parent_id' => $this->ajaxData['parent_id']]
            ])[0]['count'] + 1;
    }

    protected function ajaxSearch(){

        $data = $this->clearStr($_POST['data']);
        return $this->admin_model->adminSearch($data);
    }

    protected function sortGalleryImg(){
        $id = $this->ajaxData['id'];
        $id_row = $this->ajaxData['id_row'];
        $row = $this->ajaxData['row'];
        $table = $this->ajaxData['table'];
        if($id && $row){
            unset($this->ajaxData['id']);
            unset($this->ajaxData['row']);
            unset($this->ajaxData['id_row']);
            unset($this->ajaxData['table']);

            $data = [];

            $data[$id_row] = $id;
            $data[$row] = implode("|", $this->ajaxData);

            if($this->admin_model->edit($table, ['fields' => $data])){
                return true;
            }else{
                return false;
            }
        }
    }

    protected function createJsThumbnail(){
        $this->ajaxData['id'] = $this->clearInt($this->ajaxData['id']);

        if($this->ajaxData['id']){
            $fileEdit = new FileEdit();
            $this->ajaxData['img'] = $_SERVER['DOCUMENT_ROOT'].$this->ajaxData['img'];
            $file = $fileEdit->createJsThumbnail($this->ajaxData, $this->ajaxData['thumb_name']);
            if($file && $this->ajaxData['tbl']){

                /*Метод для одиночного изображения*/
                $new_gal = array();
                if($this->ajaxData['id'] && $this->ajaxData['id_row']){
                    $new_gal[$this->ajaxData['id_row']] = $this->ajaxData['id'];
                }else{
                    $all_rows = true;
                }
                $thumbs = $this->admin_model->get($this->ajaxData['tbl'], [
                    'fields' => ['thumbnails'],
                    'where' => [$this->ajaxData['id_row'] => $this->ajaxData['id']]
                ])[0]['thumbnails'];

                if($thumbs){
                    $thumbs = explode("|", $thumbs);
                    $write_flag = false;
                    foreach($thumbs as $index => $thumb){
                        if(strpos($thumb,$this->ajaxData['thumb_name'].'_') === 0){
                            $thumbs[$index] = $file;
                            $write_flag = true;
                            break;
                        }
                    }
                    if(!$write_flag) $thumbs[] = $file;
                }else{
                    $thumbs[] = $file;
                }

                $new_gal['thumbnails'] = implode("|", $thumbs);
                $this->admin_model->edit($this->ajaxData['tbl'], ['fields' => $new_gal, 'all_rows' => $all_rows]);
                /*Метод для одиночного изображения*/

                /*метод для обновления строки шаблонов*/
                /*
                $images = $this->admin_model->get($this->ajaxData['tbl'], [
                    'fields' => ['thumbnails'],
                    'where' => ['id' => $this->ajaxData['id']]
                ]);

                if(!empty($images[0]['thumbnails'])){
                    $arrImg = explode('|', $images[0]['thumbnails']);
                }
                if(!in_array($file, $arrImg)){
                    $arrImg[] = $file;
                    $new_gal = array();
                    $new_gal['id'] = $this->ajaxData['id'];
                    $new_gal['thumbnails'] = implode('|', $arrImg);
                    $this->admin_model->edit($this->ajaxData['tbl'], ['fields' => $new_gal]);
                }*/
                /*метод для обновления строки шаблонов*/
                return PATH.UPLOAD_DIR.$file;
                }
                return false;
        }else{
            unset($this->ajaxData['img']);
            $_SESSION['crop_image'][] = $this->ajaxData;
            return 'add_session';
        }
    }

    protected function sendMail(){
        foreach ($_POST as $key => $value) {
            if (empty($value)) {
                return array('send' => 0, 'message' => $key);
            }
        }

        $set = $this->object_model->get('settings')[0];

        $subject = "Сообщение с сайта ".$set['name'];

        //Заголовки
        //$headers = "From: ".$_POST['email']."\nReply-To: " . $_POST['email'] . "\n";
        $headers = "Content-type: text/plain; charset=utf-8\r\n";
        $headers .= "Сообщение с сайта ".$set['name'];

        //Тело письма
        $mail_body = "Имя клиента - ".$_POST['name']."\r\n\r\n".
            "Телефон - ".$_POST['phone']."\r\n";
        //Отправка писем

        if(mail($set['email'], $subject, $mail_body, $headers)){
            return array('send' => 1, 'message' => 'Сообщение отправлено');
        }

    }

    protected function deleteGalleryImg(){
        $id = $this->ajaxData['id'];
        $id_row = $this->ajaxData['id_row'];
        if(strrpos($this->ajaxData['img_src'], "?") !== false){
            $img = substr($this->ajaxData['img_src'], 0, strrpos($this->ajaxData['img_src'], "?"));
        }else{
            $img = $this->ajaxData['img_src'];
        }
        $table = $this->ajaxData['table'];

        if(strpos($this->ajaxData['row'], 'gallery') === false){
            $row = 'thumbnails';
        }else{
            $row = $this->ajaxData['row'];
        }

        if($id && $id_row){
            $images = $this->admin_model->get($table, [
                'fields' => [$row],
                'where' => [$id_row => $id]
            ]);
        }else{
            $images = $this->admin_model->get($table, [
                'fields' => [$row]
            ]);
        }

        if($images){
            $arrImg = explode('|', $images[0][$row]);
            foreach($arrImg as $key => $value){
                if($value == $img){
                    unset($arrImg[$key]);
                    break;
                }
            }
            $new_gal = [];
            $all_rows = false;
            if($id && $id_row){
                $new_gal[$id_row] = $id;
            }else{
                $all_rows = true;
            }
            $new_gal[$row] = implode('|', $arrImg);

            if($this->admin_model->edit($table, ['fields' => $new_gal, 'all_rows' => $all_rows])){
                @unlink($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$img);
                return true;
            }
        }else{
            return false;
        }

    }
}
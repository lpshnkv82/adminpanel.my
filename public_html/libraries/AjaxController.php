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

            case 'search':
                exit(json_encode($this->ajaxSearch()));
                break;
            case 'send_mail':
                exit(json_encode($this->sendMail()));
                break;

            case 'crop':
                $this->ajaxData = json_decode($_POST['data'], true);
                exit($this->createJsThumbnail());
                break;
        }
    }

    protected function ajaxSearch(){
        $temp_tables = $this->object_model->showTables();
        $data = $this->clearStr($_POST['data']);
        $result = [];
        foreach($temp_tables as $item){
            $table = reset($item);
            if(!in_array($table, EXCEPTION_TABLES)){

                $res = $this->object_model->showColumns($table);

                $fields = '';
                $where = '';
                foreach($res as $col){
                    $columns[] = $col['Field'];

                    if($col['Key'] == 'PRI') $fields .= $col['Field'] . ',';

                    if(mb_strpos($col['Field'], 'name') !== false){
                        $fields[] = $col['Field'];

                        if(!$where){
                            $where .= "WHERE {$col['Field']} LIKE %$data%";
                        }else{
                            $where .= " OR {$col['Field']} LIKE %$data%";
                        }
                    }

                    if(mb_strpos($col['Field'], 'content') !== false){
                        if(!$where){
                            $where .= "WHERE {$col['Field']} LIKE %$data%";
                        }else{
                            $where .= " OR {$col['Field']} LIKE %$data%";
                        }
                    }

                    if(mb_strpos($col['Field'], 'description') !== false){
                        if(!$where){
                            $where .= "WHERE {$col['Field']} LIKE %$data%";
                        }else{
                            $where .= " OR {$col['Field']} LIKE %$data%";
                        }
                    }

                }

                $fields = rtrim($fields, ',');
                $result[$table] = $this->admin_model->ajaxSearch($fields, $table, $where);
            }
        }

        exit($_POST['data']);
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

        if($this->ajaxData['id'] || $this->ajaxData['tbl'] == 'settings'){
            $fileEdit = new FileEdit();
            $this->ajaxData['img'] = $_SERVER['DOCUMENT_ROOT'].$this->ajaxData['img'];
            $file = $fileEdit->createJsThumbnail($this->ajaxData, CROP[$this->ajaxData['ratio']], $this->ajaxData['ratio']);
            if($file && $this->ajaxData['tbl']){

                /*Метод для одиночного изображения*/
                $new_gal = array();
                if($this->ajaxData['id']){
                    $new_gal['id'] = $this->ajaxData['id'];
                }else{
                    $all_rows = true;
                }
                $new_gal['thumbnails'] = $file;
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
            $_SESSION['crop_image'][$this->ajaxData['ratio']] = $this->ajaxData;
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
        $img = $this->ajaxData['img_src'];
        $table = $this->ajaxData['table'];

        switch($this->ajaxData['row']){
            case 'img':
                $row = 'thumbnails';
                break;

            default:
                $row = $this->ajaxData['row'];
                break;
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
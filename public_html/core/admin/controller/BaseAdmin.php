<?php
namespace core\admin\controller;
use core\admin\model\Model;
use core\base\model\ModelUser;

class BaseAdmin extends \core\base\controller\BaseController{

    protected $object_model;
    protected $object_model_user;
    protected $title;
    protected $content;
    protected $user = true;
    protected $user_access = true;

    protected $display_flag = false;
    protected $tables; //генерация добавления разделов
    protected $table; // Текущая таблица
    protected $data;
    protected $fileArray;
    protected $columns;
    protected $menu_pos;
    protected $main_pages;
    protected $parents;

    protected $leftMenu;
    protected $translate;
    protected $templateArr;
    protected $blockNeedle;
    protected $exceptionTables;

    protected $Settings;


    protected function inputData(){

        if($this->user){
            $this->checkAuth();
            if($this->user_type != 'admin'){
                $this->redirect(PATH);
            }
        }

        $this->init(true);

        $this->Settings = new \core\base\settings\Settings();

        $this->leftMenu = $this->Settings->getLeftMenu();
        $this->translate = $this->Settings->getTranslate();
        $this->templateArr = $this->Settings->getTemplateArr();
        $this->blockNeedle = $this->Settings->getBlockNeedle();
        $this->exceptionTables = $this->Settings->getExceptionTables();

        $this->title = 'VG engine';


        $this->object_model = Model::getInstance();
        $this->object_model_user = ModelUser::getInstance();

        $tables = $this->object_model->showTables();

        foreach($tables as $item){
            $value = reset($item);
            if($this->exceptionTables){
                if(!in_array($value, $this->exceptionTables)){
                    $this->tables[] = $value;
                }
            }else{
                $this->tables[] = $value;
            }
        }

        $this->sendNoCacheHeaders();
    }

    protected function outputData(){

        unset($_SESSION['crop_image']);

        $header = $this->render(ADMIN_TEMPLATE.'include/header', array(
                                'title' => $this->title,
                                'styles' => $this->style,
                                'user_type' => $this->user_type,
                                'user_name' => $this->user_name,
                                'user_id' => $this->user_id,
                                'tables' => $this->tables,
                                'table' => $this->table,
                                'leftMenu' => $this->leftMenu
                                ));

        $footer = $this->render(ADMIN_TEMPLATE.'include/footer',array(
                                'scripts' => $this->script,
                                ));

        $page = $this->render(ADMIN_TEMPLATE.'index', array(
                                'header' => $header,
                                'content' => $this->content,
                                'footer' => $footer
                                ));
        return $page;
    }

    protected function addSessionData(){
        if($this->isPost()){
            foreach ($_POST as $key => $value){
                $_SESSION['res'][$key] = $value;
            }
            $this->redirect();
        }
    }

    protected function emptyFields($value, $answer){
        if(empty($value)){
            $_SESSION['res']['answer'] = '<div class="error">'.$answer.'</div>';
            $this->addSessionData();
        }
        if(is_int($value)){
            if($value){
                return;
            }else{
                $_SESSION['res']['answer'] = '<div class="error">Не корректное числовое значение</div>';
                $this->addSessionData();
            }
        }
    }

    protected function countChar($value, $counter, $answer){
        if(mb_strlen($value) > $counter){
            $_SESSION['res']['answer'] = '<div class="error">'.$answer.'</div>';
            $this->addSessionData();
        }
    }

    protected function clearPostFields($arr, $unset = false){
        $validate = $this->Settings->getValidation();

        foreach($arr as $key => $value){
            if(is_array($value)){
                $this->clearPostFields($value);
            }else{
                if(is_numeric($value)){
                    $value = $this->clearInt($value);
                }else{
                    $value = $this->clearStr($value);
                }
                if($unset){
                    if(empty($value)){
                        unset($arr[$key]);
                        continue;
                    }
                }

                if($validate){
                    if(array_key_exists($key, $validate)){
                        if($this->translate[$key]){
                            $answer = $this->translate[$key][0];
                        }else{
                            $answer = $key;
                        }

                        if($validate[$key]['empty']){
                            $this->emptyFields($value, 'Не заполнено '.$answer);
                        }

                        if($validate[$key]['count']){
                            $this->countChar($value,  $validate[$key]['count'],'лина поля ' . $answer . ' превышает ' . $validate[$key]['count'] . 'символов');
                        }
                    }
                }
            }
        }
        return true;
    }

    protected function createOutputData($res, $return = false){

        $res_arr = [
            'id_row' => false,
            'name_row' => false,
            'menu_pos' => false,
            'parent_id' => false
        ];
        $temp_arr = [];
        foreach($res as $col){
            $insert = false;
            $default = false;
            foreach($this->blockNeedle as $key => $item){
                if(empty($item)){
                    $default = $key;
                    continue;
                }
                if(in_array($col['Field'], $item)){
                    $temp_arr[$key][] = $col['Field'];
                    $insert = true;
                    break;
                }
            }
            if(!$insert){
                if($default){
                    $temp_arr[$default][] = $col['Field'];
                }else{
                    $temp_arr['default'][] = $col['Field'];
                }
            }

            if(!array_key_exists($col['Field'], $this->translate)){
                $this->translate[$col['Field']][0] = $col['Field'];
                //$this->translate[$col['Field']][0] = $this->yaTranslate($col['Field'], true);
            }

            if(is_array($res_arr)){
                if($col['Key'] == 'PRI'){
                    if(!$res_arr['id_row']) $res_arr['id_row'] = $col['Field'];
                }
                if(!$res_arr['name_row']){
                    if(strpos($col['Field'], 'name') !== false) $res_arr['name_row'] = $col['Field'];
                }
                if(in_array($col['Field'], $res_arr)) $res_arr[$col['Field']] = true;
            }
        }

        foreach ($this->blockNeedle as $index => $item) {
            $this->columns[$index] = $temp_arr[$index];
            unset($temp_arr[$index]);
        }

        if($temp_arr['default']){
            $this->columns['default'] = $temp_arr['default'];
        }

        return $res_arr;
    }

    protected function yaTranslate($string, $uppercase = false){
        if( $curl = curl_init() ) {
            $yandexParameters = $this->Settings->getYandexTranslateParameters();

            $url = $yandexParameters['url'];
            $apiKey = $yandexParameters['key'];
            $text = 'text='.$string;
            $lang = 'lang=en-ru';

            $url_str = $url . '?' . $apiKey . '&' . $text. '&' . $lang;

            curl_setopt($curl, CURLOPT_URL, $url_str);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

            $out = curl_exec($curl);
            curl_close($curl);

            $xmlData = new \SimpleXMLElement($out);

            $str = (string)$xmlData->text[0];

            if($uppercase){
                return mb_substr(mb_strtoupper($str, 'utf-8'), 0, 1, 'utf-8') . mb_substr($str, 1, mb_strlen($str)-1, 'utf-8');
            }

            return $str;
        }
    }

    private function sendNoCacheHeaders(){
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s")." GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Cache-Control: post-check=0,pre-check=0", false);
        header("Cache-Control: max-age=0", false);
        header("Pragma: no-cache");
    }

}
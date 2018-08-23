<?php
namespace core\user\controller;
use core\user\model\Model;

 abstract class BaseUser extends \core\base\controller\BaseController{
     protected $object_model;
     protected $title; //Title страницы
     protected $keywords; //Ключевики страницы
     protected $description; //Description страницы
     protected $style; //Полный путь к стилям сайта
     protected $script; //Полный путь к сскриптам сайта
     protected $header; //Хранение шаблона HEADER
     protected $sidebar; //Хранение сайдбара
     protected $content; //Хранение динамического контента сайта
     protected $footer; //Хранение футера сайта
     protected $menu; //Хранение меню сайта
     protected $display_flag = false; //флаг отображения блоков если необходимо

     protected $pageItem;
     private $menu_class;
     protected $set;
     protected $template;

     protected function inputData(){
        //$this->title = MAIN_TITLE;

         foreach ($this->styles as $style){
             $this->style[] = PATH.TEMPLATE.$style;
         }

         foreach ($this->scripts as $script){
             $this->script[] = PATH.TEMPLATE.$script;
         }

         $this->object_model = Model::getInstance();

         $this->addActiveMenu();

         $this->set = $this->object_model->get('settings')[0];

         if($this->set['phone']){
             $this->set['phone'] = explode(",", $this->set['phone']);
         }
     }

     protected function outputData(){

         if(!$this->display_flag){
             $this->header = $this->render(TEMPLATE.'include/header', array(
                 'styles' => $this->style,
                 'title' => $this->title,
                 'keywords' => $this->keywords,
                 'description' => $this->description,
                 'set' => $this->set,
                 'menu_class' => $this->menu_class
             ));

             $this->footer = $this->render(TEMPLATE.'include/footer', array(
                 'scripts' => $this->script,
                 'set' => $this->set,
             ));
         }


         $page = $this->render(TEMPLATE.'index', array(
                                                        'header' => $this->header,
                                                        'content' => $this->content,
                                                        'footer' => $this->footer
                                                        ));
         return $page;//Возвращаем готовую страницу
     }

     protected function addActiveMenu(){

         $url = $_SERVER['REQUEST_URI'];

         if(strrpos($url, 'contacts')){
             $this->menu_class['contacts'] = 'active';
         }elseif (strrpos($url, 'categories') || strrpos($url, 'category')){
             $this->menu_class['categories'] = 'active';
         }elseif (strrpos($url, 'portfolio')){
             $this->menu_class['portfolio'] = 'active';
         }else{
             $this->menu_class['main'] = 'active';
         }
     }

     protected function createImgThumbs($arr){
        if(isset($arr[0])){
            foreach($arr as $key => $item){
                if(!empty($item['thumbnails']) && !strpos($item['thumbnails'], '|')){
                    $arr[$key]['img'] = $item['thumbnails'];
                }
            }
        }else{
            if(!empty($arr['thumbnails']) && !strpos($arr['thumbnails'], '|')){
                $arr['img'] = $arr['thumbnails'];
            }
        }
        return $arr;
     }
 }
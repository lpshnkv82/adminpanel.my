<?php
namespace core\user\controller;
use core\user\model\Model;

 abstract class BaseUser extends \core\base\controller\BaseController{
     protected $object_model;
     protected $title; //Title страницы
     protected $keywords; //Ключевики страницы
     protected $description; //Description страницы
     protected $header; //Хранение шаблона HEADER
     protected $sidebar; //Хранение сайдбара
     protected $content; //Хранение динамического контента сайта
     protected $footer; //Хранение футера сайта
     protected $menu; //Хранение меню сайта
     protected $display_flag = false; //флаг отображения блоков если необходимо

     protected $pageItem;
     private $menu_class;
     protected $set;
     protected $main_class = 'inner_page';
     protected $template;
     protected $contactsPage = false;

     protected function inputData(){

         $this->init();

         $this->object_model = Model::getInstance();

         $this->addActiveMenu();

         $this->set = $this->object_model->get('settings', ['limit' => '1'])[0];

         $this->set['short_name'] = substr($this->set['name'], 0, 1) . substr($this->set['name'], -1, 1);

         if($this->set['phone']){
             $this->set['phone'] = explode(",", $this->set['phone']);
         }

         if($this->set['success_phrase']){
             $phrase_arr = explode("\r\n", $this->set['success_phrase']);
             $this->set['success_phrase'] = $phrase_arr[mt_rand(0, count($phrase_arr) - 1)];
         }
     }

     protected function outputData(){

         $this->header = $this->render(TEMPLATE.'include/header', array(
             'styles' => $this->style,
             'title' => $this->title,
             'keywords' => $this->keywords,
             'description' => $this->description,
             'menu_class' => $this->menu_class,
             'set' => $this->set,
             'main_class' => $this->main_class
         ));

         if(!$this->display_flag){
             $this->footer = $this->render(TEMPLATE.'include/footer', array(
                 'display_flag' => $this->display_flag,
                 'scripts' => $this->script,
                 'set' => $this->set,
                 'contactsPage' => $this->contactsPage
             ));
         }else{
             $this->footer = $this->render(TEMPLATE.'include/footer', array(
                 'display_flag' => $this->display_flag,
                 'scripts' => $this->script,
                 'set' => $this->set,
                 'pages' => $this->pageItem,
                 'contactsPage' => $this->contactsPage
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
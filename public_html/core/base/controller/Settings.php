<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 23.08.2018
 * Time: 15:00
 */

namespace core\base\controller;


class Settings
{

    private $leftMenu = [
        'pages' => ['name' => 'Страницы', 'img' => 'pages.png'],
        'portfolio' => ['name' => 'Портфолио', 'img' => 'pages.png']
    ];

    private $translate = [
        'name' => ['Название'],
        'img' => ['Основное изображение'],
        'gallery_img' => ['Галлерея изображений'],
        'price' => ['Цена'],
        'keywords' => ['Ключевые слова', 'Максимум 70 символов'],
        'description' => ['Метаописание', 'Максимум 160 символов'],
        'content' => ['Описание'],
        'short_content' => ['Краткое описание'],
        'visible' => ['Показать на сайте'],
        'menu_pos' => ['Позиция в меню'],
        'parent_id' => ['Родительская категория'],
        /*Шаблон настроек сайта*/
        'phone' => ['Телефон', 'Введите номера телефонов через запятую'],
        'email' => ['E-mail'],
        'adress' => ['Адрес компании'],
        'sidebar_header' => ['Заголовок правого сайдбара'],
        'type' => ['Тип компании'],
        'success_phrase' => ['Промо высказывания']
        /*Шаблон настроек сайта*/
    ];

    private $templateArr = [
        'text' => ['name', 'price', 'type', 'sidebar_header', 'phone', 'email', 'adress'],
        'textarea' => ['keywords', 'description', 'content', 'short_content', 'success_phrase'],
        'radio' => ['visible'],
        'img' => ['img'],
        'gallery_img' => ['gallery_img'],
        'select' => ['menu_pos'],
        'select_parents' => ['parent_id']
    ];

    private $blockNeedle = [
        'vg-rows' => [],
        'vg-img' => ['img', 'gallery_img'],
        'vg-content' => ['content', 'short_content']
    ];

    private $validation = [
        'name' => ['empty' => true],
        'keywords' => ['count' => 70],
        'description' => ['empty' => true, 'count' => 160],
        'content' => ['empty' => true]
    ];

    private $yandexTranslate = [
        'url' => 'https://translate.yandex.net/api/v1.5/tr/translate',
        'key' => 'key=trnsl.1.1.20180821T133645Z.4f02615523209aec.3439cd97dcbfd60dff85a9f4dfdcf5059b501011'
    ];

    private $exceptionTables = ['users', 'users_types', 'fealtures'];


    public function getLeftMenu(){
        return $this->leftMenu;
    }

    public function getTranslate(){
        return $this->translate;
    }

    public function getTemplateArr(){
        return $this->templateArr;
    }

    public function getBlockNeedle(){
        return $this->blockNeedle;
    }

    public function getValidation(){
        return $this->validation;
    }

    public function getYandexTranslateParameters(){
        return $this->yandexTranslate;
    }

    public function getExceptionTables(){
        return $this->exceptionTables;
    }

}
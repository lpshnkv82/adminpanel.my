<?php
 function print_arr($arr){
     echo '<pre>';
     print_r($arr);
     echo '</pre>';
 }

function getCat($id, $arr, $parent_id = 0, $flag = false, $admin = false){
    if(empty($arr[$parent_id])) {
        return;
    }

    if(!$admin){
        $sourse = 'category/id/';
    }else{
        $sourse = 'editservices/id/';
    }

    for($i = 0; $i < count($arr[$parent_id]);$i++) {

        $active = '';
        if($id == $arr[$parent_id][$i]['id']){
            $active = 'style="color:#da8c35;"';
        }

        if(!$parent_id){
            if($flag){
                $flag = false;
                echo '</div>';
            }
            echo '<div class="categories">';
            echo '<div class="title-categories">';
            echo '<a class="menu_hide" href="'.PATH.$sourse.$arr[$parent_id][$i]['id'].'">';
            if(!$admin){
                if(!empty($arr[$parent_id][$i]['img_icon'])){
                    if(file_exists($_SERVER['DOCUMENT_ROOT'].PATH.TEMPLATE.'img/'.$arr[$parent_id][$i]['img_icon'])){
                        echo '<img src="'.PATH.TEMPLATE.'img/'.$arr[$parent_id][$i]['img_icon'].'" alt="icon">';
                    }
                }
            }
            echo '</a>';
            echo '<a '.$active.' href="'.PATH.$sourse.$arr[$parent_id][$i]['id'].'">'.$arr[$parent_id][$i]['name'].'</a>';
            if($admin){
                echo '<a class="menu_hide" href="'.PATH.'editcategory/id/'.$arr[$parent_id][$i]['id'].'">';
                echo '<img style="width:17px" src="'.PATH.ADMIN_TEMPLATE.'img/edit.png" alt="Редактировать">';
                echo '</a>';
            }
            echo '</div>';
            $flag = true;

            if(!empty($arr[$arr[$parent_id][$i]['id']])){
                echo '<div class="toggle_categories">';
            }

            getCat($id, $arr, $arr[$parent_id][$i]['id'], $flag, $admin);

            if(!empty($arr[$arr[$parent_id][$i]['id']])){
                echo '</div>';
            }

        }else{
            echo '<div class="subcategory">';
            echo '<a '.$active.' href="'.PATH.$sourse.$arr[$parent_id][$i]['id'].'">'.$arr[$parent_id][$i]['name'].'</a>';
            if($admin){
                echo '<a style="float:right;" class="menu_hide" href="'.PATH.'editcategory/id/'.$arr[$parent_id][$i]['id'].'">';
                echo '<img style="width:17px" src="'.PATH.ADMIN_TEMPLATE.'img/edit.png" alt="Редактировать">';
                echo '</a>';
            }
            getCat($id, $arr, $arr[$parent_id][$i]['id'], $flag, $admin);
            echo '</div>';
        }
    }
    if(!$parent_id && $flag){
        echo '</div>';
    }
}
function icon_output($arr){
    for($i = 0; $i < count($arr); $i++){
        echo '<a class="menu_hide" href="'.PATH.'category/id/'.$arr[$i]['id'].'">';
        if(!empty($arr[$i]['img_icon'])){
            if(file_exists($_SERVER['DOCUMENT_ROOT'].PATH.TEMPLATE.'img/'.$arr[$i]['img_icon'])){
                /*echo '<img src="'.PATH.TEMPLATE.'img/icon_menu_'.$arr[$i]['id'].'.png" alt="icon">';*/
                echo '<img src="'.PATH.TEMPLATE.'img/'.$arr[$i]['img_icon'].'" alt="icon">';
            }
        }
        echo '</a>';
    }
}

function textCutting($str, $counter = false){
    $tags = [0 => ['<p>', '</p>'],
        1 => ['<ul>', '</ul>'],
        2 => ['<ol>', '</ol>']
    ];
    if(!$counter){
        $center = mb_strlen($str) / 2;
        $center = (int)$center;
        $char = mb_substr($str, $center, 1);
        $res_str = array();
        if($char != ' '){
            $sub_str = mb_substr($str, $center);
            $probel = (int)mb_strpos($sub_str, ' ');
            if($probel){
                $cut = $center + $probel;
                $res_str[0] = mb_substr($str, 0, $cut);
                $res_str[1] = mb_substr($str, $cut + 1);
            }else{
                $res_str[0] = $str;
            }
        }else{
            $res_str[0] = mb_substr($str, 0, $center);
            $res_str[1] = mb_substr($str, $center + 1);
        }
    }else{

        if(mb_strlen($str) < $counter){
            $res_str[0] = $str;
            return $res_str;
        }
        $center = $counter;
        $char = mb_substr($str, $center, 1);
        $res_str = array();

        if($char != ' '){
            $sub_str = mb_substr($str, 0, $center);
            $probel = (int)mb_strrpos($sub_str, ' ');
            if($probel){
                $res_str[0] = mb_substr($str, 0, $probel);
                $res_str[1] = mb_substr($str, $probel + 1);
            }else{
                $res_str[0] = $str;
            }
        }else{
            $res_str[0] = mb_substr($str, 0, $center);
            $res_str[1] = mb_substr($str, $center + 1);
        }
    }

    foreach($res_str as $key => $item){
        foreach($tags as $tag){
            $start = mb_strrpos($item, $tag[0]);
            $end = mb_strrpos($item, $tag[1]);
            if ($end !== false){
                if($start === false || mb_strpos($item, $tag[0]) > mb_strpos($item, $tag[1])){
                    $res_str[$key] = $tag[0] . $res_str[$key];
                }
            }elseif($start !== false){
                $end = (int)$end;
                if($start <= $end){
                    $res_str[$key] .= $tag[1];
                }
            }
        }
    }

    return $res_str;
}

function pagination($pages, $string = ''){
        if($pages){

            /*Поиск пораметра Page в адресной строке*/
            if(preg_match("/[\w\d\/]+(page\/\d+)/ui", $_SERVER['REQUEST_URI'])){
                $str = preg_replace("/(page\/\d+)/", '', $_SERVER['REQUEST_URI']);
                $str = substr($str, 1);
                if(substr($str, -1) !== '/'){
                    $str = $str.'/';
                }
            }else{
                $str = $_SERVER['REQUEST_URI'];
                $str = substr($str, 1);
                if(substr($str, -1) !== '/'){
                    $str = $str.'/';
                }
            }
            if(strrpos($str, 'string')){
                $str = substr($str, 0, strrpos($str, 'string')-1);
            }
            if($string){
                $string = '/string/'.$string;
            }
            /*Поиск пораметра Page в адресной строке*/

            if($pages['first']){
                echo '<a style="margin-right: 10px;" href="'.PATH.$str.'page/'.$pages['first'].$string.'">Первая</a>';
            }
            if($pages['back']){
                echo '<a style="margin-right: 10px;" href="'.PATH.$str.'page/'.$pages['back'].$string.'">Назад</a>';
            }
            if($pages['previos']){
                foreach ($pages['previos'] as $value){
                    echo '<a style="margin-right: 10px;" href="'.PATH.$str.'page/'.$value.$string.'">'.$value.'</a>';
                }
            }
            if($pages['current']){
                echo '<p style="margin-right: 10px;">'.$pages['current'].'</p>';
            }
            if($pages['next']){
                foreach ($pages['next'] as $value){
                    echo '<a style="margin-right: 10px;" href="'.PATH.$str.'page/'.$value.$string.'">'.$value.'</a>';
                }
            }
            if($pages['forward']){
                echo '<a style="margin-right: 10px;" href="'.PATH.$str.'page/'.$pages["forward"].$string.'">Вперед</a>';
            }
            if($pages['last']){
                echo "<a style='margin-right: 10px;' href='".PATH.$str."page/".$pages['last'].$string."''>Последняя</a>";
            }
        }
}

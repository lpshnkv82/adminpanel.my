<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 12.08.2018
 * Time: 12:12
 */

namespace libraries;


class TextModify
{

    /**
     * @param $text - строка
     * @param string $delim - html тег по которому разбивать строку
     * @param int $counter - количество элементов результирующего массива текста
     * @return array - массив, в котором текст поделен на заданное количество
     * Элементов может быть меньше, но не более чем указано в $counter
     */
    public function createTextArray($text, $delim = 'p', $counter = 4){
        if($text){
            if(is_numeric($delim)){
                $counter = (int)$delim;
                $delim = 'p';
            }

            $start_tag = '<' . $delim . '>';
            $end_tag = '</' . $delim . '>';

            $arr = explode("$start_tag", $text);
            foreach($arr as $key => $item){
                if(empty($item) || strpos($item, '&nbsp;' . $end_tag) === 0){
                    unset($arr[$key]);
                }else{
                    $arr[$key] = $start_tag . $item;
                }
            }

            $arr = array_values($arr);

            if(count($arr) == 1) return $arr;

            for(; $counter > 0; $counter--){
                $count = round(count($arr) / $counter);
                if($count >= 1) break;
            }

            $res_arr = [];

            $j = 0;
            for($i = 0; $i < $counter; $i++) {
                foreach ($arr as $index => $item) {
                    if($j < $count){
                        $res_arr[$i] .= $item;
                        unset($arr[$index]);
                        $j++;
                    }else{
                        $j = 0;
                        break;
                    }
                }
            }
            if(!empty($arr)){
                foreach($arr as $item){
                    $res_arr[$counter - 1] .= $item;
                }
            }
            return $res_arr;
        }
        return $text;
    }

    /**
     * @param $str - Строка для обрезки по заданным параметра
     * @param bool $counter - колисество символов для обрезки, по умолчанию половина текста
     * @return array - массив => [0 => первая половина текста, 1 => вторая половина текста]
     */
    public function textCutting($str, $counter = false, $revers = false){
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
                if(!$revers){
                    $sub_str = mb_substr($str, $center);
                    $probel = (int)mb_strpos($sub_str, ' ');
                }else{
                    $sub_str = mb_substr($str, 0, $center);
                    $probel = (int)mb_strrpos($sub_str, ' ');
                }

                if($probel){
                    if(!$revers){
                        $cut = $center + $probel;
                    }else{
                        $cut = $probel;
                    }

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

}
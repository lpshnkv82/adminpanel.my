<?php
namespace core\base\model;

abstract class BaseModel{

    protected $inst_driver;
    protected $insertQuery;


    protected function instDriver(){
        try{
            $this->inst_driver = ModelDriver::getInstance();
        }
        catch(DbException $e){
            exit();
        }
    }

    /*Base methods*/
    private function createInsertQuery($arr = false, $files = false, $except = array()){

        if(!$arr){
            $arr = $_POST;
        }

        $this->insertQuery['rows'] = '';
        $this->insertQuery['values'] = '';

        foreach($arr as $key => $value){
            if($except){
                if(in_array($key, $except)){
                    continue;
                }
            }
            $this->insertQuery['rows'] .= $key.',';

            if(strpos($value, 'NOW()') === 0){
                $this->insertQuery['values'] .= $value.",";
            }else{
                $this->insertQuery['values'] .= "'".$value."',";
            }
        }
        if($files){
            foreach($files as $key => $file){
                $this->insertQuery['rows'] .= $key.',';
                if(is_array($file)){
                    $this->insertQuery['values'] .= "'".implode('|', $file)."',";
                }else{
                    $this->insertQuery['values'] .= "'".$file."',";
                }
            }
        }
        $this->insertQuery['rows'] = rtrim($this->insertQuery['rows'], ',');
        $this->insertQuery['values'] = rtrim($this->insertQuery['values'], ',');
    }

    private function createUpdateQuery($arr = false, $files = false, $except = array()){

        if(!$arr){
            $arr = $_POST;
        }

        $this->insertQuery['rows'] = '';

        foreach($arr as $key => $value){
            if($except){
                if(in_array($key, $except)){
                    continue;
                }
            }
            if(strpos($value, 'NOW()') === 0){
                $this->insertQuery['rows'] .= $key."=".$value.",";
            }else{
                $this->insertQuery['rows'] .= $key."='".$value."',";
            }
        }
        if($files){
            foreach($files as $key => $file){
                if(is_array($file)){
                    $this->insertQuery['rows'] .= $key."='".implode('|', $file)."',";
                }else{
                    $this->insertQuery['rows'] .= $key."='".$file."',";
                }
            }
        }
        $this->insertQuery['rows'] = rtrim($this->insertQuery['rows'], ',');
    }

    /**
     * @param $table - таблица дл обновления данных
     * @param array $set - массив параметров:
     * fields => [поле => значение]; если не указан, то обрабатывается $_POST[поле => значение]
     * разрешена передача NOW() в качестве Mysql функции обычно строкой
     * files => [поле => значение]; можно подать массив вида [поле => [массив значений]]
     * except => ['исключение 1', 'исключение 2'] - исключает данные элементы массива из добавления в запрос
     * return_id => true|false - возвращать или нет идентификатор вставленной записи
     * @return mixed
     */
    final public function add($table, $set = array()){

        $set['fields'] = !empty($set['fields']) ? $set['fields'] : false;
        $set['files'] = !empty($set['files']) ? $set['files'] : false;
        $set['return_id'] = !empty($set['return_id']) ? $set['return_id'] : false;
        $set['except'] = !empty($set['except']) ? $set['except'] : array();

        $this->createInsertQuery($set['fields'], $set['files'], $set['except']);

        $query = "INSERT INTO $table ({$this->insertQuery['rows']}) VALUES ({$this->insertQuery['values']})";

        $res = $this->inst_driver->insert($query, $set['return_id']);

        return $res;
    }

    /**
     * @param $table - таблица дл обновления данных
     * @param array $set - массив параметров:
     * fields => [поле => значение]; если не указан, то обрабатывается $_POST[поле => значение]
     * разрешена передача NOW() в качестве Mysql функции обычно строкой
     *
     * files => [поле => значение]; можно подать массив вида [поле => [массив значений]]
     * where => [поле => значение];
     * operand => ['=', '<>', IN] операнд для поля where.
     * Если поля where нет, то берется первый элемент массива fields и подставляется в качетсве where
     * Если массив fields пуст, ищется $_POST['id'] или $_POST['user_id'] и подставляется в качетсве where
     *
     * except => ['исключение 1', 'исключение 2'] - исключает данные элементы массива из добавления в запрос
     * all_rows => true|false, если true - редактировать все поля таблицы
     * @return mixed
     */
    final public function edit($table, $set = array()){

        $set['fields'] = !empty($set['fields']) ? $set['fields'] : false;
        $set['files'] = !empty($set['files']) ? $set['files'] : false;
        $set['where'] = !empty($set['where']) ? $set['where'] : false;
        $set['operand'] = !empty($set['operand']) ? $set['operand'] : ['='];
        $set['except'] = !empty($set['except']) ? $set['except'] : array();
        $set['all_rows'] = $set['all_rows'] ? $set['all_rows'] : false;

        if(!$set['all_rows']){
            if(!$set['fields']){
                if(isset($_POST['id'])){
                    $id = $_POST['id'];
                    $where = 'WHERE id = '.$id;
                    unset($_POST['id']);
                }elseif (isset($_POST['user_id'])){
                    $id = $_POST['user_id'];
                    $where = 'WHERE user_id = '.$id;
                    unset($_POST['user_id']);
                }
            }else{
                if(!$set['where']){
                    foreach($set['fields'] as $key => $value){
                        $where = 'WHERE '.$key.'='.$value;
                        unset($set['fields'][$key]);
                        break;
                    }
                }else{
                    $where = $this->createWhere($set);
                }
            }
        }

        $this->createUpdateQuery($set['fields'], $set['files'], $set['except']);

        $query = "UPDATE $table SET {$this->insertQuery['rows']} $where";

        $res = $this->inst_driver->update($query);

        return $res;
    }

    /**
     * @param $table - Таблица БД
     * @param array $set - массив:
     * fields => [поля, которые надо выбрать] если пустое то *;
     * where => [поле => значение] условия;
     * operand => ['=', '<>', IN, NOT IN] операнд для поля where. Если операндов меньше чем элементов where, то в следующие
     * where добавляется последний операнд; если не указан, то по умолчанию - '=';
     *
     * order => [поле 1, поле 2] - сортировка
     * order_direction => [ASC, DESC] - Направление сортировки. Если операндов меньше чем элементов order, то в следующие
     * ORDER BY добавляется последний операнд; если не указан, то по умолчанию - 'ASC';
     *
     * limit => строка со значением;
     * @return mixed
     */
    final public function get($table, $set = array()){

        $set['fields'] = !empty($set['fields']) ? $set['fields'] : array('*');
        $set['where'] = !empty($set['where']) ? $set['where'] : array();
        $set['operand'] = !empty($set['operand']) ? $set['operand'] : array('=');

        $db_fields = '';

        foreach($set['fields'] as $field){
            $db_fields .= $field.',';
        }


        $db_fields = rtrim($db_fields, ',');
        $db_where = $this->createWhere(['where' => $set['where'], 'operand' => $set['operand']]);
        $order = '';
        if(is_array($set['order'])){
            $set['order_direction'] = !empty($set['order_direction']) ? $set['order_direction'] : array('ASC');
            $direct_count = 0;
            foreach($set['order'] as $order_by) {
                if (empty($order)) {
                    $order = 'ORDER BY ';
                }

                if ($set['order_direction'][$direct_count]) {
                    $order_direction = $set['order_direction'][$direct_count];
                    $direct_count++;
                } else {
                    $order_direction = $set['order_direction'][$direct_count - 1];
                }
                $order .= $order_by. " ". $order_direction . ",";
            }
            $order = rtrim($order, ',');
        }

        $limit = !empty($set['limit']) ? 'LIMIT '.$set['limit'] : '';

        $query = trim("SELECT $db_fields FROM $table $db_where $order $limit");

        $res = $this->inst_driver->select($query);

        return $res;
    }

    protected function createWhere($set){

        $o_count = 0;
        $where = '';

        foreach($set['where'] as $key => $item){
            if(empty($where)){
                $where = 'WHERE ';
            }

            if($set['operand'][$o_count]){
                $equal = $set['operand'][$o_count];
                $o_count++;
            }else{
                $equal = $set['operand'][$o_count - 1];
            }

            if($equal == 'IN' || $equal == 'NOT IN'){
                $temp_item = explode(',', $item);
                $in_str = '';
                foreach($temp_item as $v){
                    $v = trim($v);
                    if(strpos($v, 'SELECT') === 0){
                        $in_str .= trim($v).",";
                    }else{
                        $in_str .= "'".trim($v)."',";
                    }
                }
                $in_str = rtrim($in_str, ',');
                $where .= $key." ". $equal. " (". $in_str . ") AND ";
            }else{
                $where .= $key . $equal. "'". $item . "' AND ";
            }
        }
        $where = substr($where, 0, strrpos($where, 'AND'));

        return $where;
    }

    final public function delete($table, $id, $row = false, $row_name = 'id'){
        if($row){
            $query = "UPDATE $table SET $row = '' WHERE id = $id";
        }else{
            $query = "DELETE FROM $table WHERE $row_name = $id";
        }

        $this->inst_driver->delete($query);
    }

    final public function showColumns($table){
        $query = "SHOW COLUMNS FROM $table";
        return $this->inst_driver->select($query);
    }

    final public function showTables(){
        $query = "SHOW TABLES";
        return $this->inst_driver->select($query);
    }
    /*Base methods*/

}
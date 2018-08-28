<?php
namespace core\admin\model;

class Model extends \core\base\model\BaseModel{
    static $_modelInstance;

    static function getInstance(){
        if(self::$_modelInstance instanceof self){
            return self::$_modelInstance;
        }
        return self::$_modelInstance = new self;
    }

    private function __construct(){
        parent::instDriver();
    }

    public function updateMenuPosition($table, $row, $where, $end_pos, $update_rows = false){

        if($where){
            $start_pos = $this->get($table, ['fields' => [$row],
                'where' => $where
            ])[0][$row];

            if($update_rows){
                $update_rows['operand'] = !empty($update_rows['operand']) ? $update_rows['operand'] : ['='];
                $where_arr = $this->get($table, [
                    'fields' => $update_rows['where'],
                    'where' => $where
                ])[0];

                if($where_arr)
                    $db_where = $this->createWhere(['where' => $where_arr, 'operand' => $update_rows['operand']]);
            }
        }else{
            $start_pos = $this->get($table, [
                    'fields' => ['COUNT(*) AS count']
                ])[0]['count'] + 1;
        }

        $db_where = $db_where ? $db_where . 'AND ' : 'WHERE';
        if($start_pos < $end_pos){
            $query = "UPDATE $table SET $row = $row - 1 $db_where $row <= $end_pos AND $row > $start_pos";
        }elseif($start_pos > $end_pos){
            $query = "UPDATE $table SET $row = $row + 1 $db_where $row >= $end_pos AND $row < $start_pos";
        }else{
            return;
        }

        $this->inst_driver->update($query);
    }

    public function adminSearch($data){

        $result = [];
        $temp_tables = $this->showTables();

        foreach($temp_tables as $item){
            $table = reset($item);
            if(!in_array($table, EXCEPTION_TABLES)){

                $res = $this->showColumns($table);

                $id_row = '';
                $fields = '';
                $where = '';
                $img = false;

                foreach($res as $col){
                    $columns[] = $col['Field'];

                    if($col['Key'] == 'PRI') $id_row = $col['Field'];

                    if(!$img){
                        if(mb_strpos($col['Field'], 'img') !== false){
                            $fields .= $col['Field'] . ' as img,';
                            $img = true;
                        }
                    }

                    if(mb_strpos($col['Field'], 'name') !== false){
                        $fields .= $col['Field'] . ' as name,';

                        if(!$where){
                            $where .= "WHERE {$col['Field']} LIKE '%$data%'";
                        }else{
                            $where .= " OR {$col['Field']} LIKE '%$data%'";
                        }
                    }

                    if(mb_strpos($col['Field'], 'content') !== false){
                        if(!$where){
                            $where .= "WHERE {$col['Field']} LIKE '%$data%'";
                        }else{
                            $where .= " OR {$col['Field']} LIKE '%$data%'";
                        }
                    }

                    if(mb_strpos($col['Field'], 'description') !== false){
                        if(!$where){
                            $where .= "WHERE {$col['Field']} LIKE '%$data%'";
                        }else{
                            $where .= " OR {$col['Field']} LIKE '%$data%'";
                        }
                    }

                }

                if($fields){
                    $fields = $id_row . ' as id,' .rtrim($fields, ',');
                    $query = "SELECT $fields FROM $table $where";
                    $result[$table] = $this->inst_driver->select($query);
                }
            }
        }
        if($result){
            $res_arr = [];
            foreach ($result as $index => $item) {
                if(!$item){
                    unset($result[$index]);
                    continue;
                }
                foreach ($item as $key => $value){
                    $result[$index][$key]['link'] = PATH.ADMIN_PATH.'/edit/'.$index.'/'.$value['id'];
                    $res_arr[] = $result[$index][$key];
                }
            }
            shuffle($res_arr);
            return $res_arr;
        }
        return;
    }
}
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
}
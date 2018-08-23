<?php
namespace core\user\model;

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

    public function getCustom($table, $fields, $where){

        $query = "SELECT $fields FROM $table WHERE $where";
        return $this->inst_driver->select($query);
    }
}
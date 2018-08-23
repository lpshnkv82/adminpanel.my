<?php
namespace core\base\model;

 class ModelDriver{
     static $_modelInstance;
     private $mysqli_db;

     static function getInstance(){
         if(self::$_modelInstance instanceof self){
             return self::$_modelInstance;
         }
         return self::$_modelInstance = new self;
     }

     private function __construct(){
         $this->mysqli_db = new \mysqli(HOST, USER, PASS, DB_MANE);

         if($this->mysqli_db->connect_error){
             throw new DbException('Ошибка подключения к базе данных: '
                                    .$this->mysqli_db->connect_errno
                                    .' '.iconv('CP1251', 'UTF-8', $this->mysqli_db->connect_error));
         }
         $this->mysqli_db->query("SET NAMES UTF8");
     }

     public function select($query){
        $result = $this->mysqli_db->query($query);
         if(!$result){
             throw new DbException('Ошибка запроса к базе данных: '.$this->mysqli_db->connect_errno
                 .' '.$this->mysqli_db->connect_error);
         }

         if($result->num_rows == 0){
             return false;
         }

         $row = array();
         for($i = 0; $i < $result->num_rows; $i++){
             $row[] = $result->fetch_assoc();
         }

         return $row;
     }
     
     public function delete($query){

         $result = $this->mysqli_db->query($query);
         if(!$result){
             throw new DbException('Ошибка запроса к базе данных: '.$this->mysqli_db->connect_errno
                 .' '.$this->mysqli_db->connect_error);

             return false;
         }
         return true;
     }

     public function insert($query, $id=false){
         
         $result = $this->mysqli_db->query($query);

         if($this->mysqli_db->affected_rows == -1){

             throw new DbException('Ошибка запроса к базе данных: '.$this->mysqli_db->connect_errno
                 .' '.$this->mysqli_db->connect_error);

             return false;
         }
         if($id){
            return $this->mysqli_db->insert_id;
         }

         return true;
     }
     
     public function update($query){

         $result = $this->mysqli_db->query($query);

         if(!$result){
             throw new DbException('Ошибка запроса к базе данных: '.$this->mysqli_db->connect_errno
                 .' '.$this->mysqli_db->connect_error);

             return false;
         }

         return true;
     }
 }
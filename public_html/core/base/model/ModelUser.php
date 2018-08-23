<?php
namespace core\base\model;
use core\base\controller\AuthException;
use libraries\Crypt;

class ModelUser{

    static $_modelInstance;
    static $_cookieName = 'USERNAME';
    protected $inst_driver;
    protected $user_data = array();
    private $cookieTime;
    private $cookieVersion;
    protected $clue = "|";
    
    static function getInstance(){
        if(self::$_modelInstance instanceof self){
            return self::$_modelInstance;
        }
        return self::$_modelInstance = new self;
    }

    private function __construct(){
        
        try{
            $this->inst_driver = ModelDriver::getInstance();
        }
        catch(DbException $e){
            exit();
        }
    }

    public function getUser($login, $password){

        $query = "SELECT u.user_id, u.login, ut.user_type FROM users u 
                    LEFT JOIN users_types ut ON u.user_type = ut.id
                    WHERE u.login = '$login' AND u.password = '$password'";

        $result = $this->inst_driver->select($query);
        if($result == NULL || $result == false){
            throw new AuthException('Неправильные имя пользователя и пароль');
            return;
        }

        if(is_array($result)){
            return $result[0];
        }
    }

    public function getFealturies($ip){

        $query = "SELECT fealtures FROM fealtures WHERE ip = '$ip'";
        $result = $this->inst_driver->select($query);

        if(count($result) == 0){
            return NULL;
        }
        return $result[0]['fealtures'];

    }

    public function insertFealtures($ip){
        $time = date("H:i:s");
        $query = "INSERT INTO fealtures (fealtures, ip, time) VALUES (1, '$ip', '$time')";
        $result = $this->inst_driver->insert($query);
    }

    public function updateFealtures($ip, $fealtures){
        $time = date("H:i:s");
        $fealtures++;
        $query = "UPDATE fealtures SET fealtures = $fealtures, time = '$time' WHERE ip = '$ip'";
        $result = $this->inst_driver->insert($query);
    }

    public function deleteFealtures($time){
        $query = "DELETE FROM fealtures WHERE time < '$time'";
        $result = $this->inst_driver->delete($query);
    }

    public function checkIdUser($id = false, $user_type = 'user', $user_name = 'Unnone'){
        if($id){
            $this->user_data['id'] = $id;
            $this->user_data['type'] = $user_type;
            $this->user_data['name'] = $user_name;
            return $this->user_data;
        }else{
            if(array_key_exists(self::$_cookieName, $_COOKIE)){
                $this->unPackage($_COOKIE[self::$_cookieName]);
            }else{
                throw new AuthException ('Доступ запрещен');
            }
        }
    }
    public function set(){
        $cookie_string = $this->package();

        if($cookie_string){
            setcookie(self::$_cookieName, $cookie_string, 0, PATH);
            return true;
        }
    }

    private function package(){
        if($this->user_data['id']){
            $cookie_arr = array($this->user_data['id'], date("H:i:s"), VERSION, $this->user_data['type'], $this->user_data['name']);
            $str = implode($this->clue, $cookie_arr);
            $crypt = Crypt::getInstance();
            
            return $crypt->encrypt($str);
        }else{
            throw new AuthException("Не найден идентификатор пользователя");
        }
    }
    private function unPackage($str){
        if($str){
            $crypt = Crypt::getInstance();
            $result = $crypt->decrypt($str);
            list($this->user_data['id'], $this->cookieTime, $this->cookieVersion, $this->user_data['type'], $this->user_data['name']) = explode($this->clue, $result); //Создаем переменные из массива
            return true;
        }else{
            throw new AuthException('Не верный формат идентификатора');
        }
    }

    public function validateCookie(){

        if(!$this->user_data['id'] || !$this->cookieTime || !$this->cookieVersion || !$this->user_data['type'] || !$this->user_data['name']){
            throw new AuthException('Не корректные данные пользователя. Доступ запрещен');
        }

        if($this->cookieVersion != VERSION){
            throw new AuthException('Проверьте версию');
        }

        $this_time = new \DateTime();
        $cookie_time = new \DateTime($this->cookieTime);
        $cookie_time->modify(COOKIE_TIME." minutes");

        if($this_time > $cookie_time) {
            throw new AuthException ('Превышено время бездействия пользователя');
        }

        $this->set();

        return true;
    }

    public function getUserType(){
        return $this->user_data['type'];
    }

    public function getUserName(){
        return $this->user_data['name'];
    }

    public function getUserId(){
        return $this->user_data['id'];
    }

    public function logout(){
        setcookie(self::$_cookieName, '', 1, PATH);
        return true;
    }
    
}
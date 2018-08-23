<?php
namespace core\base\controller;

class AuthException extends \Exception{

    public function __construct($message){
        $this->message = $message;
        $_SESSION['auth'] = $message;
    }
}
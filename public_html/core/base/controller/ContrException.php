<?php
namespace core\base\controller;

class ContrException extends \Exception{

    protected $errorMessage;

    public function __construct($message, $code=false){
        
        $this->errorMessage = $message;

        $file = $this->getFile();
        $line = $this->getLine();
        $_SESSION['error']['file'] = $file;
        $_SESSION['error']['line'] = $line;

        if($code){
            $_SESSION['error']['code'] = $code;
        }

        header("Location:".PATH.'error404/message/'.rawurlencode($message));
    }

}
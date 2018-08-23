<?php
namespace core\base\model;

class DbException extends \Exception{

    protected $errorMessage;

    public function __construct($message){

        $this->errorMessage = $message;

        $file = $this->getFile();
        $line = $this->getLine();
        $_SESSION['error']['file'] = $file;
        $_SESSION['error']['line'] = $line;

        header("Location:".PATH.'error404/message/'.rawurlencode($message));

    }

}
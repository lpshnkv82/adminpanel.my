<?php
namespace core\base\controller;

class Error404Controller extends BaseError{

    protected function inputData($parameter = array()){

        parent::inputData();
        
        $err = '';
        $arr = array();
        if(isset($parameter['message'])){

            foreach($parameter as $key => $value){
                $value = $this->clearStr(rawurldecode($value));
                $arr[] = $value;

                $err .= $key.' - '.$value. '|';
            }
            
            if($_SESSION['error']){
                foreach($_SESSION['error'] as $key => $value){
                    $err .= $key.' - '.$value. '|';
                }
            }
            unset($_SESSION['error']);
            $this->errors = $err;
            $this->messageError = $arr;

        }

    }

    protected function outputData(){

        return parent::outputData();
    
    }

}
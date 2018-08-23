<?php
namespace libraries;

class Crypt{

    static $_cryptInstance;
    private $descriptor;
    private $crypt_method = MCRYPT_BLOWFISH; //Режим шифрования
    private $mode = MCRYPT_MODE_CFB; //Метод шифрования

    static function getInstance(){
        if(self::$_cryptInstance instanceof self){
            return self::$_cryptInstance;
        }
        return self::$_cryptInstance = new self;
    }

    private function __construct(){
        /*Создаем дескриптор для модуля шифрования*/
        $this->descriptor = mcrypt_module_open($this->crypt_method, '', $this->mode, '');
        /*Создаем дескриптор для модуля шифрования*/
    }

    public function encrypt($str){ //Шифврование данных
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->descriptor), MCRYPT_RAND); //Создаем вектор инициализации и получаем его длину

        /*Шифруем строку*/
        mcrypt_generic_init($this->descriptor, CRYPT_KEY, $iv);
        $crypt_text = mcrypt_generic($this->descriptor, $str);
        mcrypt_generic_deinit($this->descriptor);
        /*Шифруем строку*/

        return $iv.$crypt_text;
    }

    public function decrypt($str){  //расшифровка данных
        $iv_size = mcrypt_enc_get_iv_size($this->descriptor); //Получаем дляну вектора инициализации
        $iv = substr($str, 0, $iv_size); //Получаем вектор инициализаци из шифрованной строки
        $crypt_string = substr($str, $iv_size);//Получаем саму шифрованную строку

        /*Расшифровываем строку*/
        mcrypt_generic_init($this->descriptor, CRYPT_KEY, $iv);
        $dec_str = mdecrypt_generic($this->descriptor, $crypt_string);
        mcrypt_generic_deinit($this->descriptor);
        /*Расшифровываем строку*/

        return $dec_str;

    }

}
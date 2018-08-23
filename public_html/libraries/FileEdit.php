<?php
namespace libraries;

class FileEdit{

    protected $img_arr = array();

    public function addFile(){

        foreach($_FILES as $key => $file){
            $file_arr = array();

            if(is_array($file['name'])){
                for($i = 0; $i < count($file['name']); $i++){
                    if(!empty($file['name'][$i])){
                        $file_arr['name'] = $file['name'][$i];
                        $file_arr['type'] = $file['type'][$i];
                        $file_arr['tmp_name'] = $file['tmp_name'][$i];
                        $file_arr['error'] = $file['error'][$i];
                        $file_arr['size'] = $file['size'][$i];

                        $res = $this->checkFile($file_arr, $i);
                        if($res){
                            $this->img_arr[$key][] = $res;
                        }
                    }
                }
            }else{
                if(!empty($file['name'])){
                    $res = $this->checkFile($file, $key);
                    if($res){
                        $this->img_arr[$key] = $res;
                    }
                }
            }
        }
        return $this->getFiles();
    }

    protected function checkFile($image, $key = 0){

        // Проверяем размер файлов и если он превышает заданный размер
        // завершаем выполнение скрипта и выводим ошибку
        if ($image['size'] > 10000000) {
            return false;
        }

        // Достаем формат изображения
        $imageFormat = explode('.', $image['name']);
        $imageFormat = $imageFormat[1];

        // Генерируем новое имя для изображения.
        $fileName = hash('crc32', time()) .'_'. $key . '.' . $imageFormat;
        $imageFullName = $_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$fileName;

        // Сохраняем тип изображения в переменную
        $imageType = $image['type'];

        // Сверяем доступные форматы изображений, если изображение соответствует,
        // копируем изображение в папку images
        if ($imageType == 'image/jpeg' || $imageType == 'image/png' || $imageType == 'image/gif') {
            if (!$this->uploadImage($image['tmp_name'], $imageFullName)) {
                return false;
            }else{
                //$this->createThumbnail($imageFullName, $key);
                return $fileName;
            }
        }
    }

    protected function uploadImage($tmp_file, $dest){
        if (move_uploaded_file($tmp_file, $dest)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Функция создания thumbnail
     * @param $image - полный путь к изображению
     * @param $settings - параметры (cut => [width|height], resize => [width|size] или [height|size])
     * @param string $prefix - префикс (необязательно, если есть добавляется к названию изображения и формируется новый
     * файл, если нет, то текущий файл перезаписывается)
     * @return bool|string - false - если ошибка создания | имя файла при создании изображения
     */
    public function createThumbnail($image, $settings, $prefix = ''){

        if(!empty($image) && !empty($settings)){
            foreach($settings as $type => $value){
                switch($type){
                    case 'resize':
                        $resize_set = explode("|", $value);
                        return $this->resize($image, $prefix, $resize_set);
                        break;

                    case 'cut':
                        $w_h = explode('|', $value);
                        $thumb_width = (int)trim($w_h[0]);
                        $thumb_height = (int)trim($w_h[1]);
                        if($thumb_width && $thumb_height){
                            return $this->thumbnail($image, $thumb_width, $thumb_height, $prefix);
                        }
                        break;

                    default:
                        return false;
                        break;
                }
            }
        }
    }


    /**
     * @param $image
     * @param $prefix
     * @param $resize
     * @return bool|string
     */
    protected function resize($image, $prefix, $resize){

        $info = getimagesize($image); //получаем размеры картинки и ее тип
        $size = array($info[0], $info[1]); //закидываем размеры в массив

        if($resize){
            foreach ($resize as $key => $value){
                $resize[$key] = trim($value);
            }
            if($resize[0] == 'width'){
                $ratio = $resize[1] / $size[0];
                $height = $size[1] * $ratio;
                $new_size = array($resize[1], $height);
            }elseif($resize[0] == 'height'){
                $ratio = $resize[1] / $size[1];
                $width = $size[0] * $ratio;
                $new_size = array($width, $resize[1]);
            }else{
                return false;
            }
        }

        $thumb = imagecreatetruecolor($new_size[0], $new_size[1]); //возвращает идентификатор изображения, представляющий черное изображение заданного размера

        //В зависимости от расширения картинки вызываем соответствующую функцию
        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($image); //создаём новое изображение из файла

            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparent);
            imagesavealpha($thumb, true); // save alphablending setting (important);

        } else if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($image);
        } else if ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($image);
        } else {
            return false;
        }

        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $new_size[0], $new_size[1], $size[0], $size[1]); //Копирование и изменение размера изображения с ресемплированием

        if($prefix){
            $image_name = substr($image, strrpos($image, '/') + 1);
            $dir_name = substr($image, 0, strrpos($image, '/') + 1);
            $new_image = $dir_name.$prefix."_".$image_name;
        }else{
            $new_image = $image;
        }

        if ($info['mime'] == 'image/png') {
            imagepng($thumb, $new_image); //создаём новое изображение из файла
        } else if ($info['mime'] == 'image/jpeg') {
            imagejpeg($thumb, $new_image);
        } else if ($info['mime'] == 'image/gif') {
            imagegif($thumb, $new_image);
        } else {
            return false;
        }
        return $new_image;
    }

    protected function thumbnail($image, $thumb_width, $thumb_height, $prefix = ''){

        $info = getimagesize($image); //получаем размеры картинки и ее тип
        $size = array($info[0], $info[1]); //закидываем размеры в массив

        $thumb = imagecreatetruecolor($thumb_width, $thumb_height); //возвращает идентификатор изображения, представляющий черное изображение заданного размера

        //В зависимости от расширения картинки вызываем соответствующую функцию
        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($image); //создаём новое изображение из файла

            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparent);
            imagesavealpha($thumb, true); // save alphablending setting (important);

        } else if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($image);
        } else if ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($image);
        } else {
            return false;
        }


        $src_aspect = $size[0] / $size[1]; //отношение ширины к высоте исходника
        $thumb_aspect = $thumb_width / $thumb_height; //отношение ширины к высоте аватарки

        if($src_aspect < $thumb_aspect) {        //узкий вариант (фиксированная ширина)
            $scale = $thumb_width / $size[0];
            $new_size = array($thumb_width, $thumb_width / $src_aspect);
            $src_pos = array(0, ($size[1] * $scale - $thumb_height) / $scale / 2); //Ищем расстояние по высоте от края картинки до начала картины после обрезки
        }else if($src_aspect > $thumb_aspect) { //широкий вариант (фиксированная высота)
            $scale = $thumb_height / $size[1];
            $new_size = array($thumb_height * $src_aspect, $thumb_height);
            $src_pos = array(($size[0] * $scale - $thumb_width) / $scale / 2, 0); //Ищем расстояние по ширине от края картинки до начала картины после обрезки
        }else{
            //другое
            $new_size = array($thumb_width, $thumb_height);
            $src_pos = array(0,0);
        }
        
        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);

        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]); //Копирование и изменение размера изображения с ресемплированием

        $image_name = substr($image, strrpos($image, '/') + 1);
        if($prefix){
            $dir_name = substr($image, 0, strrpos($image, '/') + 1);
            $image_name = $prefix."_".$image_name;
            $new_image = $dir_name.$image_name;
        }else{
            $new_image = $image;
        }

        if ($info['mime'] == 'image/png') {
            imagepng($thumb, $new_image); //создаём новое изображение из файла
        } else if ($info['mime'] == 'image/jpeg') {
            imagejpeg($thumb, $new_image);
        } else if ($info['mime'] == 'image/gif') {
            imagegif($thumb, $new_image);
        } else {
            return false;
        }
        return $image_name;
    }

    public function createJsThumbnail($arr, $new_image_size, $prefix = ''){

        $image = $arr['img'];

        $info = getimagesize($image); //получаем размеры картинки и ее тип
        $size = array($info[0], $info[1]); //закидываем размеры в массив

        /*Создаем изображение того же размера что и вырезанное*/
        $arr['w'] = max($arr['w'], 1);
        $arr['h'] = max($arr['h'], 1);

        $new_image_size[0] = $arr['w'];
        $new_image_size[1] = $arr['h'];
        /*Создаем изображение того же размера что и вырезанное*/


        $thumb = imagecreatetruecolor($new_image_size[0], $new_image_size[1]); //возвращает идентификатор изображения, представляющий черное изображение заданного размера

        //В зависимости от расширения картинки вызываем соответствующую функцию
        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($image); //создаём новое изображение из файла

            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparent);
            imagesavealpha($thumb, true); // save alphablending setting (important);

        } else if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($image);
        } else if ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($image);
        } else {
            return false;
        }

        $new_size = array($new_image_size[0], $new_image_size[1]);
        $src_pos = array($arr['x1'], $arr['y1']);

        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);

        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $arr['w'], $arr['h']); //Копирование и изменение размера изображения с ресемплированием

        $image_name = substr($image, strrpos($image, '/') + 1);
        if($prefix){
            $image_name = $prefix."_".$image_name;
            //$dir_name = $arr['crop'];
            $dir_name = substr($image, 0, strrpos($image, '/') + 1);
            $new_image = $dir_name.$image_name;
        }else{
            $new_image = $image;
        }

        if ($info['mime'] == 'image/png') {
            imagepng($thumb, $new_image); //создаём новое изображение из файла
        } else if ($info['mime'] == 'image/jpeg') {
            imagejpeg($thumb, $new_image);
        } else if ($info['mime'] == 'image/gif') {
            imagegif($thumb, $new_image);
        } else {
            return false;
        }
        return $image_name;
    }

    protected function getFiles(){
        return $this->img_arr;
    }
}
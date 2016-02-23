<?php
    /**
    * @desc 文件IO
    * 
    * @copyright (c) 2011 
    * @author heyl,2011-12-20
    * @package HFIOFile.class.php
    * @version HFIOFile.class.php
    */

    class HFIOFile
    {
        public static function write($file, $data, $append = false)
        {

            if (!file_exists($file)){
                if (!self::mkdir(dirname($file))) {
                    return false;
                }
            }
            $mode = $append ? 'ab' : 'wb';
            $fp = @fopen($file, $mode);
            if (!$fp) {
                exit("Can not open file $file !");
            }
            flock($fp, LOCK_EX);
            $len = @fwrite($fp, $data);
            flock($fp, LOCK_UN);
            @fclose($fp);
            return $len;
        }

        public static function read($file) {

            if (!file_exists($file)){
                return false;
            }
            if (!is_readable($file)) {
                return false;
            }
            $result = '';
            if (function_exists('file_get_contents')){
                $result = file_get_contents($file);
            }else{
                $result = (($contents = file($file))) ? implode('', $contents) : false; 
            }
            return $result;
        }
        public static function mkdir($path) 
        {
            $ret = true;
            if (!is_dir($path)){
                $ret = mkdir($path, 0755,true);
            }
            return $ret;
        }

        public static function rm($path)
        {    
            $path = rtrim($path,'/\\ ');
            if ( !is_dir($path) ){ return @unlink($path); }
            if ( !$handle= opendir($path) ){ 
                return false; 
            }
            while( false !==($file=readdir($handle)) ){
                if($file=="." || $file=="..") continue ;
                $file=$path . $file;
                if(is_dir($file)){ 
                    self::rm($file);
                } else {
                    if(!@unlink($file)){
                        return false;
                    }
                }
            }
            closedir($handle);
            if(!rmdir($path)){
                return false;
            }
            return true;
        }
       public function delDir($dir) {
            //先删除目录下的文件：
            $dh=opendir($dir);
            while ($file=readdir($dh)) {
                if($file!="." && $file!="..") {
                    $fullpath=$dir."/".$file;
                    if(!is_dir($fullpath)) {
                        unlink($fullpath);
                    } else {
                        deldir($fullpath);
                    }
                }
            }

            closedir($dh);
            //删除当前文件夹：
            if(rmdir($dir)) {
                return true;
            } else {
                return false;
            }
        }

    }
?>

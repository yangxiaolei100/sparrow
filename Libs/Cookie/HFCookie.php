<?php
/**
* @describe  cookie 
* @version   1.0   
*/

class HFCookie
{
    /********* public ***************/
	public static $cookie_varpre = 'df$%54';
    public static $cookie_expire = 3600;
    public static $cookie_domain = '';
    public static $cookie_path   = '/';
	/********* __construct ***************/
	public function __construct($cookie_varpre,$cookie_expire,$cookie_domain,$cookie_path){
		self::$cookie_varpre  = $cookie_varpre;
        self::$cookie_expire  = $cookie_expire;
        self::$cookie_domain  = $cookie_domain;
        self::$cookie_path    = $cookie_path;
		if(empty(self::$cookie_varpre) || empty(self::$cookie_domain) || empty(self::$cookie_path)){
			trigger_error("您正在试图使用框架提供类库，请调用此类的构造方法，初始化数据库连接信息。");
		}
	}
    // get cookie value
    public static function get($name) {
        $prefix  = self::$cookie_varpre;
        $value   = @$_COOKIE[$prefix.$name];
        return empty($value) ? null : (unserialize(base64_decode($value)));
    }

    // set cookie value
    public static function set($name,$value,$expire='',$path='',$domain='') {
        if($expire=='') {
            $expire =   self::$cookie_expire;
        }
        if(empty($path)) {
            $path   = self::$cookie_path;
        }
        if(empty($domain)) {
            $domain =   self::$cookie_domain;
        }
        $expire  =  !empty($expire) ? time() + $expire : 0;
        $value   =  base64_encode(serialize($value));
        
        $prefix  = self::$cookie_varpre;

        setcookie($prefix.$name,$value,$expire,$path,$domain);
        $_COOKIE[$prefix.$name]  = $value;
    }

    // del cookie value
    public static function del($name){
        self::set($name,'',-3600);
        $prefix  = self::$cookie_varpre;
        unset($_COOKIE[$prefix.$name]);
    }

    // clear cookie value
    public static function clear() {
        unset($_COOKIE);
    }
}
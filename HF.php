<?php

if(!defined('DS'))define('DS', DIRECTORY_SEPARATOR);
if(!defined('APP_ROOT'))trigger_error("请先定义工程根目录APP_ROOT");
define('FARME_ROOT',dirname(__FILE__));
/*加载框架配置文件*/
include_once "config.inc";
date_default_timezone_set('PRC');
/*定义自动加载*/
if (function_exists('spl_autoload_register')){
	spl_autoload_register(array('HF','autoLoad'));
}else{
	function __autoload($className){
		HF::autoLoad($className);
	}
}
if($_HFConfig["errorHandle"]){
	set_error_handler(array('HF','errorReport'));
}

/*main*/
class HF
{
	protected static $_regClass = array(
		//框架核心
		'CController' => 'Core/CController.php',
		'CRequest' => 'Core/CRequest.php',
		'CRequestCLI' => 'Core/CRequestCLI.php',
		'CViewRender'  => 'Core/CViewRender.php',
		//数据库
		'HFMysqlDB'  => 'Libs/DB/HFMysqlDB.php',
		'HFDbHelper'  => 'Libs/DB/HFDbHelper.php',
		//memcache
		'HFMemcache' => 'Libs/HFMemcache.php',
		//redis
		'HFRedis' => 'Libs/Nosql/Redis.php',
		//字符串
		'HFString'  => 'Libs/String/HFString.php',
		//日志
		'HFLog' => 'Libs/Log/HFLog.php',
		//加密
		'HFEncrypt' => 'Libs/Encrypt/HFEncrypt.php',
		'HFReversibleEncrypt' => 'Libs/Encrypt/HFReversibleEncrypt.php',
		//工具类库
		'HFPage' => 'Libs/Tool/HFPage.php',
		'HFUtil' => 'Libs/Tool/HFUtil.php',
		'HFVerificationCode' => 'Libs/Tool/HFVerificationCode.php',
		//cookie
		'HFCookie' => 'Libs/Cookie/HFCookie.php',
		//文件和目录操作
		'HFIOFile' => 'Libs/HFIOFile.php',
        //http请求
        'HttpRequest' => 'Libs/Http/HttpRequest.php'
	);
	/*
	 * 自动加载
	 */
	public static function autoLoad($classname){
		if(isset(self::$_regClass[$classname])){
			require_once(FARME_ROOT.DS.self::$_regClass[$classname]);
		}else{
			$filePath = self::parseClassName($classname);
			$abPath = APP_ROOT.$filePath;
			if(file_exists($abPath)){
				require_once ($abPath);
			}else{
				trigger_error("文件不存在,$abPath");
			}
		}
	}
	/**
     * 解析类名
     */
    public static function parseClassName($className){
    	$classArr	= explode('_', $className);
    	$classPath	= '';
    	if (is_array($classArr)) {
    		foreach ($classArr as $row) {
    			if (empty($classPath)) {
    				$classPath = ucfirst($row);
    			} else {
    				$classPath .= DS.ucfirst($row);
    			}
    		}
    	}
    	
    	$classPath	= empty($classPath) ? $className : $classPath;
    	return $classPath.".php";
    }
	/**
	 * 自定义错误处理函数
	 */
	public static function errorReport($errno, $errstr, $errfile, $errline){ 
	 	 header("Content-type:text/html;charset=utf-8");
		 echo "错误描述：".$errstr."</br>";
		 echo "错误文件：".$errfile."</br>";
		 echo "错误行号：".$errline."</br>";
		 echo "错误编码：".$errno."</br>";
		 die();
	 }
}
/*factory*/
class F
{
	private static $_objects = array();
	/**
	 * 单利模式实例化
	 */
	public static function S($className){
		if(empty($className))trigger_error("需要给出实例化类名");
		if(isset(self::$_objects[$className]) && self::$_objects[$className]){
			return self::$_objects[$className];
		}else{
			self::$_objects[$className] = new $className();
			return self::$_objects[$className];
		}
	}
	/**
	 * 普通模式实例化
	 */
	public static function N($className){
		if($className){
			$obj = new $className();
		}else{
			trigger_error("需要给出实例化类名");
		}
		return $obj;
	}
}
/*Base*/
class Base
{
	public static $_c = "C";
	public static $_v = "V";
	public static function run(){
		$request_c = CRequest::getController();
		$request_a = CRequest::getAction();
		$request_c = $request_c ? $request_c : 'Default';
		$controller = F::N(self::$_c.'_'.$request_c);
		$controller->prepare($_REQUEST);
		if($request_a){
			if(method_exists($controller,$request_a)){
			}else{
				trigger_error("方法不存在");
			}
			$controller->$request_a();
		}
		$controller->display();
	}
	//命令行启动入口
	public static function runCLI(){
		$request_c = CRequestCLI::getController(true);
		$request_a = CRequestCLI::getAction(true);
		$controller = F::N(self::$_c.'_'.$request_c);
		$count = count($_SERVER['argv']);
		for ($i = 1; $i < $count ; $i++){
			if(substr($_SERVER['argv'][$i],0,1) == '-'){
				$para = substr($_SERVER['argv'][$i],1);
				$pos = strpos($para,'=');
				if($pos === false){
					continue;
				}else{
					$key = substr($para,0,$pos);
					$val = substr($para,$pos+1);
				}
				$_REQUEST["$key"] = "$val"; 
			}else{
				continue;
			}
		}
		$controller->prepare($_REQUEST);
		if($request_a){
			if(method_exists($controller,$request_a)){
			}else{
				trigger_error('方法不存在');
			}
			$controller->$request_a();
		}
		$controller->display();
	}
}



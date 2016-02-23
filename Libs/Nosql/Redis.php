<?php
class HFRedis extends Redis{
	private $host = "127.0.0.1";
	private $port = "6379";
	public function __construct($host,$port = NULL){
		$this->host = $host;
		if(!empty($port)){
			$this->port = $port;
		}
		$this->port = $port;
		if(!empty($port)){
			$this->port = $port;
		}
		if(!$this->connect($this->host,$this->port)){
			trigger_error("redis连接失败！");
		}
	}
	/**
	 * 删除redis某一key值的元素
	 * 
	 * @param string/array $key  
	 */
	public function delete($key){
		if(empty($key))return false;
		return parent::delete($key);
	}
	/**
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $start
	 * @param unknown_type $end
	 * @param unknown_type $withscores
	 */
	public function zRevRange($key, $start,$end,$withscores){
		return parent::zRevRange($key, $start,$end,$withscores);
	}
}
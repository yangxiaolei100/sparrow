<?php
class Libs_Memcache_Memlib extends HFMemcache{
	public function __construct(){
		global $_memcache_config;
		parent::__construct($_memcache_config["host"],$_memcache_config["port"],$_memcache_config["timeout"],$_memcache_config["life"]);
	}
}
?>
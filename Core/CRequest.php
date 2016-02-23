<?php
class CRequest
{
	public static function getController(){
		return isset($_REQUEST['c']) ?  ucfirst(strip_tags($_REQUEST['c'])) : 'Default';
	}
	public static function getAction(){
		return isset($_REQUEST['a']) ?  ucfirst(strip_tags($_REQUEST['a'])) : 'Default';
	}
}
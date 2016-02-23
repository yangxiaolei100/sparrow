<?php
class CRequestCLI
{
	public static function getController(){
		return (isset($_SERVER['argv'][1]) && (substr($_SERVER['argv'][1],0,3) == '-c=')) ?  ucfirst(strip_tags(substr($_SERVER['argv'][1],3))) : 'Default';
	}
	public static function getAction(){
		return (isset($_SERVER['argv'][2]) && (substr($_SERVER['argv'][2],0,3) == '-a=')) ?  ucfirst(strip_tags(substr($_SERVER['argv'][2],3))) : 'Default';
	}
}
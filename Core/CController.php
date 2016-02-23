<?php
/**
 * 控制层抽象类
 * 
 * @author yangdayao
 * @version v1.0
 */	
abstract class CController{
	private $isSetTpl = false;
	protected $_view;
	protected $_global_setting;
	public function __construct(){
		global $_global_setting;
		$this->_global_setting = $_global_setting; 
		$this->_view = new CViewRender();
	}
	//框架每次首先会自动执行此函数，可用于加载接收到的参数，系统变量等,每个控制层必须实现此方法
	abstract public function prepare($args);
	final function display(){
		if($this->isSetTpl){
			$this->_view->display();
		}
	}
	final function setTemplate($file,$dir = NULL){
		$this->isSetTpl = true;
		$this->_view->setTemplate($file,$dir);
	}
    final function __set($key, $val)
    {
        $this->_view->set($key, $val);
    }
	final function __get($name)
    {
    	return $this->_view->get($name);
    }
}
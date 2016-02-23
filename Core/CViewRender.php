<?php
/**
 * 模板渲染和输出
 */
 class CViewRender
 {  
 	private $_viewDate = array();
 	private $_templateName;
 	private $_tplExtension = '.tpl.php';
 	
    public function __construct(){
    }
    
    public function setTemplate($template,$dir){
    	$templateDir = empty($dir) ? $template : (implode(DS,$dir).DS.$template);
    	$this->_templateName = APP_ROOT.DS.Base::$_v.DS.$templateDir.$this->_tplExtension;
    }
    
    public function set($key,$val){
    	$this->_viewDate[$key] = $val;
    }
    
    public function __set($key,$val){
    	$this->set($key,$val);
    }
    public function get($name){
    	return isset($this->_viewDate[$name]) ? $this->_viewDate[$name] : '';
    }
 	public function __get($name){
    	$this->get($name);
    }
    public function display(){
    	$this->render();
    }
    
    public function render(){
    	if(!is_file($this->_templateName)){
    		trigger_error("模板文件不存在");
    	}
    	if(!empty($this->_viewDate)){
    		extract($this->_viewDate);
    	}
    	include $this->_templateName;
    }
 } 
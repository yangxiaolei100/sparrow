<?php
class HFEncrypt{
	private $mingleStr = 'r2f*R@!sf';
	public function encrypt($str){
		if(empty($str))die("参数为空！");
		return sha1($str.$this->mingleStr);
	}
}
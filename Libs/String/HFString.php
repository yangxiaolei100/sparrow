<?php
class HFString{
	/*
	 * 中文无乱码截取
	 */
	public function cut_str($sourcestr,$cutlength,$tail = '')  
	{  
	   $returnstr='';  
	   $i=0;  
	   $n=0;  
	   $str_length=strlen($sourcestr);//字符串的字节数  
	   while (($n<$cutlength) and ($i<=$str_length))  
	   {  
	      $temp_str=substr($sourcestr,$i,1);  
	      $ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码  
	      if ($ascnum>=224)    //如果ASCII位高与224，  
	      {  
			 $returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符          
	         $i=$i+3;            //实际Byte计为3  
	         $n++;            //字串长度计1  
	      }  
	      elseif ($ascnum>=192) //如果ASCII位高与192，  
	      {  
	         $returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符  
	         $i=$i+2;            //实际Byte计为2  
	         $n++;            //字串长度计1  
	      }  
	      elseif ($ascnum>=65 && $ascnum<=90) //如果是大写字母，  
	      {  
	         $returnstr=$returnstr.substr($sourcestr,$i,1);  
	         $i=$i+1;            //实际的Byte数仍计1个  
	         $n++;            //但考虑整体美观，大写字母计成一个高位字符  
	      }  
	      else                //其他情况下，包括小写字母和半角标点符号，  
	      {  
	         $returnstr=$returnstr.substr($sourcestr,$i,1);  
	         $i=$i+1;            //实际的Byte数计1个  
	         $n=$n+0.5;        //小写字母和半角标点等与半个高位字符宽...  
	      }  
	   }  
	   if ($str_length>$i && !empty($tail)){  
	   		$returnstr = $returnstr . $tail;//超过长度时在尾处加上省略号  
	    }  
	    return $returnstr;  
	}  
	/**
	 * 检测邮箱字符串的合法性
	 */
	public static function checkMail($str){
		if(preg_match("/^[0-9a-zA-Z]+(?:[\_\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $str)){
			return true;
		}
		else{
			return false;
		}
	}
/**
	 * 检测字符串的合法性
	 */
	function checkStringType($str,$type,$encoding = 'UTF-8'){
		$ret = '';
		switch ($type){
			case 'tel':
				$ret = (preg_match("/^(((d{3}))|(d{3}-))?((0d{2,3})|0d{2,3}-)?[1-9]d{6,8}$/",$str)) ? true:false;
			break;
			case 'mail':
				$ret = preg_match("/^[0-9a-zA-Z]+(?:[\_\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $str) ? true:false;
			break;
			case 'mobile':
				$ret = preg_match("/^(13|15|18)\d{9}$/i", $str) ? true:false;
			break;
			case 'en':
				$ret = preg_match("/^[a-zA-Z]$/", $str) ? true:false;
			break;
			case 'ch':
				if ($encoding == 'GBK') {
					$reg = "/^[\xa0-\xff]+$/";
				} else if ($encoding == 'UTF-8') {
					$reg = "/^[\xe0-\xef][\x80-\xbf]+$/";
				}
				$ret = preg_match($reg, $str) ? true:false;
			break;
			case 'url':
				$ret = preg_match("/^(http\:\/\/)?\w+(\.\w+)\/?/i", $str) ? true:false;
			break;
			case 'postcode':
				$ret = preg_match("/^\d{6}$/", $str) ? true:false;
			break;
			case 'number':
				$ret = preg_match("/^[0-9]+$/", $str) ? true:false;
			break;
		}
		return $ret;
		
	}
	public static function getfirstchar($s0){  
		$fchar = ord($s0{0});
		if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
		$s1 = iconv("UTF-8","gb2312", $s0);
		$s2 = iconv("gb2312","UTF-8", $s1);
		if($s2 == $s0){$s = $s1;}else{$s = $s0;}
		$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
		if($asc >= -20319 and $asc <= -20284) return "a";
		if($asc >= -20283 and $asc <= -19776) return "b";
		if($asc >= -19775 and $asc <= -19219) return "c";
		if($asc >= -19218 and $asc <= -18711) return "d";
		if($asc >= -18710 and $asc <= -18527) return "e";
		if($asc >= -18526 and $asc <= -18240) return "f";
		if($asc >= -18239 and $asc <= -17923) return "g";
		if($asc >= -17922 and $asc <= -17418) return "h";
		if($asc >= -17417 and $asc <= -16475) return "j";
		if($asc >= -16474 and $asc <= -16213) return "k";
		if($asc >= -16212 and $asc <= -15641) return "l";
		if($asc >= -15640 and $asc <= -15166) return "m";
		if($asc >= -15165 and $asc <= -14923) return "n";
		if($asc >= -14922 and $asc <= -14915) return "o";
		if($asc >= -14914 and $asc <= -14631) return "p";
		if($asc >= -14630 and $asc <= -14150) return "q";
		if($asc >= -14149 and $asc <= -14091) return "r";
		if($asc >= -14090 and $asc <= -13319) return "s";
		if($asc >= -13318 and $asc <= -12839) return "t";
		if($asc >= -12838 and $asc <= -12557) return "w";
		if($asc >= -12556 and $asc <= -11848) return "x";
		if($asc >= -11847 and $asc <= -11056) return "y";
		if($asc >= -11055 and $asc <= -10247) return "z";
		return null;
	}
 	/*
	* 取得中文字符的长度
	* @param string $str 字符
	* @return int 字符的长度 
	*/
    public static function len($string)
    {
        $len = strlen($string);
        $i = 0;
        $n = '';
        while ($i<$len) {
    	    if (preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/", $string[$i])) {
    			$i += 2;
    		 } else {
    			$i += 1;
    		 }
    	    $n += 1;
    	 }
        return $n;
    }
    
    /*
	* 字符串截取
	* @param string $string 字符
	* @return int 字符的长度 
	*/
    public  static function substr($string, $length, $charset = 'gbk') 
    {
		if (strlen($string) <= $length) {
			return $string;
		}
		//$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
		$substr = '';
		if ($charset == 'UTF8') {
			$n = $tn = $noc = 0;
			while ($n < strlen($string)) {
				$t = ord($string[$n]);
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1; $n++; $noc++;
				} elseif(194 <= $t && $t <= 223) {
					$tn = 2; $n += 2; $noc += 2;
				} elseif(224 <= $t && $t < 239) {
					$tn = 3; $n += 3; $noc += 2;
				} elseif(240 <= $t && $t <= 247) {
					$tn = 4; $n += 4; $noc += 2;
				} elseif(248 <= $t && $t <= 251) {
					$tn = 5; $n += 5; $noc += 2;
				} elseif($t == 252 || $t == 253) {
					$tn = 6; $n += 6; $noc += 2;
				} else {
					$n++;
				}
				if($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
			}

			$substr = substr($string, 0, $n);

		} else {
			for ($i = 0; $i < $length; $i++) {
				$substr .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
			}
		}
    	//$substr = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $substr);
		return $substr;
	}
	
	/*
	* 中文首字母
	* @param string $string 字符
	* @return 首字母
	*/
	public static function firstLetter($string) {
		$dict = array(
        			'a'=>0xB0C4,'b'=>0xB2C0,'c'=>0xB4ED,'d'=>0xB6E9,
        			'e'=>0xB7A1,'f'=>0xB8C0,'g'=>0xB9FD,'h'=>0xBBF6,
        			'j'=>0xBFA5,'k'=>0xC0AB,'l'=>0xC2E7,'m'=>0xC4C2,
        			'n'=>0xC5B5,'o'=>0xC5BD,'p'=>0xC6D9,'q'=>0xC8BA,
        			'r'=>0xC8F5,'s'=>0xCBF9,'t'=>0xCDD9,'w'=>0xCEF3,
        			'x'=>0xD188,'y'=>0xD4D0,'z'=>0xD7F9
    			);
		$letter = substr($string, 0, 1);
		if($letter >= chr(0x81) && $letter <= chr(0xfe)) {
			$num = hexdec(bin2hex(substr($string, 0, 2)));
			foreach ($dict as $k=>$v){
				if($v>=$num)
					break;
				}
				return $k;
		} elseif ((ord($letter)>64&&ord($letter)<91) || (ord($letter)>96&&ord($letter)<123) ){
			return $letter;
		} elseif ($letter>='0' && $letter<='9'){
			return $letter;
		} else {
			return '*';
		}
	}
	/**
	 * 获取每个汉字的拼音首字母小写
	 * 
	 * @param unknown_type $zh 汉字串
	 */
	public static function pinyin($zh){
		$ret = "";
		$s1 = iconv("UTF-8","gb2312", $zh);
		$s2 = iconv("gb2312","UTF-8", $s1);
		if($s2 == $zh){$zh = $s1;}
		for($i = 0; $i < strlen($zh); $i++){
			$s1 = substr($zh,$i,1);
			$p = ord($s1);
			if($p > 160){
				$s2 = substr($zh,$i++,2);
				$ret .= self::getfirstchar($s2);
			}else{
				$ret .= $s1;
			}
		}
		return $ret;
	}
	/*
	 * 取得随机字符串
	 * @param int $length  字符的长度
	 * @param int $numeric 只是数字时，数字长度
	 * @return 
	 */
    public static function random($length, $numeric = 0) 
    {
		mt_srand((double)microtime() * 1000000);
		if($numeric) {
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		} else {
			$hash = '';
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) {
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}
    public static  function delLink($str)
    {
        return preg_replace("/<a [^>]*>|<[\s]*\/a>/i","",$str);
        //拷贝到编辑器中的文章，会将空格加上一个分段符，导致段落间距过大，在此滤除
        return  str_replace('<p>&nbsp;</p>','',$str);
    }
}

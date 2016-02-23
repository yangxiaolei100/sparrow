<?php
/**
 * 功能列表
 * 
 * getClientIp 获取客户端ip地址
 * getSelfURL  获取访问当前页面URL
 *
 */
class HFUtil{
	/**
    * getClientIp
    * @return ip
    */
    function getClientIp(){
        $ip=false;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
                if ($ip) { 
                    array_unshift($ips, $ip); 
                    $ip = FALSE; 
                }
                $num = count($ips);
            for ($i = 0; $i < $num; $i++) {
                if (!eregi ("^(10\.|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/i',$ip)) {
            return $ip;
        } else {
            return false;
        } 
    }
    /**
     * 获取访问当前页面地址
     */
    function getSelfURL(){  
	    //$_SERVER["REQUEST_URI"] 只有 apache 才支持,  
	    //因此需要下面的判断来解决通用问题  
	    if (isset($_SERVER['REQUEST_URI']))  
	    {  
	        $serverrequri = $_SERVER['REQUEST_URI'];   
	    }  
	    else  
	    {  
	        if (isset($_SERVER['argv']))  
	        {  
	            $serverrequri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];  
	        }  
	        else if(isset($_SERVER['QUERY_STRING']))  
	        {  
	            $serverrequri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];  
	        }  
	    }  
	    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";  
	    $protocal_temp = strtolower($_SERVER["SERVER_PROTOCOL"]);
	    $protocol = substr($protocal_temp,0,strpos($protocal_temp, "/")).$s;  
	    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);  
	    return $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri;     
	}  
}
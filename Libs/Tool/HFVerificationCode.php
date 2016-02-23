<?php
class HFVerificationCode{
	public function drawCode(){
		//@session_start();
		$im=imagecreatetruecolor(90,30);//图片大小
		$bg=imagecolorallocate($im,255,255,255);
		$te=imagecolorallocate($im,rand(50,200),rand(0,10),0);
		imagefill($im, 0, 0, $bg);
		 for($i=0;$i<3;$i++){
			imageline($im,rand(0,200),rand(0,200),rand(100,-200),rand(0,-200),$te);
			imageline($im,rand(100,-100),rand(0,200),rand(0,200),rand(100,-100),$te);
		}
		 for($i=0;$i<100;$i++){
			imagesetpixel($im,rand()%100,rand()%30,$te);
		}
		imageline($im,0,0,89,29,$te);
		imageline($im,0,0,89,0,$te);
		imageline($im,0,0,0,30,$te);
		imageline($im,89,0,89,29,$te);
		imageline($im,0,29,89,29,$te);
		$array="款到垦丁国去我饿人他一哦平领看就解好个过飞的是啊在想拍将来更没新才被粘帽";
		$len=strlen($array);
		$str1=substr($array, rand(0, $len/3-1)*3, 3);
		$str2=substr($array, rand(0, $len/3-1)*3, 3);
		/*echo $str1.$str2;
		$str1=iconv("gbk","utf-8",$str1); 
		$str2=iconv("gbk","utf-8",$str2); 
		echo $str1.$str2;*/
		//$_SESSION['check_pic']=$str1.$str2;
		//imagettftext(im,size,angle,x,y,color,font,str);
		imagettftext($im,rand(18,22),rand(0,10),rand(1,30),rand(18,32),$te,FARME_ROOT.DS.'Res'.DS.'STXINGKA.TTF',$str1);
		imagettftext($im,rand(18,22),rand(0,10),rand(45,60),rand(18,32),$te,FARME_ROOT.DS.'Res'.DS.'STXINGKA.TTF',$str2);
		
		@header("content-type: image/jpeg");
		imagejpeg($im);
		return $str1.$str2;
	}
}

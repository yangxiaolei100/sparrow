<?php
/**
分页类
*/
class HFPage {
	private $allCount = "";//总记录数
	private $currPage = "";//当前页
	private $pageSize = "";//每页显示行数
	private $offPage = "5";//显示当前页码范围，如当前页是第90页，offPage=5，则显示的页码包括85,86,87,88,89,90,91,92,93,94,95
	public function __construct($allCount, $pageSize, $currPage, $offPage=5){
		$this->allCount = $allCount;
		$this->currPage = $currPage;
		$this->pageSize = $pageSize;
		$this->offPage = $offPage;
	}



    public function pagination(){
        if($this->allCount>0){
            //global $url;//当前文件路径
            global $QUERY_STRING; //系统参数串
            global $_POST;
            //$queryString = $QUERY_STRING;
            //echo $queryString.'<br>';
            $pageStr = "";
            $url = $_SERVER['REQUEST_URI'];
            /**  支持查询中的参数    */
            $queryString = '';
            while (list($key,$val) = each($_POST)){
                if ($val && !is_array($val)) $queryString.= "$key=$val&";
            }
            /**
            去除多余的参数
             */
            //echo $queryString;
            $queryString = preg_replace("/page=([0-9]+)/i","",$QUERY_STRING);
            $url = preg_replace('/\/page=(\d+)(&[a-zA-Z0-9]+)?/i','${1}',$url);
            if(substr($url,-1)!='/'){
                $url .= '/';
            }
            $arr = explode("&",$queryString);
            foreach($arr as $key=>$value){
                $subArr = explode("=",$value);
                if($subArr[0]=="c"||$subArr[0]=="a"||$subArr[0]=="page"){
                    unset($arr[$key]);
                }
            }
            $queryString = implode("&",$arr);
            $queryString = empty($queryString) ? "":"&".$queryString;
            //echo $queryString;

            $pageCount = ceil($this->allCount / $this->pageSize);
            $pageStr .= "共".$this->allCount."条 ";
            $pageStr .= $this->currPage == 1?" 首页":' <a href='.$url.'page=1'.$queryString.'>首页</a> ';
            $pageStr .= $this->currPage > 1?' <a href='.$url.'page='.($this->currPage-1).$queryString.'>上一页</a> ':"上一页";

            for($i=-$this->offPage;$i<=$this->offPage;$i++){
                $showPage=$this->currPage+$i;
                if($showPage>0&&$showPage<=$pageCount){
                    if($this->currPage == $showPage){$font1 = '<font color="#FF0000">';$font2 = '</font>';}else{$font1 = '';$font2 = '';}
                    $pageStr .= '<a href='.$url.'&page='.$showPage.$queryString.'>'.$font1.$showPage.$font2.'</a> ';
                }
            }
            $pageStr .= $this->currPage < $pageCount?' <a href='.$url.'page='.($this->currPage+1).$queryString.'>下一页</a> ':"下一页";
            $pageStr .= $this->currPage == $pageCount?" 尾页":' <a href='.$url.'page='.$pageCount.$queryString.'>尾页</a> ';
            $pageStr .= " 共".$pageCount."页";
            return $pageStr;
        }
    }

	public function paginationNew(){
		if($this->allCount>0){
			global $_POST;
			$pageStr = "";
            $temp = explode('/',$_SERVER['REQUEST_URI']);
            array_pop($temp);
            $url = implode('/',$temp)."/";
            $url .= '?';
			 /**  支持查询中的参数*/
            $queryString = $this->getNewParaStr($_SERVER['QUERY_STRING']);
            $queryString = empty($queryString)? '' : '&'.$queryString;
			
			$pageCount = ceil($this->allCount / $this->pageSize);
			$pageStr .= "共".$this->allCount."条 ";
			$pageStr .= $this->currPage == 1?" 首页":' <a href='.$url.'page=1'.$queryString.'>首页</a> ';
			$pageStr .= $this->currPage > 1?' <a href='.$url.'page='.($this->currPage-1).$queryString.'>上一页</a> ':"上一页";
			
			for($i=-$this->offPage;$i<=$this->offPage;$i++){
				$showPage=$this->currPage+$i;
				if($showPage>0 && $showPage<=$pageCount){

					if($this->currPage == $showPage){$font1 = '<font color="#FF0000">';$font2 = '</font>';}else{$font1 = '';$font2 = '';}
					$pageStr .= '<a href='.$url.'page='.$showPage.$queryString.'>'.$font1.$showPage.$font2.'</a> ';
				}
			}
			$pageStr .= $this->currPage < $pageCount?' <a href='.$url.'page='.($this->currPage+1).$queryString.'>下一页</a> ':"下一页";
			$pageStr .= $this->currPage == $pageCount?" 尾页":' <a href='.$url.'page='.$pageCount.$queryString.'>尾页</a> ';
			$pageStr .= " 共".$pageCount."页"." 每页".$this->pageSize."条";
			return $pageStr;
		}
	}
    public function getNewParaStr($urlParaStr = ''){
        if(empty($urlParaStr))return '';
        $parr = explode('&',$urlParaStr);
        $paraStr = '';
        if(!empty($parr))foreach($parr as $str){
            $str = trim($str,'/');
            $keyAndVal = explode('=',$str);
            if(strtoupper($keyAndVal[0]) == 'C' || strtoupper($keyAndVal[0]) == 'A' || strtoupper($keyAndVal[0]) == 'PAGE' ){
                continue;
            }
            $paraStr .= "&".$str;
        }
        $paraStr = trim($paraStr,'&');

        return $paraStr;
    }
    public function getCurrentPage($urlParaStr = ''){
        if(empty($urlParaStr))return '';
        $parr = explode('&',$urlParaStr);
        if(!empty($parr))foreach($parr as $str){
            $keyAndVal = explode('=',$str);
            if(strtoupper($keyAndVal[0]) == 'PAGE' ){
                return $keyAndVal[1];
            }
        }
        return 1;
    }
}
//$currPage=$_GET["page"];
//$page = new HFPage(201,$currPage,10);
//$page->pagination();

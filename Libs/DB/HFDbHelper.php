<?php
class HFDbHelper extends HFMysqlDB{
	var $errMsg	= array();
	public function __construct($host,$database,$dbuser,$password,$chareset){
		parent::__construct($host,$database,$dbuser,$password,$chareset);
	}
	/**
	 * 获取内容
	 *
	 * @param string $tableName 表名
	 * @param string/array $col 返回的列
	 * @param string/array $where 条件
	 * @param string/array $orderby 排序方式
	 * @param int $start 数据库起始位置
	 * @param int $len 长度
	 * @return array/object
	 */
	public function select($tableName,$col=array(),$where=array(),$orderBy=array(),$start=0,$len=0,$dateType='O',$debug=false)
	{
		if (!$tableName) {
			$this -> errMsg[]	= 'select:表名为空';
			return false;
		}
		//解析列
		$col_str = $this -> parseSelectCol($col);
		if (!$col_str) {
			$this -> errMsg[]	= 'select:列名为空';
			return false;
		}
		//解析where条件
		$where_str	= $this -> parseWhere($where);
		$where_str  = empty($where_str) ? '' : ' WHERE '.$where_str;
		//解析order by 子句
		$orderBy 	= $this -> parseOrderBy($orderBy);
		$orderBy  	= empty($orderBy) ? '' : ' ORDER BY '.$orderBy;
		//限制条件
		$start 		= (int)$start;
		$len		= (int)$len;
		$limit		= '';
		$limit		= $len > 0 ? "LIMIT {$start},{$len}" : '';
		$sql = "SELECT {$col_str} FROM `{$tableName}` {$where_str} {$orderBy} {$limit} ";
		if($debug == true)echo $sql;
		if (1 == $len) {
			return $this -> getRow($sql,$dateType);
		}else{
			return $this -> getResults($sql,$dateType);
		}
	}
	/**
	 * 插入操作
	 *
	 * @param string $tableName 表名
	 * @param array $col 列名
	 * @param bool $lastid 是否返回ID
	 * @return bool/int
	 */
	public function insert($tableName,$col=array(),$lastId=false,$debug = false)
	{
		if (!$tableName) {
			$this -> errMsg[]	= 'insert:表名为空';
			return false;
		}
		//解析列
		$col_str 	= $this -> parseInsertCol($col);
		if (empty($col_str)) {
			$this -> errMsg[]	= 'insert:插入的列为空';
			return false;
		}
		$sql = "INSERT INTO `{$tableName}` {$col_str}";
        if($debug){
            echo $sql;
        }
		if ($this -> execSql($sql)) {
			if (true == $lastId) {
				return (int)$this -> getLastInsertID();
			}
			return true;
		}
		$this -> errMsg[]	= 'insert:查询失败';
		return false;
	}
	/**
	 * 更新操作
	 *
	 * @param string $tableName 表名
	 * @param string/array $col 列
	 * @param string/array $where 条件
	 * @param array $orderby 排序
	 * @param int $limit 限制
	 * @return bool
	 */
	public function update($tableName,$col=array(),$where=array(),$orderBy=array(),$limit=0,$debug = false)
	{
		if (!$tableName) {
			$this -> errMsg[]	= 'update:表名为空';
			return false;
		}
		//解析列
		$colStr = $this -> parseUpdateCol($col);
		if (empty($colStr)) {
			$this -> errMsg[] = 'update:列为空';
			return false;
		}
		//解析where
		$whereStr	= $this -> parseWhere($where);
		$whereStr   = empty($whereStr) ? '' : ' WHERE '.$whereStr;
		//解析order by 子句
		$orderByStr = $this -> parseOrderBy($orderBy);
		$orderByStr = empty($orderByStr) ? '' : ' ORDER BY '.$orderByStr;
		$limit		= (int)$limit;
		$limit		= $limit > 0 ? 'LIMIT '.$limit : '';
		
		$sql = "UPDATE `{$tableName}` SET {$colStr} {$whereStr} {$orderByStr} {$limit}";
		if($debug)echo $sql;
		if ($this -> execSql($sql)){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 删除操作
	 *
	 * @param string $tableName 表名
	 * @param string/array $where 条件
	 * @param string/array $orderBy 排序
	 * @param int $limit 限制
	 * @return bool
	 */
	public function delete($tableName,$where,$orderBy=array(),$limit=0)
	{
		if (!$tableName) {
			$this -> errMsg[]	= 'update:表名为空';
			return false;
		}
		//解析where
		$whereStr	= $this -> parseWhere($where);
		$whereStr   = empty($whereStr) ? '' : ' WHERE '.$whereStr;
		
		//解析order by 子句
		$orderByStr = $this -> parseOrderBy($orderBy);
		$orderByStr = empty($orderByStr) ? '' : ' ORDER BY '.$orderByStr;
		
		$limit		= (int)$limit;
		$limit		= $limit > 0 ? 'LIMIT '.$limit : '';
		
		$sql = "DELETE FROM `{$tableName}` {$whereStr} {$orderByStr} {$limit}";
		if ($this -> execSql($sql) ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * 统计记录数
	 *
	 * @param string $tableName 表名
	 * @param string/array $where 条件
	 * @return int
	 */
	public function count($tableName, $where=array(),$isDebug = false)
	{
		return $this->one($tableName,'COUNT(*)',$where,$isDebug);
	}
	/**
	 * 获取一个字段的内容
	 *
	 * @param string $tableName
	 * @param string $col
	 * @param string/array $where
	 * @return string
	 */
	public function one($tableName,$col='',$where=array(),$debug = false)
	{
		if (!$tableName) {
			$this -> errMsg[] = 'getOne:表名为空';
			return false;
		}
		if (!is_string($col)) {
			$this -> errMsg[] = 'getOne:返回一列只支持字符串';
			return false;
		}
		//解析where
		$whereStr	= $this->parseWhere($where);
		$whereStr   = empty($whereStr) ? '' : ' WHERE '.$whereStr;
	    $sql = "SELECT {$col} FROM {$tableName} {$whereStr}";
	    if($debug)echo $sql;
	    return $this->getVar($sql);
	}
	/**
	 * 解析select条件
	 *
	 * @param array/string $col 列
	 * @return string
	 */
	private function parseSelectCol($col){
		if (empty($col)) {
			return '*';
		}
		if (is_string($col)) {
			return $col;
		} else if (is_array($col)) {
			return implode(',',$col);
		} else {
			return '*';
		}
	}
	/**
	 * 解析where字符串
	 *
	 * @param string/array $where 条件
	 * @return string
	 */
	private function parseWhere($where){
		if (empty($where)) {
			return '';
		}
		if (is_string($where)) {
			return $where;
		} else if (is_array($where)) {
			$str = '';
			foreach ($where as $key=>$item) {
				if ($item === null || !$key) {continue;}
				if (empty($str)) {
					if (is_int($item)) {
						$str = trim($key).'='.$item.'';
					} else {
						$str = trim($key)."='".$item."'";
					}
				} else {
					if (is_int($item)) {
						$str .= ' AND '.trim($key).'='.$item.'';
					} else {
						$str .= ' AND '.trim($key)."='".$item."'";
					}
				}
			}
			return $str;
		} else {
			return '';
		}
	}
	/**
	 * 解析insert列
	 *
	 * @param array $col 要插入的列
	 * @return string
	 */
	private function parseInsertCol($col)
	{
		if (empty($col)) {
			return '';
		}
		if (!is_array($col)) {
			return '';
		}
		$col_name	= "";
		$value		= "";
		$sql        = "";
		foreach ( $col as $key=>$item ){
			if (empty($col_name)) {
				$col_name 	= trim($key);
				if( is_int($item) ){
				     $value	= $item;
				}else{
				     $value	= "'$item'";
				}
			} else {
				$col_name .= ",".trim($key);
				if( is_int($item) ){
				     $value	.= ","."$item";
				}else{
				     $value	.= ","."'$item'";
				}
			}
		}
		if (!empty($col_name) && !empty($value)) {
			$sql  = "({$col_name}) VALUES ({$value})";
		}	
		return $sql;
		
	}
	/**
	 * 解析更新子句
	 *
	 * @param string/array $col 列
	 * @return string
	 */
	private function parseUpdateCol($col){
		if (empty($col)) {
			return '';
		}
		if (is_string($col)) {
			return $col;
		} else if (is_array($col)) {
		    $update_str	= "";
			foreach ( $col as $key=>$item ) {
				$key  = trim($key);
				if (empty($update_str)) {
				    if( is_int($item) ){
				         $update_str	= $key."=".$item;
				    }else{
					     $update_str	= $key."='".$item."'";
				    }
				} else {
				    if( is_int($item) ){
				         $update_str	.= ",".$key."=".$item;
				    }else{
					     $update_str	.= ",".$key."='".$item."'";
				    }
				}
			}
			return $update_str;
		} else {
			return '';
		}
	}

	/**
	 * 解析order by 子句
	 *
	 * @param array/string $orderby 子句
	 * @return string
	 */
	private function parseOrderBy($orderby)
	{
		if (empty($orderby)) {
			return '';
		}
		if (is_string($orderby)) {
			return $orderby;
		} else if (is_array($orderby)) {
			$order_str	= '';
			foreach ($orderby as $key=>$row) {
				if (empty($order_str)) {
					if (is_int($key)) {
						$order_str = $row;
					} else {
						$row = strtoupper($row);
						$order_str = $key.' '.$row;
					}
				} else {
					if (is_int($key)) {
						$order_str .= $row;
					} else {
						$row = strtoupper($row);
						$order_str .= ','.$key.' '.$row;
					}
				}
			}
			return $order_str;
		} else {
			return '';
		}
	}
}
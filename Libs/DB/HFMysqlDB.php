<?php
class HFMysqlDB extends PDO{
	public $host	 = "";
	public $database = "";
	public $dbuser	 = "";
	public $password = "";
	public $chareset = "";
	protected $beginTransaction = 0;
	public function __construct($host,$database,$dbuser,$password,$chareset){
		$this->host = $host;
		$this->database = $database;
		$this->dbuser = $dbuser;
		$this->password = $password;
		$this->chareset = $chareset;
		if(empty($this->host) && empty($this->database) && empty($this->dbuser) && empty($this->password) && empty($this->chareset)){
			trigger_error("您正在试图使用框架提供的类库，请调用此类的构造方法，初始化数据库连接信息。");
		}
		$dns = "mysql:dbname=$this->database;host=$this->host";
		parent::__construct($dns,$this->dbuser,$this->password);
		$sql = "SET NAMES '$this->chareset';";
		$this->execSql($sql);
	}
	/**
	 * 获取查询结果
	 * 
	 * @param string $sql 	sql语句
	 * @param string $type O返回对应结果，A返回关联数据结果，N返回数字下标结果，B同时返回关联数组和数字下标结果
	 * @return array 查询结果
	 */
	public function getResults($sql,$type = 'O',$debug = false){
		$type = strtoupper($type);
        if($debug)echo $sql;
		$res = $this->query($sql);
		if(!empty($res)){
			switch($type){
				case 'O':
				$res->setFetchMode(PDO::FETCH_OBJ);
				break;
				case 'A':
				$res->setFetchMode(PDO::FETCH_ASSOC);
				break;
				case 'N':
				$res->setFetchMode(PDO::FETCH_NUM);
				break;
				case 'B':
				$res->setFetchMode(PDO::FETCH_BOTH);
				break;
				default:
				$res->setFetchMode(PDO::FETCH_OBJ);
				break;
			}
			$ret = $res->fetchAll() or null;
			return $ret;
			//print_r($res->fetchAll());
		}else{
			return $res;
		}
	}
	/**
	 * 获取一行查询结果
	 * 
	 * @param string $sql 	sql语句
	 * 
	 * @return array 单行结果，结果为对象类型
	 */
	public function getRow($sql,$type = 'O'){
		$type = strtoupper($type);
		$res = $this->query($sql);
		switch($type){
				case 'O':
				$res->setFetchMode(PDO::FETCH_OBJ);
				break;
				case 'A':
				$res->setFetchMode(PDO::FETCH_ASSOC);
				break;
				case 'N':
				$res->setFetchMode(PDO::FETCH_NUM);
				break;
				case 'B':
				$res->setFetchMode(PDO::FETCH_BOTH);
				break;
				default:
				$res->setFetchMode(PDO::FETCH_OBJ);
				break;
			}
		$result = $res->fetchAll();
		return isset($result[0]) ? $result[0] : null;
	}
	/**
	 * 获取单个值的查询结果
	 * 
	 * @return string 单值结果
	 * @return null   查询结果为空
	 */
	public function getVar($sql){
		$res = $this->query($sql);
        if(empty($res)) return null;
		$ret = $res->fetch(PDO::FETCH_COLUMN);
		return $ret === false ? null : $ret;
	}
	/**
	 * 获取插入后最后自增增长的id，封装PDO的lastInsertId方法
	 * 
	 * @return int 自增长id
	 */
	public function getLastInsertID(){
		try {
			$lastId = $this->lastInsertId();
		} catch (Exception $e) {
			return null;
		}
		return $lastId;
	}
	/**
	 * 执行update,insert，delete语句，封装PDO的exec方法
	 * 
	 * @param string $sql 	sql
	 * @return int 			影响的行数
	 */
	public function execSql($sql){
		if(empty($sql)){
			trigger_error("参数为空");
		}
		$res = $this->exec($sql);
		if($res === false){
			return false;
			echo '执行失败失败:'.$sql;
			trigger_error("操作数据库失败");
		}else{
			return $res;
		}
	}
	/**
	 * 锁定表
	 * @param string $tableName 表名
	 */
	public function lockTable($tableName){
		$this->exec("LOCK TABLE $tableName WRITE");
	}
	/**
	 * 解锁定表
	 */
	public function unlockTable($tableName){
		$this->exec('UNLOCK TABLES');
	}
	/**
	 * 开始一个事务，封装pdo的beginTransaction方法
	 */
	function beginTransaction() 
    { 
        if(!$this->transactionCounter++) 
            return parent::beginTransaction(); 
       return $this->transactionCounter >= 0; 
    } 
	/**
	 * 提交一个事务，封装pdo的commit方法
	 */
    function commit() 
    { 
       if(!--$this->transactionCounter) 
           return parent::commit(); 
       return $this->transactionCounter >= 0; 
    } 
	/**
	 * 回滚事务，封装pdo的rollback方法
	 */
    function rollback() 
    { 
        if($this->transactionCounter >= 0) 
        { 
            $this->transactionCounter = 0; 
            return parent::rollback(); 
        } 
        $this->transactionCounter = 0; 
        return false; 
    } 
	
}
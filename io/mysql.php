<?php

class AysMysql{
	public $dbSource = "";
	public $dbConfig = [];//mysql的配置
	public function __construct(){
		$this->dbSource = new Swoole\Mysql;
		$this->dbConfig = [
			'host'=>'127.0.0.1',
			'port'=>3306,
			'user'=>'root',
			'password'=>123456,
			'database'=>'swoole',
			'charset'=>'utf8'
		];
	}
	public function update(){
		
	}
	public function add(){
	
	}
	public function execute($id,$username){
		//
		$this->dbSource->connect($this->dbConfig,function($db,$connect_result) use ($id,$username){
			//连接成功?
			if(false === $connect_result){
				var_dump($db->connect_error);
				return false;
			}
			echo "链接成功".PHP_EOL;
//			$sql = "select * from test where id = 1";
			$sql = "update test set `dame` = '{$username}' where id = {$id}";
			//query, 执行(add select update delete)
			$db->query($sql,function($db,$result){
				//select =>result 返回的是 查询的结果内容
				// add update delete 返回true \ false
				if(false === $result){
					//执行失败
					var_dump($result)."执行失败".PHP_EOL;
				}else if(true === $result){
					// add update delete
					var_dump($result)."执行结果".PHP_EOL;
				}else{
					echo "查询成功".PHP_EOL;
					//查询select
					var_dump($result);
				}
				echo "关闭数据库的链接".PHP_EOL;
				$db->close();//关闭连接
			});
		});
		return true;
	}
}

$obj = new AysMysql();
$flag = $obj->execute(1,'singwa-1111');
var_dump($flag).PHP_EOL;

echo "start...".PHP_EOL;

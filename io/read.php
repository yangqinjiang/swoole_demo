<?php


//读取文件
//只能读取2M的文件
$result = swoole_async_readfile(__DIR__."/1.txt",function($filename,$fileContent){
	echo "filename:".$filename.PHP_EOL;
	echo "content:".$fileContent.PHP_EOL;
});
var_dump($result);
echo "start".PHP_EOL;

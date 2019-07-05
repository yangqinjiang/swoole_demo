<?php

$content = date("Y-m-d H:i:s").PHP_EOL;

$result = swoole_async_writefile(__DIR__."/1.log",$content,function($filename){
	echo "success".PHP_EOL;
},FILE_APPEND);//
var_dump($result);
echo "start".PHP_EOL;

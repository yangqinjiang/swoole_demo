<?php

$redisClient = new swoole_redis;
$redisClient->connect('127.0.0.1',6379,function(swoole_redis $redisClient,$result){
   echo "connect success".PHP_EOL;
	var_dump($result);//连接成功后的返回值
	//异步
	$redisClient->set('singwa_1',time(),function($client,$result){
		echo($result).PHP_EOL;//输出 OK
	});
	//get 
	$redisClient->get('singwa_1',function($client,$result){
		echo "返回值".PHP_EOL;
		var_dump($result);
	});

	$redisClient->keys('*',function($client,$result){
		echo "返回值".PHP_EOL;
		var_dump($result);
	});
	$redisClient->close();
});
echo "start".PHP_EOL;

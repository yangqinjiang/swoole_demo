<?php

$http = new swoole_http_server('0.0.0.0',8811);

$http->on('request',function($req,$res){
	//协程代码, 要放在 onRequest,On......内使用
	//获取redis里面的key的内容,然后输出到浏览器
	$redis = new Swoole\Coroutine\Redis();
	$redis->connect('127.0.0.1',6379);
	$value = $redis->get($req->get['a']);
	$res->header('Content-Type',"text/plain");
	$res->end($value);
});

$http->start();

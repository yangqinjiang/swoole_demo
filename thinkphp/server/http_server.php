<?php

$http = new swoole_http_server("0.0.0.0",8811);

//静态文件
// curl http://127.0.0.1:8811/hello.html
//当程序找到静态文件时，则输出它,否则向下执行
$http->set([
	'enable_static_handler'=>true,
	'document_root'=>__DIR__."/../publi/static"
]);
$http->on("request",function($request,$response){
	echo __DIR__."/../publi/static";
	//print_r($request->get);
	//记录日志
	$content = [
	'date:'=>date('Ymd His'),
	'get:'=>$request->get,
	'post:'=>$request->post,
	'header:'=>$request->header,
	];
	swoole_async_writefile(__DIR__."/access.log",json_encode($content).PHP_EOL,function($filename){},FILE_APPEND);
	$response->cookie("singwa","xssss",time()+1800);
	$response->end("sss".json_encode($request->get));
});

//启动
$http->start();

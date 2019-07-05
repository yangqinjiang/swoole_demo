<?php

$http = new swoole_http_server("0.0.0.0",8811);

//静态文件
// curl http://127.0.0.1:8811/hello.html
//当程序找到静态文件时，则输出它,否则向下执行
$http->set([
	'enable_static_handler'=>true,
	'document_root'=>"/home/root/study/code/static"
]);
$http->on("request",function($request,$response){
	print_r($request->get);
	$response->cookie("singwa","xssss",time()+1800);
	$response->end("sss".json_encode($request->get));
});

//启动
$http->start();

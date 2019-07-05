<?php

$serv = new Swoole\Server("127.0.0.1", 9501);
$serv->set([
	'worker_num'=>4,//worker进程数 cpu 1-4
	'max_request'=>10000,//最大请求数
]);
$serv->on('connect', function ($serv, $fd){
    echo "Client {$fd} :Connect.\n";
});
$serv->on('receive', function ($serv, $fd, $reactor_id, $data) {
    $serv->send($fd, "Swoole: {$fd} - {$reactor_id} ".$data);
});
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.-- {$fd}\n";
});
$serv->start();


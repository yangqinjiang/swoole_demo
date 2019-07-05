<?php
$server = new swoole_websocket_server("0.0.0.0", 8812);
//静态文件
// curl http://127.0.0.1:8812/hello.html
//当程序找到静态文件时，则输出它,否则向下执行
$server->set([
        'enable_static_handler'=>true,
        'document_root'=>"/home/root/study/code/static"
]);
$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});

$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
    $server->push($frame->fd, json_encode(["hello", "world"]));
});

$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});
echo "open http://127.0.0.1:8812/ws_client.html\n";
$server->start();

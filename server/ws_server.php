<?php
$server = new swoole_websocket_server("0.0.0.0", 8812);

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
echo "open http://127.0.0.1:8811/ws_client.html";
$server->start();

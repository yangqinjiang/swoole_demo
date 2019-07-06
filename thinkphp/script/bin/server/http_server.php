<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 18/2/28
 * Time: 上午1:39
 */
$http = new swoole_http_server("0.0.0.0", 8811);

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => __DIR__."/../../../public/static",//静态文件夹,
        'worker_num' => 5,
    ]
);
$http->on('WorkerStart', function(swoole_server $server,  $worker_id) {
    
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../../../application/');
    // 加载框架里面的文件
    require __DIR__ . '/../../../thinkphp/base.php';
    //不要加载 start.php
    //require __DIR__ . '/../thinkphp/start.php';
});
$http->on('request', function($request, $response) use($http){

    //还原$_SERVER数据
    $_SERVER  =  [];
    if(isset($request->server)) {
        foreach($request->server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    //还原header数据
    if(isset($request->header)) {
        foreach($request->header as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    //还原$_GET数据
    $_GET = [];
    if(isset($request->get)) {
        foreach($request->get as $k => $v) {
            $_GET[$k] = $v;
        }
    }
    //还原$_POST数据
    $_POST = [];
    if(isset($request->post)) {
        foreach($request->post as $k => $v) {
            $_POST[$k] = $v;
        }
    }
    //清除缓冲区
    ob_start();
    // 执行应用并响应
    try {
        //container有命名空间
        think\Container::get('app', [APP_PATH])
            ->run()
            ->send();
    }catch (\Exception $e) {
        // todo
    }

    //echo "-action-".request()->action().PHP_EOL;
    $res = ob_get_contents();//读取缓冲区的内容
    ob_end_clean();
    $response->end($res);
});

$http->start();

// topthink/think-swoole
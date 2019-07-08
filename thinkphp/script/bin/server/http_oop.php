<?php
/**
 * 将swoole_http_server编写成对象
 */
class Http {
    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;

    public $http = null;
    public function __construct() {
        $this->http = new swoole_http_server(self::HOST, self::PORT);

        $this->http->set(
            [
                'enable_static_handler' => true,
                'document_root' => __DIR__."/../../../public/static",//静态文件夹,
                'worker_num' => 4,
                'task_worker_num' => 4,
            ]
        );

        $this->http->on("workerstart", [$this, 'onWorkerStart']);
        $this->http->on("request", [$this, 'onRequest']);
        $this->http->on("task", [$this, 'onTask']);
        $this->http->on("finish", [$this, 'onFinish']);
        $this->http->on("close", [$this, 'onClose']);

        $this->http->start();
    }

    //分别有 worker_num个进程启动下面的代码
    /**
     * @param $server
     * @param $worker_id
     */
    public function onWorkerStart($server,  $worker_id) {
            // 定义应用目录
            define('APP_PATH', __DIR__ . '/../../../application/');
            // 加载框架里面的文件
            require __DIR__ . '/../../../thinkphp/base.php';
    }

    /**
     * request回调
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response) {
        echo "some one request".PHP_EOL;
        //还原$_SERVER数据
        $_SERVER  =  [];//一定要清空,否则下次过来会保持上次请求的数据
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
        $_GET = [];//一定要清空,否则下次过来会保持上次请求的数据
        if(isset($request->get)) {
            foreach($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }
        //还原$_POST数据
        $_POST = [];//一定要清空,否则下次过来会保持上次请求的数据
        if(isset($request->post)) {
            foreach($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }

        $_POST['http_server'] = $this->http;//保持http实例

        ob_start();
        // 执行应用并响应
        //捕获异常
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
        //$http->close();//粗暴地关闭链接.可清空变量??但会在终端上报错
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($serv, $taskId, $workerId, $data) {

        // 分发 task 任务机制，让不同的任务 走不同的逻辑
        $obj = new app\common\lib\task\Task;

        $method = $data['method'];
        $flag = $obj->$method($data['data']);
        

        return $flag; // 告诉worker
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";
    }

    /**
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd) {
        echo "clientid:{$fd}\n";
    }
}

new Http();
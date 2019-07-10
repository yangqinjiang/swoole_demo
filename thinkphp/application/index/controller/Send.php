<?php
namespace app\index\controller;
use app\common\lib\Util;
class Send
{
    /**
     * 发送验证码
     */
    public function index() {
        // tp  input
        //TODO: swoole与request的bug
        //$phoneNum = request()->get('phone_num', 0, 'intval');
        $phoneNum = intval($_GET['phone_num']);
        //TODO:参数检测
        if(empty($phoneNum)) {
            // status 0 1  message data
            return Util::show(config('code.error'), 'error');
        }

        //tood
        // 生成一个随机数
        $code = 6666;//rand(1000, 9999);

        $taskData = [
            'method' => 'sendSms',
            'data' => [
                'phone' => $phoneNum,
                'code' => $code,
            ]
        ];
        //sleep(1);
        //TODO: 放到task里面发送短信, 因为我们不相信外部系统的稳定性
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'OK');
         
    }
}

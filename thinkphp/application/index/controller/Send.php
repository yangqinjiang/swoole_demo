<?php
namespace app\index\controller;
use app\common\lib\ali\Sms;
use app\common\lib\Util;
use app\common\lib\Redis;
class Send
{
    /**
     * 发送验证码
     */
    public function index() {
        // tp  input
        //$phoneNum = request()->get('phone_num', 0, 'intval');
        $phoneNum = intval($_GET['phone_num']);
        if(empty($phoneNum)) {
            // status 0 1  message data
            return Util::show(config('code.error'), 'error');
        }

        //tood
        // 生成一个随机数
        $code = rand(1000, 9999);

        $taskData = [
            'method' => 'sendSms',
            'data' => [
                'phone' => $phoneNum,
                'code' => $code,
            ]
        ];
        sleep(1);
        $send_ok = true;//TODO:
        if($send_ok){
            return Util::show(config('code.success'), 'ok'.$code);
        }else{
            return Util::show(config('code.error'), '验证码发送失败');
        }
         
    }
}

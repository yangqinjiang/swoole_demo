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
        sleep(1);
        $send_ok = true;//TODO:使用easy sms 库来发送短信 https://github.com/overtrue/easy-sms


        if($send_ok){
            //TODO: 记录code到redis,使用协程Redis
            //下面的代码, 跑不通, 
            /*$redis = new \Swoole\Coroutine\Redis();
            $redis->connect(config('redis.host'),config('redis.port'));
            $redis->set(\app\common\lib\Redis::smsKey($phoneNum),$code,config('redis.out_time'));
            $redis->close();*/
            //异步
            $redisClient = new \swoole_redis;
            $redisClient->connect(config('redis.host'),config('redis.port'),
            function(\swoole_redis $redisClient,$result) use($phoneNum,$code){
                $redisClient->set(\app\common\lib\Redis::smsKey($phoneNum),$code,config('redis.out_time'));
                $redisClient->close();
            });
            return Util::show(config('code.success'), $taskData);
        }else{
            return Util::show(config('code.error'), '验证码发送失败');
        }
         
    }
}

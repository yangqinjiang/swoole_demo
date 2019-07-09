<?php
/**
 * 代表的是  swoole里面 后续 所有  task异步 任务 都放这里来
 * Date: 18/3/27
 * Time: 上午1:20
 */
namespace app\common\lib\task;
use app\common\lib\ali\Sms;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;
class Task {

    /**
     * 异步发送 验证码
     * @param $data
     * @param $serv swoole server对象
     */
    public function sendSms($data, $serv=null) {
        echo "发送短信".PHP_EOL;
        //TODO:使用easy sms 库来发送短信 https://github.com/overtrue/easy-sms
        //默认发送成功
        Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
        return true;
        /*
        try {
            $response = Sms::sendSms($data['phone'], $data['code']);
        }catch (\Exception $e) {
            // todo
            return false;
        }

        // 如果发送成功 把验证码记录到redis里面
        if($response->Code === "OK") {
            Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
        }else {
            return false;
        }
        return true;
        */
    }

    /**
     * 通过task机制发送赛况实时数据给客户端
     * @param $data
     * @param $serv swoole server对象
     */
    public function pushLive($data, $serv) {
        echo "通过task机制发送赛况实时数据给客户端".PHP_EOL;
        $clients = Predis::getInstance()->sMembers(config("redis.live_game_key"));

        foreach($clients as $fd) {
            $serv->push($fd, json_encode($data));
        }
    }
}
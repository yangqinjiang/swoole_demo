<?php
/**
 * 监控服务 ws http 8811
 */

class Server {
    const PORT = 8811;

    public function port() {
        // 2>/dev/null 去掉无用的信息
        //  | grep LISTEN | wc -l 查询到正在LISTEN 的行数,并统计
        $shell  =  "netstat -anp 2>/dev/null | grep ". self::PORT . " | grep LISTEN | wc -l";

        $result = shell_exec($shell);
        if($result != 1) {
            // 发送报警服务 邮件 短信
            /// todo
            echo date("Ymd H:i:s")."error".PHP_EOL;
        } else {
            echo date("Ymd H:i:s")."succss".PHP_EOL;
        }
    }
}

// nohup /path/to/bin/php server.php > log.file &
//检测是否运行
//   ps aux|grep monitor/server.php
swoole_timer_tick(2000, function($timer_id) {
    (new Server())->port();
    echo "time-start".PHP_EOL;
});

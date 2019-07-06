<?php

$process = new swoole_process(function(swoole_process $process){
	$process->exec('/home/root/study/soft/php7.2.19/bin/php',[__DIR__.'/../server/http_server.php']);
},false);

$pid = $process->start();
echo $pid.PHP_EOL;

swoole_process::wait();
/*
运行
php process.php
查看进程  49093下面有4个worker进程
[root@localhost ~]# netstat -anp|grep process.php
[root@localhost ~]# ps aux|grep process.php
root      49091  0.0  0.4 157516  9256 pts/0    S+   12:40   0:00 /home/root/study/soft/php7.2.19/bin/php process.php
root      49124  0.0  0.0 112708   992 pts/1    S+   12:41   0:00 grep --color process.php
[root@localhost ~]# pstree -p 49091
php(49091)───php(49092)─┬─php(49093)─┬─php(49098)
                        │            ├─php(49099)
                        │            ├─php(49100)
                        │            └─php(49101)
                        ├─{php}(49094)
                        ├─{php}(49095)
                        ├─{php}(49096)
                        └─{php}(49097)

或者
ps aft|grep http_server.php
*/

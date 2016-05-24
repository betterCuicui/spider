<?php
$workers = [];
$worker_num = 2;


$redis = new Redis();
$res = $redis->connect('127.0.0.1','6379');

for($i = 0; $i < $worker_num; $i++)
{
    $process = new swoole_process('callback_function', false, false);
    $process->useQueue();
    $pid = $process->start();
    $workers[$pid] = $process;
    //echo "Master: new worker, PID=".$pid."\n";
}

function callback_function(swoole_process $worker)
{
    //echo "Worker: start. PID=".$worker->pid."\n";
    //recv data from master

    //$recv = $worker->pop();
sleep(2);
    $redis = new Redis();
    $res = $redis->connect('127.0.0.1','6379');
    $a = $redis->lPop("$worker->pid");
    echo "PID={$worker->pid} : $a<br/>";
    sleep(2);
    $a = $redis->lPop("$worker->pid");
    echo "PID={$worker->pid} : $a<br/>";
    $worker->exit(0);
}

foreach($workers as $pid => $process)
{
    $redis->rPush("$pid","hello world");
    $redis->rPush("$pid","haha world");
    //$process->push("hello worker[$pid]\n");
}

for($i = 0; $i < $worker_num; $i++)
{
    $ret = swoole_process::wait();
    $pid = $ret['pid'];
    unset($workers[$pid]);
    echo "Worker Exit, PID=".$pid.PHP_EOL;
}
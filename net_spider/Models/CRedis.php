<?php
class CRedis
{
    public static function init(){
        $redis = new Redis();
        $config = require $_SERVER['DOCUMENT_ROOT']."/Models/CDbConfig.php";
        $res = $redis->connect($config['redis']['host'],$config['redis']['port']);
        if($res != 1){
            SeasLog::error("连接redis失败！");
        }
        return $redis;
    }
}
<?php
$_SERVER['DOCUMENT_ROOT'] = '/home/www/net_spider';
require "CRedis.php";
$redis = CRedis::init();
$a = $redis->lLen('main_list');
echo $a;
//$a = null;
//echo $a;
/*
echo false == $redis->sAdd('url_set' , "www.baidu.com");

/*
$a = $redis->lLen("main_list");
echo empty($a);
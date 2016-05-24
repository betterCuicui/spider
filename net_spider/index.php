<?php
$_SERVER['DOCUMENT_ROOT'] = '/home/www/net_spider';
require $_SERVER['DOCUMENT_ROOT']."/Controllers/CSpider.php";

$spider = new CSpider();
$spider->init(10000);
//$spider->set_first_url('http://202.118.201.228/homepage/index.do');
$spider->set_first_url('http://202.118.201.228/homepage/infoArticleList.do;?sortColumn=publicationDate&columnId=354&sortDirection=-1&pagingPage=2&pagingNumberPer=2000');
$spider->set_process_num(3);
$spider->spider_start();
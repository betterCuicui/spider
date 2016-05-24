<?php
header("Content-Type: text/html; charset=utf-8");

$aaa = curl_init();
curl_setopt($aaa,CURLOPT_URL,'http://202.118.201.228/homepage/infoArticleList.do;jsessionid=C740F0000AEB683E39F99B5FF11EC4A1.TH?sortColumn=publicationDate&pagingNumberPer=12&columnId=354&sortDirection=-1&pagingPage=1&');
curl_setopt($aaa,CURLOPT_RETURNTRANSFER,true);
curl_setopt($aaa, CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");
$output = curl_exec($aaa);
curl_close($aaa);
echo $output;
?>
/*
$ch1 = curl_init();
$ch2 = curl_init();
$ch3 = curl_init();

// 设置URL和相应的选项
curl_setopt($ch3, CURLOPT_URL, "http://192.168.72.128/net_spider/");
curl_setopt($ch3,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch3, CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");
curl_setopt($ch1, CURLOPT_URL, "http://192.168.72.128/net_spider/");
curl_setopt($ch1,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch1, CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");
curl_setopt($ch2, CURLOPT_URL, "http://192.168.72.128/net_spider/demo/demo_post.php");
curl_setopt($ch2,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch2, CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");

// 创建批处理cURL句柄
$mh = curl_multi_init();
$curl_arr = array($ch1,$ch2,$ch3);
// 增加2个句柄
$active = null;
$i = 1;
$y = 1;
do{
    $cme = curl_multi_exec($mh, $active);

    while ($done = curl_multi_info_read($mh)){
        echo "in";
        $tmp_result = curl_multi_getcontent($done['handle']);
        curl_multi_remove_handle($mh, $done['handle']);
        var_dump($tmp_result);
        echo "<br/>";
    }
    //echo "$active<br/>";
    //curl_multi_select防止CPU 100%   ??????
    if (($cme == 0 && $active == 0) && (curl_multi_select($mh) == -1)) {
        usleep(1);
    }
    if($active < 4){
        if(!empty($curl_arr)){
            curl_multi_add_handle($mh,$curl_arr[0]);
            array_splice($curl_arr,0,1);
            $active++;
        }
    }

    //echo "$active<br/>";
}while($active);
curl_multi_close($mh);
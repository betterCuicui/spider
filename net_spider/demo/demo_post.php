<?php

$data = 'theCityCode=792&theUserID=';
$curlobj = curl_init();
curl_setopt($curlobj,CURLOPT_URL,'http://www.webxml.com.cn/WebServices/WeatherWS.asmx/getWeather');
curl_setopt($curlobj,CURLOPT_HEADER,0);
curl_setopt($curlobj,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curlobj,CURLOPT_POST,1);
curl_setopt($curlobj,CURLOPT_POSTFIELDS,$data);
curl_setopt($curlobj,CURLOPT_HTTPHEADER,array("application/x-www-form-urlencoded;charset=utf-8","Content-Length: ".strlen($data)));
curl_setopt($curlobj, CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");
$rtn = curl_exec($curlobj);
if(!curl_errno($curlobj)){
    echo $rtn;
} else {
    echo 'cURL error: '.curl_error($curlobj);
}
curl_close($curlobj);
?>
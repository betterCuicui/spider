<?php

class CcUrl
{
    //初始化curl;
    public static function curl_init($url,$curlopt = array()){
        $curl_obj = curl_init();
        curl_setopt($curl_obj,CURLOPT_URL,$url);
        if(empty($curlopt)){
            curl_setopt($curl_obj,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl_obj,CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");
        } else{
            foreach ($curlopt as $k => $v){
                curl_setopt($curl_obj,$k,$v);
            }
        }
        return $curl_obj;
    }

    //通过curl_get的方式爬网页
    public static function curl_by_get($url){
        if(empty($url) || !self::check_url($url))
            return null;
        $curl_obj =self::curl_init($url);
        $output = curl_exec($curl_obj);
        if(!curl_errno($curl_obj)){
            curl_close($curl_obj);
            return $output;
        } else {
            curl_close($curl_obj);
            SeasLog::error('cURL error: '.$url.curl_error($curl_obj));
        }
        return null;
    }

    public static function check_url($url){
        if(!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
            return false;
        }
        return true;
    }
}
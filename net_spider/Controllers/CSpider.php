<?php
require $_SERVER['DOCUMENT_ROOT']."/Models/CBloomFilter.php";
require $_SERVER['DOCUMENT_ROOT'].'/Models/CcUrl.php';
require $_SERVER['DOCUMENT_ROOT'].'/Controllers/CWebResolutionSchool.php';

class CSpider
{
    private $process_num = 5;
    private $thread_num = 10;
    private $bloom_filter;
    private $is_init = false;
    private $redis_main;
    private $process_arr = array();
    const MAIN_LIST = 'main_list';
    public function init($capacity){

        //初始化seaslog
        $log_psth = SeasLog::getBasePath();
        $log_psth = $log_psth."/spider/";
        SeasLog::setBasePath($log_psth);

        //初始化布隆过滤器
        $this->bloom_filter = new CBloomFilter();
        $this->bloom_filter->init($capacity);
        $this->is_init = true;

        //初始化redis
        $this->redis_main = CRedis::init();
        $this->redis_main->flushdb();
    }
    
    //设置url
    public function set_first_url($url){
        if(!CcUrl::check_url($url)){
            echo "the url you input is error!";
        }
        $this->redis_main->rPush(self::MAIN_LIST,$url);
    }
    
    //设置启动进程数
    public function set_process_num($num = 5){
        $num = intval($num);
        if($num <= 0){
            return false;
        }
        if($num > 16)
            $num = 16;
        $this->process_num = $num;
    }
    
    //设置每个进程的线程数
    public function set_thread_num($num = 10){
        if(intval($num) <= 0){
            return;
        }
        $this->thread_num = $num;
    }
    
    //开始
    public function spider_start(){
        if($this->is_init == false){
            echo "please init spider";
            return false;
        }
        $a = $this->redis_main->llen(self::MAIN_LIST);
        if(empty($a)){
            echo "please set first url!";
            exit(0);
        }

        //创建进程
        for($i = 0; $i < $this->process_num; $i++)
        {
            $pid = pcntl_fork();
            if($pid == -1){
                echo "fork child process failed\n";
                exit(0);
            }
            if($pid == 0){
                sleep(1);
                self::callback_function();
            }
            else{
                echo "create process $pid success!\n";
                $this->process_arr[] = $pid;
            }
        }

        sleep(5);

        //总进程开始分发任务
        while(1){
            //取任务
            $url_temp = $this->redis_main->lPop(self::MAIN_LIST);
            if(empty($url_temp)){
                continue;
                //判断是否所有的进程都没干活
                $is_working = false;
                foreach ($this->process_arr as $pid) {
                    if($this->redis_main->get("{$pid}_runing")){
                        $is_working = true;
                    }
                }
                //当所有的进程都没有干活的时候，那么就给他们发退出消息
                if(!$is_working){
                    foreach ($this->process_arr as $pid) {
                        //$this->redis_main->rPush("$pid","work_exit");
                    }
                    //等待所有的进程退出
                    while(count($this->process_arr) > 0) {
                        foreach($this->process_arr as $key => $pid) {
                            $res = pcntl_waitpid($pid, $status, WNOHANG);
                            // If the process has already exited
                            if($res == -1 || $res > 0){
                                echo "\n process $pid exit\n";
                                unset($this->process_arr[$key]);
                            }
                        }
                        sleep(1);
                    }
                    echo "spider over!\n";
                    return true;
                }
            }else{
                //如果url总队列中取出了一个url，需要先经过布隆过滤器过滤一遍
                //if($this->bloom_filter->push($url_temp)){
                  if($this->redis_main->sAdd('url_set' , $url_temp)){
                    echo "url : $url_temp 通过布隆过滤器\n";
                    //找出哪个work进程的url队列任务最少
                    $min_pid = null;
                    $min_url_num = 0;
                    $j = 0;
                    foreach ($this->process_arr as $pid) {
                        $temp_num = $this->redis_main->lLen("$pid");
                        if($temp_num == 0){
                            $min_pid = $pid;
                            break;
                        }else{
                            if($j == 0 || $temp_num < $min_url_num){
                                $min_pid = $pid;
                                $min_url_num = $temp_num;
                            }
                        }
                        $j++;
                    }
                    //发布任务给最少的那个
                    echo "任务给了$min_pid\n";
                    $this->redis_main->rPush("$min_pid",$url_temp);
                }else{
                      //echo "url: $url_temp 没有通过布隆过滤器!!!\n";
                  }
            }
        }
    }

    //多进程回调函数
    public static function callback_function(){
        $pid = getmypid();

        echo "process $pid start!\n";

        $redis = CRedis::init();
        $mh = curl_multi_init();
        $active = 0;
        do{
            $cme = curl_multi_exec($mh, $active);

            //设置进程状态
            if($active > 0){
                $redis->set("{$pid}_runing",true);
            }
            //异步获取数据
            while ($done = curl_multi_info_read($mh)){
                $tmp_result = curl_multi_getcontent($done['handle']);
                //处理url
                $web_solution = new CWebResolutionSchool();
                $temp_url = curl_getinfo($done['handle'],CURLINFO_EFFECTIVE_URL);
                $web_solution->web_analytic($temp_url,$tmp_result);
                curl_multi_remove_handle($mh, $done['handle']);
            }

            //curl_multi_select防止CPU 100%   ??????
            if (($cme == 0 && $active == 0) && (curl_multi_select($mh) == -1)) {
                usleep(1);
            }

            //增加句柄，继续抓取url
            if($active < 5){
                $url = $redis->lPop("$pid");
                //if($url == 'work_exit'){
                 //   exit(0);
                //}
                if(!empty($url) && CcUrl::check_url($url)){
                    echo "pid:$pid 获得任务$url\n";
                    $curl_obj = CcUrl::curl_init($url);
                    curl_multi_add_handle($mh,$curl_obj);
                    //$redis->set("{$pid}_runing",true);
                }else{
                    continue;
                    //当url队列为空，切当前也没有url正在抓取，那么进程就不在运行；
                    if($active == 0){
                        //echo "任务为空，url队列也没有东西\n";
                        $redis->set("{$pid}_runing",false);
                    }else{
                        $redis->set("{$pid}_runing",true);
                    }
                }
            }
        }while (1);
    }
}
<?php
require $_SERVER['DOCUMENT_ROOT']."/Controllers/CWebResolution.php";
require $_SERVER['DOCUMENT_ROOT']."/Models/CRedis.php";
require $_SERVER['DOCUMENT_ROOT']."/Models/CPdoMysql.php";
require $_SERVER['DOCUMENT_ROOT'].'/Models/simple_html_dom/simple_html_dom.php';

class CWebResolutionSchool implements CWebResolution
{
    const MAIN_LIST = 'main_list';
    const URL_INFO_BEFORE = 'http://202.118.201.228/homepage/';
    const URL_LIST_BEFORE = 'http://202.118.201.228/homepage/infoArticleList.do;';
    private $url;
    private $html;
    private $redis;
    private $mysql;
    public function __construct()
    {
        $this->html = new simple_html_dom();
        $this->redis = CRedis::init();
        $this->mysql = new CPdoMysql();
    }

    //网页内容解析
    public function web_analytic($temp_url,$contents){
        $this->url = $temp_url;
        $this->html->load($contents);
        //根据url来判断是获取内容还是获取url
        if(1 == $this->get_what($temp_url)){
            echo "url: $temp_url 应该获得内容\n";
            $this->get_contents();
        }else if(2 == $this->get_what($temp_url)){
            echo "url: $temp_url 应该获得url列表\n";
            $this->set_url_list();
        }
        $this->html->clear();
    }
    
    //获取网页中有用的url，放入列表中
    public function set_url_list(){
        foreach($this->html->find('a') as $element){
            $url = $element->href;
            $url = $this->set_url($url);
            echo "\n url: $url \n";
            if(!empty($url) && CcUrl::check_url($url)){
                echo "\n url: $url 放入主队列\n";
                $this->redis->rPush(self::MAIN_LIST,$url);
            }
        }
    }
    
    //补全url
    public function set_url($url){
        if(preg_match('/infoSingleArticle.do.*columnId=[0-9]?/',$url))
            return self::URL_INFO_BEFORE.$url;
        else if(preg_match('/pagingPage=[0-9]?/',$url)){
            $url = self::URL_LIST_BEFORE.$url;
            $url = str_replace('&amp;','&',$url);
            return $url;
        }
        return null;
    }

    //获得网页的内容并且解析放入数据库中
    public function get_contents(){
        $content = $this->html->find('div[id=article]',0);
        $public_time = null;
        if(!empty($content)){
            $articleInfo = $content->find('div[id=articleInfo]',0);
            preg_match_all('/([0-9]|-)+/',$articleInfo,$data);
            $public_time = $data[0][0];
        }
        $sql = "INSERT INTO school_spider VALUES (?,?,?)";
        $val = array($this->url,$public_time,$content);
        $this->mysql->operate_by_sql($sql,$val);
    }

    public function get_what($url){
        //页面
        if(preg_match('/http:\/\/202.118.201.228\/homepage\/infoSingleArticle.*articleId=[0-9]?/i',$url)){
            return 1;
        }
        //下一页
        if(preg_match('/http:\/\/202.118.201.228\/homepage\/infoArticleList.*columnId=[0-9]?/i',$url))
            return 2;
        //除掉
        return 0;
    }

}
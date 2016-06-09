<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
        $search_contents = I('get.search_contents');
        if(!empty($search_contents)){
            require '/usr/local/xunsearch/sdk/php/lib/XS.php';
            $xs = new XS('school_news');
            $search = $xs->search;
            $docs = $search->setFuzzy()->setQuery($search_contents)->setLimit(50)->search();
            $res = array();
            foreach ($docs as $doc)
            {
                $temp = array();
                $temp['tittle'] = $search->highlight($doc->tittle); // 高亮处理 tittle 字段
                $temp['contents'] = $search->highlight($doc->contents); // 高亮处理 contents 字段
                $temp['url'] = $doc->url;
                $temp['public_time'] = substr($doc->public_time,0,4).'年'.substr($doc->public_time,4,2).'月'.substr($doc->public_time,6,2).'日';
                $res[] = $temp;
            }
            $this->assign('res',$res);

            /*$school_sql = M();
            $sql = "select * from school_spider where contents LIKE '%$search_contents%'";
            $res = $school_sql->query($sql);
            $this->assign('res',$res);*/
        }
	    $this->display();
    }
}
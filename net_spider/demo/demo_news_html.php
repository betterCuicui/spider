<?php
header("Content-Type: text/html; charset=utf-8");

include_once '../Models/simple_html_dom/simple_html_dom.php';
$ch = curl_init();
$url1 = 'http://202.118.201.228/homepage/infoSingleArticle.do?articleId=2279&columnId=354';
$url2 = 'http://202.118.201.228/homepage/infoSingleArticle.do;jsessionid=932BE8F0900C032CDD2F83F1B56A85AF.TH?articleId=2275';
curl_setopt($ch,CURLOPT_URL,$url1);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");
$output = curl_exec($ch);

echo curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);

curl_close($ch);
$html = new simple_html_dom();
$html->load($output);
$a = $html->find('a');
foreach($a as $u){
    echo $u->href."<br/>";
}
return;
$main = $html->find('div[id=article]',0);
echo $main;
return;
$tittle = $main->find('h2',0);
$articleInfo = $main->find('div[id=articleInfo]',0);
$content = $main->find('div.body',0);
preg_match_all('/([0-9]|-)+/',$articleInfo,$data);
echo "public_time:".$data[0][0]."<br/>";
echo "view_times:".$data[0][1]."<br/>";
echo "tittle:".$tittle."<br/>";
echo "content:".$content;
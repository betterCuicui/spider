<?php
require "./Models/CcUrl.php";

//echo preg_match('/infoSingleArticle.do.*columnId=[0-9]?/','infoSingleArticle.do?articleId=2285&columnId=354');
echo "http://202.118.201.228/homepage/infoArticleList.do;?sortColumn=publicationDate&amp;pagingNumberPer=12&amp;columnId=354&amp;sortDirection=-1&amp;pagingPage=2&amp; ";
echo str_replace('&amp','&','http://202.118.201.228/homepage/infoArticleList.do;?sortColumn=publicationDate&amp;pagingNumberPer=12&amp;columnId=354&amp;sortDirection=-1&amp;pagingPage=2&amp; ');
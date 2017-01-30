<?php
require('./lib/init.php');

//查询所有栏目
$sql="select catname,cat_id from cat";
$cats=mGetALL($sql);

//分页代码
$sql="select count(*)from art where 1".$where;//获取总文章数
$num=mGetOne($sql);//总的文章数
//getPage()
$curr=isset($_GET['page'])? $_GET['page']:1;//当前页码数
$cnt=5;//每页显示条数
$page=getPage($num,$curr,$cnt);

//判断地址栏是否有cat_id
if (isset($_GET['cat_id'])) {
	$where="and art.cat_id=$_GET['cat_id']";
}else{
	$where='';
}

//查询所有文章
$sql="select art_id,title,content,pubtime,comm,catname,thumb from art inner 
join cat on art.cat_id=cat.cat_id where 1".$where.'order by art_id desc limit'.($curr-1)*$cnt.','.$cnt;
$arts=mGetALL($sql);

require('./view/front/index.html');

?>
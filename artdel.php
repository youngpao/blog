<?php
require('./lib/init.php');

$art_id=$_GET['art_id'];

//地址栏传来的art_id 是否合法
if (!is_numeric($art_id)) {
	error('文章id不合法');
}

//是否有这篇文章
$sql="select *from art where art_id=$art_id";
if(!mGetRow($sql)){
	error('文章不存在');
}

//删除文章

$sql="delete from art where art_id=$art_id";
if (! mQuery($sql)) {
	error('文章删除失败');
}else{
	header('Location:artlist.php'); //删除成功后返回到栏目列表
}
?>
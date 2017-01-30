<?php
require('./lib/init.php');
$comment_id=$_GET['comment_id'];
//获取当前评论的art_id
$sql="select art_id from comment where comment_id=".$comment_id;
$art_id=mGetOne($sql);

//删除评论表这条评论
$sql="delete from comment where comment_id=".$comment_id;
$rs=mQuery($sql);
//如果获取art_id成功 更改art表的comm评论数
if ($art_id) {
	$sql='update art set comm=comm-1 where art_id='$art_id;
	mQuery($sql);
}

//跳转到上一页commlist.php
$ref=$_SERVER['HTTP_REFERER'];
header("Location: $ref");

?>
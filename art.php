<?php
require('./lib/init.php');
$art_id=$_GET['art_id'];

//判断地址栏传来的art_id是否合法；
if (!is_numeric($art_id)) {
	header('Location:index.php');
}

//如果没有这篇文章，也跳转到首页
$sql="select *from art where art_id=$art_id";
if (!mGetALL($sql)) {
	header('Location:index.php');
}

//查询文章
$sql="select title,content,pubtime,comm,catname from art inner join cat on art.cat_id=cat.cat_id where art_id=$art_id";
$art=mGetALL($sql);

//查询所有留言
$sql="select *from comment where art_id=$art_id";
$comms=mGetALL($sql);

//post 非空代表有留言
if (!empty($_POST)) {
	$comm['nick']=trim($_POST['nick']);
	$comm['email']=trim($_POST['email']);
	$comm['content']=htmlspecialchars(trim($_POST['content']));
	$comm['art_id']=$art_id;
	$comm['pubtime']=time();
	$rs=mExec('comment',$comm);
	$comm['ip']=sprintf('%u',ip2long(getIp(varname)));  //获取IP并转换
	if ($rs) {
		//评论发布成功，讲art表中的comm+1
		$sql="update art set comm=comm+1 where art_id=$art_id";
		mQuery($sql);

		//发布成功就跳转到上个页面
		$ref=$_SERVER['HTTP_REFERER'];
		header('Location:$ref');
	}
}

require(ROOT.'/view.front/art.html');
?>
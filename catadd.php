<?php
require('./lib/init.php'); //调用mysql封装函数
if (empty($_POST)) {
	include(ROOT.'/view/admin/catadd.html');  //如果POST为空就引入当前模板
}else{
	//连接数据库
	/*$conn = mysql_connect('localhost:8081','root','');
	mysql_query('use blog',$conn);
	mysql_query('set names utf8');*/
	//检测栏目名是否为空
	$cat['catname']=trim($_POST['catname']);
	if (empty($cat['catname'])) {
		error('栏目名不能为空') ;
		exit();
	}
	//检测栏目名是否已存在
	$sql = "select count(*) from cat where catname='$cat['catname']'";
	$rs = mQuery($sql);
	if (mysql_fetch_row($rs)[0]!=0) {
		error('栏目名已存在') ;
		exit();
	}
	//将栏目写入栏目表
	if (!mExec('cat',$cat)) {
		error('栏目插入失败') ;
	}else{
		succ('栏目插入成功') ;
	}
}
 ?>
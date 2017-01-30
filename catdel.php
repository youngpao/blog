<meta charset="utf-8">
<?php
$cat_id=$_GET['cat_id'];

//连接数据库
$conn = mysql_connect('localhost:8081','root','');
mysql_query('use blog',$conn);
mysql_query('set names utf8');

//检测栏目id是否为数字
if (!is_numeric($cat_id)) {
	echo '栏目不合法';
	exit();
}

//检测栏目是否存在
$sql="select count(*)from cat where cat_id=$cat_id";
$rs=mysql_query($sql);
if (mysql_fetch_row($rs)[0] == 0) {
	echo '栏目名不存在';
	exit();
}

//检测栏目下是否有文章
$sql="select count(*) from art where cat_id=$cat_id";
$rs=mysql_query($sql);
if (mysql_fetch_row($rs)[0] != 0) {
	echo '栏目下有文章不能删除';
	exit();
}

//检测完毕删除栏目
$sql="delete from cat where cat_id=$cat_id";
if(!mysql_query($sql)){
	echo '栏目删除失败';
}else{
	echo '栏目删除成功';
}
require('./view/admin/catlist.html');

 ?>
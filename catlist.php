<meta charset="utf-8">
<?php 
//连接数据库
$conn = mysql_connect('localhost:8081','root','');
mysql_query('use blog',$conn);
mysql_query('set names utf8');

$sql = "select *from cat";
$rs=mysql_query($sql);
$cat=array();
while ($row = mysql_fetch_assoc($rs)) {
	$cat[]=$row;
}
require('./view/admin/catlist.html');
?>
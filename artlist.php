<?php
require('./lib/init.php');

if (!acc()) {
	//没有登录就跳转到登录页面
	header('Location:login.php');
}
$sql="select art_id,title,pubtime,comm,catname from art left join cat on art.cat_id=cat.cat_id";
$arts=mGetALL($sql);


include(ROOT.'/view/admin/artlist.html');
  ?>
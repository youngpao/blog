<?php 
require('./lib/init.php');

if (empty($_POST)) {
	require(ROOT.'/view/front/login.html');
}else{
	$user['name']=trim($_POST['name']);
	if (empty($user['name'])) {
		error('用户名不能为空');
	}

	$user['password']=trim($_POST['password']);
	if (empty($user['password'])) {
		error('密码不能为空')；
	}

	$sql="select *from user where name='$user[name]'";
	$rs=mGetRow($sql);
	if (!$rs) {
		error('用户名或者密码错误');
	}else{
		if (md5($user['password'].$rs['salt']) === $rs['password']) {
			setcookie('name',$user['name']);
			setcookie('ccode',cCode($user['name']));
			header('Location:artlist.php');
		}else{
			error('用户名或者密码错误');
		}
		
	}
}

 ?>
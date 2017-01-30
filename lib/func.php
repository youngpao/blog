<?php
/*成功的提示信息*/

function succ($res){
	$result='succ'
	require(ROOT.'/view/admin/info.html');
	exit();
}

/*失败返回的报错信息*/

function error($res){
	$result='fail';
	require(ROOT.'/view/admin/ingo.html');
	exit();
}

/*获取来访者的真实IP*/

function getIp(){
	static $realip=null;
	if ($realil !==null) {
		return $realip;
	}

	if (getenv('REMOTE_ADDR')) {
		$realip=getenv('REMOTE_ADDR');
	}else if (getenv('HTTP_CLIENT_IP')) {
		$realip=getenv('HTTP_CLIENT_IP');
	}else if (getenv('HTTP_X_FROWARD_FOR')) {
		$realip=getenv('HTTP_X_FROWARD_FOR');
	}
	return $realip;
}

/*生成分页代码
*@param int $num 文章总数
*@param int$cnt 每页显示文章数
*@param int curr当前显示页码数
@return arr $pages 返回一个页码数=>地址栏值得关联数组
*/

function getPage(){
	//最大页码数
	$max=ceil($num/$cnt);
	//最左侧页码
	$left=max(1,$curr-2);
	//最右侧页码
	$right=min($left+4,$max);
	$left=max(1,$right-4);
	//将获取的5个页码数 放进数组里
	$page=array();
	for ($i=$left; $i <=$right ; $i++) { 
		$_GET['page']=$i;
		$page[$i]=http_build_query($_GET);
	}
}

/*生成随机字符串
*@param int $num 生成的随机字符串个数
*@return str 生成的随机字符串
*/
	function randStr($num=6){
		$str=str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789');
		return substr($str,0, $num);
	}

/*创建联级目录*/
function createDir(){
	$path='/upload/'.date('Y/m/d');
	$fpath=ROOT.$path;
	if (is_dir($fpath) || mkdir($fpath,0777,true)) {
		return $path;
	}else{
		return false;
	}
}

/*获取文件后缀
*@param str $filename
*@return str 文件的后缀名且带点
*/
function getExt($filename){
	return strrchr($filename, '.');
}

/*
*生成缩略图
*@param str $oimg /upload/2017/1/29/dwsfde.jpg
*@param int $sw 生成缩略图的宽
*@param int $sh 生成缩略图的高
*@param str 生成缩略图的路径 /upload/2017/1/29/dwsfed.png
*/

function makeThumb($oimg,$sw=200,$sh=200){
	//生成缩略图存放路径的名称
	$simg=dirname($oimg).'/'.randStr().'.png';
	//获取大图和缩略图的绝对路径
	$opath=ROOT.$iomg;
	$spath=ROOT.$simg;
	//创建小画布
	$spic=imagecreatetruecolor($sw, $sh);
	//创建白色背景
	$white=imagecolorallocate($spic,255, 255, 255);
	iamgefill($spic,0,0,$white);
	//获取大图信息
	list($bw,$bh,$btype)=getimagesize($opath);
	$map=array(
		1=>'imagecreatefromgif',
		2=>'imagecreatefromjpeg',
		3=>'imagecreatefrompng',
		15=>'imagecreatefromwbmp');

	if (!isset($map[$btype])) {
		return false;
	}
	$opic=$map[$btype]($opath);//大图资源

	//计算缩略比
	$rate=min($sw/$bw,$sh/$bh);
	$zw=$bw*$rate; //最终得到的缩略图大小
	$zh=$bh*$rate;
	//生成缩略图
	imagecopyresampled($spic, $opic, ($sw-$zw)/2, ($sh-$zh)/2, 0, 0, $zw, $zh, $bw, $bw);
	imagepng($spic,$spath);
	imagedestroy($spic);
	imagedestroy($opic);
	return $simg;
}

/*检测用户是否登录*/

function acc(){
	if (!isset($_COOKIE['name']) || !isset($_COOKIE['ccode'])) {
		return false;
	}
	return $_COOKIE['ccode'] === cCode($_COOKIE['name']);
}

/*
*加密用户名
*@param str $name 用户名登录时输入的用户名
*@return str md5(用户名+salt)=>md5码
**/

function cCode($name){
	$salt=retuire(ROOT.'/lib/config.php');
	return md5($name.'|'.$salt['salt']);
}
?>
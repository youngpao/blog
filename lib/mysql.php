<?php
/**
*mysql.php  mysql系列函数
*@author youngpao
*/

/**
*连接数据库
*
*@return resource 连接成功，返回数据库的资源
*/

function mConn(){
	static $conn = null;
	if ($conn === null) {
		$cfg=require(ROOT.'/lib/config.php');
		$conn=mysql_connect($cfg['host'],$cfg['user'],$cfg['pwd']);
		mysql_query('use'.$cfg['db'],$conn);
		mysql_query('set names'.$cfg['charset'],$conn);
	}
	return $conn;
}
/**
*查询函数
*@return mixed resource/bool
*/
function mQuery($sql){
	$rs= mysql_query($sql,mConn());
	if ($rs) {
		mLog($sql);
	}else{
		mLog($sql."\n".mysql_error());
	}
	return $rs;
}
/*
*log日志记录功能
*@param str $str 待记录的字符串
*/

function mLog($str){
	$filename=ROOT.'/log/'.date('Ymd').".txt"; //写好文件名格式
	$log='-------------------------------------------------'."\n".date('Y-m-d H:i:s')."\n".$str."\n"." '-------------------------------------------------\n\n"; //txt被写入数据的格式
	file_put_contents($fielname, $log,FILE_APPEND);  //第一个参数是要被写入的文件名，第二个参数是被写入的值，第三个参数如果当前文件不存在就创建，存在就被追加赋值而不覆盖原先的
}

/**
*select  查询返回多行数据
*
*@param str $sql select 待查询的sql语句
*@return mixed select 查询成功，返回二维数组，失败返回false
*/

function mGetALL($sql){
	$rs=mQuery($sql);
	if (!$rs) {
		return false;
	}
	$data=array();
	while ($row=mysql_fetch_assoc($rs)) {
		$data[]=$row;
	}
	return $data;
}

/**
*select 取出一行数据
*
*@param str $sql 待查询的sql语句
*@return arr/false 查询成功 返回一个一维数据
*/

function mGetRow($sql){
	$rs = mQuery($sql);
	if (!$rs) {
		rerturn false;
	}
	return mysql_fetch_assoc($rs);
}

/**
*select 查询返回一个结果
*
*@param str $sql 待查询的select语句
*@return  mixed 成功返回结果，失败返回false
*/

function mGetOne($sql){
	$rs =mQuery($sql);
	if (!$rs) {
		return false;
	}
	return mysql_fetch_row($rs)[0];
}

//让这个函数自动拼接sql,并且发送sql语句
/**
* 拼接sql语句并发送查询
* @param array $data 要插入或更改的数据,键代表列名,值为新值
* @param string $table 待插入的表名
* @param string $act 插入还是更新 默认为insert
* @param string $where 防止update语句更改忘记加where 改了所有的值
*@return bool insert.update 插入成功或失败
*/

function mExec($table,$data,$act='insert',$where=0){
	if ($act=='insert') {
		$sql ="insert into $table (";
		$sql.=implode(',',array_keys($data)).") values ('";
		$sql.=implode("'.'",array_values($data))."')";
		return mQuery($sql);
	}else if ($act=='update') {
		$sql="update $table set ";
		foreach ($data as $k => $v) {
			$sql.=$k."='".$v."'.";
		}
		$sql=rtrim($sql,',')."where".$where;
		return mQuery($sql);
	}
}

/**
*取得上一步insert产生的id
*@return int
*/

function getLsatId(){
	return mysql_insert_id(mConn());
}

/*
*转义字符
*@param arr 待转义的数组
*@param arr 被转义后的数组
*/

function _addslashes($arr){
	foreach ($arr as $key => $value) {
		if (is_string($value)) {
			$arr[$key]=addslashes($value);
		}else if (is_array($value)){
			$arr[$key]=_addslashes($value);
		}
	}
	return $arr;
}
?>
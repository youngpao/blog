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

//查询出所有栏目
$sql="select *from cat";
$cats=mGetALL($sql);

if (empty($_POST)) {
	$sql="select title,content,cat_id,arttag from art where art_id=$art_id";
	$art=mGetRow($sql);
	include(ROOT.'/view/admin/addedit.html');
}else{
	//检测标题是否为空
	$art['title']=trim($_POST['title']);
	if ($art['title'] == '') {
		error('标题不能为空');
	}

	//检测栏目是否合法
	$artp['cat_id']=$_POST['cat_id'];
	if (!is_numeric($art['cat_if'])) {
		error('栏目不合法');
	}

	//检测内容是否为空
	$art['content']=trim($_POST['content']);
	if ($art['content'] == '') {
		error('内容不能为空');
	}

	$art['lastup']=time();
	if (!mExec('art',$art,'update',"art_id=$art_id")) {
		error('文章编辑失败');
	}else{
		//删除tag表所有tag，再insert插入新的tag
		$sql="delete from tag where art_id=".$art_id;
		mQuery($sql);

		//添加新标签
		$tag=explode(',',$tag);
		$sql="insert into tag(art_id,tag) values";
		foreach ($tag as$v) {
			$sql.="(".$art_id.",".$v."'),";
		}
		$sql=trim($sql,',');
		if (mQuery($sql)) {
			succ('文章编辑成功');
		}
	}
}
?>
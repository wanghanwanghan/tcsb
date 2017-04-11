<?php
require("config/config.php");

require ($cfg_inc_common_path);

if($_POST['add4'])
{
	$topClass=trimHtml($_POST['cid3']);
	$title=trimHtml($_POST['title']);
	$orderid=trimHtml($_POST['orderid']);
	if(!$topClass)
	{
		mysql_close();
		echo "<script>alert('三级分类,必须选择！');</script>";
		exit;
	}
	//二级分类
	$sql="select Tid,fstr from db_office where Tid='$topClass' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error548".mysql_error());
	list($aTid3,$fstr5)=mysql_fetch_row($sqlBuffer);
	
	
	//判断是否重复
	$sql="select Tid from db_office where name='$title' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error548".mysql_error());
	list($aTid)=mysql_fetch_row($sqlBuffer);
	if($aTid)
	{
		mysql_close();
		echo "<script>alert('$title"."-->该分类已经存在，请重新填写！');</script>";
		exit;
	}
	$fstr=$fstr5.$topClass.",";
	$insertSql="insert into db_office(name,ftid,fstr,level,orid)values('$title','$topClass','$fstr',4,'$orderid')";
	mysql_query($insertSql)or die("error548".mysql_error());
	
	mysql_close();
	echo "<script>alert('四级分类添加成功！');parent.location='$web_path/add_sq.php'</script>";
	exit;
}
if($_POST['ajaxbtn']=='btn3')
{
	$username=$_POST['username'];
	$type=$_POST['type'];
	//求二级分类
	$optionBuffer="";
	$class2Sql="select Tid,name from db_office where ftid='$username' and status>0";
	$class2SqlBuffer=mysql_query($class2Sql)or die("error548");
	while(list($Tid2,$name2)=mysql_fetch_row($class2SqlBuffer))
	{
		$optionBuffer.="<option value='$Tid2'>$name2"."</span>";
	}
	if($type==2)
	{
		$allBuffer="<select name='cid2' onchange=\"selectClass(this.value,3,'c3id')\"><option value='0'>请选择二级分类</option>".$optionBuffer."</select>&nbsp;&nbsp;&nbsp;&nbsp;<span id='c3id'></span>";
	}
	else
	{
		if($type==3)
		{
			$allBuffer="<select name='cid3'><option value='0'>请选择三级分类</option>".$optionBuffer."</select>&nbsp;&nbsp;";
		}
		else
		{
			$allBuffer="<select name='cid2'><option value='0'>请选择二级分类</option>".$optionBuffer."</select>&nbsp;&nbsp;";
		}
	}
	echo $allBuffer;
	exit;
	
}
if($_POST['add3'])
{
	$topClass=trimHtml($_POST['cid2']);
	$title=trimHtml($_POST['title']);
	$orderid=trimHtml($_POST['orderid']);
	if(!$topClass)
	{
		mysql_close();
		echo "<script>alert('二级分类,必须选择！');</script>";
		exit;
	}
	//二级分类
	$sql="select Tid,fstr from db_office where Tid='$topClass' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error548".mysql_error());
	list($aTid3,$fstr5)=mysql_fetch_row($sqlBuffer);
	
	
	//判断是否重复
	$sql="select Tid from db_office where name='$title' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error548".mysql_error());
	list($aTid)=mysql_fetch_row($sqlBuffer);
	if($aTid)
	{
		mysql_close();
		echo "<script>alert('$title"."-->该分类已经存在，请重新填写！');</script>";
		exit;
	}
	$fstr=$fstr5.$topClass.",";
	$insertSql="insert into db_office(name,ftid,fstr,level,orid)values('$title','$topClass','$fstr',3,'$orderid')";
	mysql_query($insertSql)or die("error548".mysql_error());
	
	mysql_close();
	echo "<script>alert('三级级分类添加成功！');parent.location='$web_path/add_sq.php'</script>";
	exit;
}
if($_POST['add2'])
{
	$topClass=trimHtml($_POST['topClass']);
	$title=trimHtml($_POST['title']);
	$orderid=trimHtml($_POST['orderid']);
	$topClass=intval($topClass);

	//判断是否重复
	$sql="select Tid from db_office where name='$title' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error548".mysql_error());
	list($aTid)=mysql_fetch_row($sqlBuffer);
	if($aTid)
	{
		mysql_close();
		echo "<script>alert('$title"."-->该分类已经存在，请重新填写！');</script>";
		exit;
	}
	$fstr=",".$topClass.",";
	$insertSql="insert into db_office(name,ftid,fstr,level,orid)values('$title','$topClass','$fstr',2,'$orderid')";
	mysql_query($insertSql)or die("error548".mysql_error());
	
	mysql_close();
	echo "<script>alert('二级分类添加成功！');parent.location='$web_path/add_sq.php'</script>";
	exit;
}
if($_POST['add'])
{
	$title=trimHtml($_POST['title']);
	$orderid=trimHtml($_POST['orderid']);
	if(!$title)
	{
		mysql_close();
		$inc_title="社区分类添加失败--->\"$title\"名称不能为空！";
		$inc_urlPath=$web_path."/add_sq.php";
		include($cfg_php_result_path);
		echo $resultBuffer;
		exit;
	}
	//判断是否重复
	$sql="select Tid from db_office where name='$title' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error548".mysql_error());
	list($aTid)=mysql_fetch_row($sqlBuffer);
	if($aTid)
	{
		mysql_close();
		$inc_title="社区分类添加失败--->\"$title\"该名称已经存在！";
		$inc_urlPath=$web_path."/add_sq.php";
		include($cfg_php_result_path);
		echo $resultBuffer;
		exit;
	}
	$insertSql="insert into db_office(name,ftid,fstr,level,orid)values('$title','0',',0,',1,'$orderid')";
	mysql_query($insertSql)or die("error548".mysql_error());
	mysql_close();
	$inc_title="顶级社区分类添加成功！";
	$inc_urlPath=$web_path."/add_sq.php";
	include($cfg_php_result_path);
	
	echo $resultBuffer;
	exit;
}
$buffer=implode("",file($template_add_sq_path));
//等级客户
$optionBuffer="";
$selectSql="select Tid,name from db_office where ftid=0 and status>0";
$selectSqlBuffer=mysql_query($selectSql)or die("error654");
while(list($office_Tid,$office_name)=mysql_fetch_row($selectSqlBuffer))
{
	$optionBuffer.="<option value='$office_Tid'>$office_name</option>";
}
$buffer=str_replace("{topClass}",$optionBuffer,$buffer);


$buffer=str_replace("{postName}",$_SERVER['PHP_SELF'],$buffer);
include($cfg_inc_replace_path);
echo $buffer;
?>
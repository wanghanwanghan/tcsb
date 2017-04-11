<?php
require("config/config.php");
require ($cfg_inc_common_path);

if($_POST['add2'])
{
	$topClass=trimHtml($_POST['topClass']);
	$title=trimHtml($_POST['title']);
	$orderid=trimHtml($_POST['orderid']);
	$topClass=intval($topClass);
	if(!$title)
	{
		mysql_close();
		echo "<script>alert('名称必须填写');</script>";
		exit;
	}
	//判断是否重复
	$sql="select Tid from db_office where name='$title' AND status>0";
	$sqlBuffer=mysql_query($sql)or die("error548".mysql_error());
	list($aTid)=mysql_fetch_row($sqlBuffer);
	if($aTid)
	{
		mysql_close();
		echo "<script>alert('$title"."-->该分类已经存在，请重新填写！');</script>";
		exit;
	}
	if($topClass==0)
	{
		$insertSql="insert into db_office(name,ftid,fstr,level,orid)values('$title','0',',0,',1,'$orderid')";
	mysql_query($insertSql)or die("error548".mysql_error());
	}
	else
	{
		$level=0;
		$selectSql="select Tid,name,ftid,fstr,level,orid from db_office where Tid='$topClass' AND status>0";
		$selectSqlBuffer=mysql_query($selectSql)or die("error548");
		list($f_Tid,$f_name,$f_ftid,$f_fstr,$f_level,$f_orid)=mysql_fetch_row($selectSqlBuffer);
		$level=$f_level+1;
		if($f_fstr)
		{
			$fstr=$f_fstr.$topClass.",";
		}
		else
		{
			$fstr=",".$topClass.",";
		}
		$insertSql="insert into db_office(name,ftid,fstr,level,orid)values('$title','$topClass','$fstr',$level,'$orderid')";
		mysql_query($insertSql)or die("error548".mysql_error());
	}
	mysql_close();
	echo "<script>alert('社区添加成功！');parent.location='$web_path/sq.php'</script>";
	exit;
}
if($_POST['delbtn'])
{
	$id=$_POST['id'];
	$idCount=count($id);
	for($t=0;$t<$idCount;$t++)
	{
		$tempTid=$id[$t];
		$cSql="select name from db_office where Tid='$tempTid' and status>0";
		$cSqlBuffer=mysql_query($cSql)or die("error548".mysql_error());
		list($cname2True)=mysql_fetch_row($cSqlBuffer);
		
		
		$cSql="select Tid from db_office where ftid='$tempTid' and status>0";
		$cSqlBuffer=mysql_query($cSql)or die("error548".mysql_error());
		list($cname2Tid)=mysql_fetch_row($cSqlBuffer);
		if($cname2Tid)
		{
			mysql_close();
			echo "<script>alert('$cname2True -->删除失败，请先删除该分类的子类!');location='sq.php';</script>";
			exit;
		}
		//开始删除
		$delSql="UPDATE db_office SET status=-1 where Tid='$tempTid'";
		mysql_query($delSql)or die("error548");
	}
	mysql_close();
	echo "<script>alert('删除成功!');location='sq.php';</script>";
	exit;
	
}
if($_POST['btn'])
{
	$updateid=$_POST['updateid'];
	$cnamearr=$_POST['cnamearr'];
	$orderid=$_POST['orderid'];
	$updateidCount=count($updateid);
	for($i=0;$i<$updateidCount;$i++)
	{
		$tUid=$updateid[$i];
		$tempcname=$cnamearr[$i];
		$temporderid=$orderid[$i];
		$sql3="update db_office set name='$tempcname',orid='$temporderid' where Tid='$tUid'";
		mysql_query($sql3)or die("error548");
	}
	mysql_close();
	echo "<script>alert('更新成功！');location='sq.php';</script>";
	exit;	
}
$buffer=implode("",file($template_man_sq_path));
$sql="select Tid,name,ftid,fstr,level,orid from db_office where status>0";
//搜索
if($_REQUEST["submit"])
{
		$GetUrl=$_SERVER["PHP_SELF"]."?submit=ok&";
		if($_REQUEST["keyword"])
		{
			$keyword=$_REQUEST["keyword"];
			$sql.=" and name like '%$keyword%'";
			$GetUrl.="keyword=$keyword&";
		}
		
		if($_REQUEST["ftidName"])
		{
			$ftidName=$_REQUEST["ftidName"];
			$sql.=" and ftid='$ftidName'";
			$GetUrl.="ftidName=$ftidName&";
		}
}
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$totleSta=mysql_num_rows($sqlBuffer);
pageft($totleSta,$admin_page_config,$GetUrl);
$sql.=" order by Tid desc";
$sql.=" limit $firstcount,$displaypg";
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$whilebuffer=Template("$template_man_sq_path","<!--begin-->","<!--end-->");
$totleBuffer="";
while(list($Tid,$name,$ftid,$fstr,$level,$orid)=mysql_fetch_row($sqlBuffer))
{
	$level=intval($level);
	$levelStr=$level_arr[$level];
	$temp=$whilebuffer;
	$temp=str_replace("{cname}",$name,$temp);
	
	$cSql="select name from db_office where Tid='$ftid' and status>0";
	$cSqlBuffer=mysql_query($cSql)or die("error548".mysql_error());
	list($cname2)=mysql_fetch_row($cSqlBuffer);
	if(!$cname2)
	{
		$cname2="顶级";
	}
	
	$temp=str_replace("{class}",$cname2,$temp);	
	$temp=str_replace("{level}",$levelStr,$temp);
	$temp=str_replace("{sorder}",$orid,$temp);
	$temp=str_replace("{Tid}",$Tid,$temp);
	$totleBuffer.=$temp;
}
$buffer=str_replace($whilebuffer,$totleBuffer,$buffer);

//===========添加部分=============
$ftidNameStr=getclassStr('0');
$buffer=str_replace("{topClass}",$ftidNameStr,$buffer);
//===========添加部分结束==========

$ftidNameStr=getclassStr('0');
$buffer=str_replace("{ftidNameStr}",$ftidNameStr,$buffer);


$buffer=str_replace("{pageStr}",$pagenav,$buffer);
$buffer=str_replace("{postName}",$_SERVER['PHP_SELF'],$buffer);
include($cfg_inc_replace_path);
echo $buffer;
?>
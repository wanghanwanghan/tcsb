<?php
/*
 * 暂时无用
require("config/config.php");

require ($cfg_inc_common_path);

if($_POST['add'])
{
	$name=trimHtml($_POST['name']);
	$mobi=trimHtml($_POST['mobi']);
	$sjg=trimHtml($_POST['sjg']);
	$jz_type=trimHtml($_POST['jz_type']);
	$jg_grade=trimHtml($_POST['jg_grade']);
	$bjz_date=trimHtml($_POST['bjz_date']);
	$ejz_date=trimHtml($_POST['ejz_date']);
	$up_bdate=trimHtml($_POST['up_bdate']);
	$up_edate=trimHtml($_POST['up_edate']);
	$dowm_bdate=trimHtml($_POST['dowm_bdate']);
	$dowm_edate=trimHtml($_POST['dowm_edate']);
	//添加
	if(!$name)
	{
		echo "<script>alert('姓名必须填写！');</script>";
		exit;
	}
	$mobiLengh=strlen($mobi);
	if(!$mobi)
	{
		echo "<script>alert('手机必须填写！');</script>";
		exit;
	}
	if($mobiLengh<11)
	{
		echo "<script>alert('手机格式错误！');</script>";
		exit;
	}
	if(!$sjg)
	{
		echo "<script>alert('所属机关必须选择！');</script>";
		exit;
	}
	if($jz_type=="999")
	{
		echo "<script>alert('矫正状态必须选择！');</script>";
		exit;
	}
	
	if($jg_grade=="999")
	{
		echo "<script>alert('监管级别必须选择！');</script>";
		exit;
	}
	
	if(!$bjz_date)
	{
		echo "<script>alert('矫正开始日期必须填写！');</script>";
		exit;
	}
	if(!$ejz_date)
	{
		echo "<script>alert('矫正结束日期必须填写！');</script>";
		exit;
	}
	
	if($up_bdate=="999" or $up_edate=="999" or $dowm_bdate=="999" or $dowm_edate=="999")
	{
		echo "<script>alert('监管时间段必须填写！');</script>";
		exit;
	}
	//判断手机号码是否重复
	$accountSql="select Tid from db_ivr where mobi='$mobi' and regstate<2 ans status>0";
	$accountSqlBuffer=mysql_query($accountSql)or die("error776".mysql_error());
	list($accountTid)=mysql_fetch_row($accountSqlBuffer);
	if($accountTid)
	{
		echo "<script>alert('该手机号码已经存在!');</script>";
		exit;
	}
	//开始添加
	$sql="insert into db_ivr(oid,name,mobi,bjz_date,ejz_date,jz_type,jg_grade,up_bdate,up_edate,dowm_bdate,dowm_edate,regstate,flag,ydate)values('$sjg','$name','$mobi','$bjz_date','$ejz_date','$jz_type','$jg_grade','$up_bdate','$up_edate','$dowm_bdate','$dowm_edate','0',1,now())";
	mysql_query($sql)or die("error");
	echo "<script>alert('添加成功!');parent.location='$web_path/add_xinxi.php'</script>";
	exit;	
}
$buffer=implode("",file($template_add_xinxi_path));
$buffer=str_replace("{postName}",$_SERVER['PHP_SELF'],$buffer);

$ftidNameStr=getclassStr('0');
$buffer=str_replace("{sjgDStr}",$ftidNameStr,$buffer);
//矫正状态
$optionBuffer=optionStr($jz_type_row,"-1","");
$buffer=str_replace("{jz_typeStr}",$optionBuffer,$buffer);
//监管级别
$optionBuffer=optionStr($jg_grade_row,"-1","");
$buffer=str_replace("{jg_gradeStr}",$optionBuffer,$buffer);

//24小时
$hstr="";
for($i=0;$i<=12;$i++)
{
	$iStr=$i."点";
	$hstr.="<option value='$i'>".$iStr."</option>";
	
}
$buffer=str_replace("{up_bdateStr}",$hstr,$buffer);
$buffer=str_replace("{up_up_edate}",$hstr,$buffer);
$hstr="";
for($i=12;$i<=23;$i++)
{
	$iStr=$i."点";
	$hstr.="<option value='$i'>".$iStr."</option>";
	
}

$buffer=str_replace("{dowm_bdateStr}",$hstr,$buffer);
$buffer=str_replace("{dowm_edateStr}",$hstr,$buffer);
include($cfg_inc_replace_path);
echo $buffer;
*/
?>
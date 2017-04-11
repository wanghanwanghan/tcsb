<?php
require("config/config.php");

require ($cfg_inc_common_path);


if($_POST['url'])
{
	$url=$_POST['url'];
}
else
{
	$url=$_SERVER['HTTP_REFERER'];
}
$Tid=$_GET['Tid'];
$Tid=intval($Tid);
if($_POST['edit'])
{
	$name=trimHtml($_POST['name']);
	$mobi=trimHtml($_POST['mobi']);
	$id_card=trimHtml($_POST['id_card']);
	$id_shebao=trimHtml($_POST['id_shebao']);
	$sjg=trimHtml($_POST['sjg']);
	// $jz_type=trimHtml($_POST['jz_type']);
	$jg_grade=intval(trimHtml($_POST['jg_grade']));
	// $bjz_date=trimHtml($_POST['bjz_date']);
	// $ejz_date=trimHtml($_POST['ejz_date']);
	// $up_bdate=trimHtml($_POST['up_bdate']);
	// $up_edate=trimHtml($_POST['up_edate']);
	// $dowm_bdate=trimHtml($_POST['dowm_bdate']);
	// $dowm_edate=trimHtml($_POST['dowm_edate']);
	$regstate=trimHtml($_POST['regstate']);
	//添加
	if(!$name)
	{
		echo "<script>alert('姓名必须填写！');</script>";
		exit;
	}
	if(!$id_shebao)
	{
		echo "<script>alert('社保编号必须填写！');</script>";
		exit;
	}
	if(!$sjg)
	{
		echo "<script>alert('所属地区必须选择！');</script>";
		exit;
	}
	// if($jz_type=="999")
	// {
		// echo "<script>alert('矫正状态必须选择！');</script>";
		// exit;
	// }
	
	if($jg_grade=="999")
	{
		echo "<script>alert('认证方式必须选择！');</script>";
		exit;
	}
	
	// if(!$bjz_date)
	// {
		// echo "<script>alert('矫正开始日期必须填写！');</script>";
		// exit;
	// }
	// if(!$ejz_date)
	// {
		// echo "<script>alert('矫正结束日期必须填写！');</script>";
		// exit;
	// }
	
	// if($up_bdate=="999" or $up_edate=="999" or $dowm_bdate=="999" or $dowm_edate=="999")
	// {
		// echo "<script>alert('监管时间段必须填写！');</script>";
		// exit;
	// }
	//开始更改
	//$udateSql="update db_ivr set oid='$sjg',name='$name',bjz_date='$bjz_date',ejz_date='$ejz_date',jz_type='$jz_type',jg_grade='$jg_grade',up_bdate='$up_bdate',up_edate='$up_edate',dowm_bdate='$dowm_bdate',dowm_edate='$dowm_edate',regstate='$regstate' where Tid='$Tid'";
	$udateSql="update db_ivr set oid='$sjg',name='$name',mobi='$mobi',id_card='$id_card',id_shebao='$id_shebao',bjz_date=NULL,ejz_date=NULL,jz_type='0',jg_grade='$jg_grade',up_bdate='0',up_edate='0',dowm_bdate='0',dowm_edate='0',regstate='$regstate' where Tid='$Tid'";
	mysql_query($udateSql)or die("error");
	echo "<script>alert('修改成功!');parent.location='$web_path/edit_xinxi.php?Tid=$Tid'</script>";
	exit;	
}
$buffer=implode("",file($template_edit_xinxi_path));
$sql="select Tid,oid,name,mobi,id_card,id_shebao,bjz_date,ejz_date,jz_type,jg_grade,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr where Tid='$Tid' and status>0";
$sqlBuffer=mysql_query($sql)or die("error548");
list($Tid,$oid,$name,$mobi,$id_card,$id_shebao,$bjz_date,$ejz_date,$jz_type,$jg_grade,$regstate,$up_bdate,$up_edate,$dowm_bdate,$dowm_edate)=mysql_fetch_row($sqlBuffer);
$buffer=str_replace("{name}",$name,$buffer);
$buffer=str_replace("{mobi}",$mobi,$buffer);
$buffer=str_replace("{id_card}",$id_card,$buffer);
$buffer=str_replace("{id_shebao}",$id_shebao,$buffer);
$ftidNameStr=getclassStr($oid);
$buffer=str_replace("{sjgDStr}",$ftidNameStr,$buffer);

//矫正状态
$jz_type=intval($jz_type);
$optionBuffer=optionStr($jz_type_row,$jz_type,"");
$buffer=str_replace("{jz_typeStr}",$optionBuffer,$buffer);

//监管级别
$optionBuffer=optionStr($jg_grade_row,$jg_grade,"");
$buffer=str_replace("{jg_gradeStr}",$optionBuffer,$buffer);

$buffer=str_replace("{bjz_date}",$bjz_date,$buffer);
$buffer=str_replace("{ejz_date}",$ejz_date,$buffer);

$optionBuffer=optionStr($regstate_grade_row,$regstate,"");
$buffer=str_replace("{regstateStr}",$optionBuffer,$buffer);

//24小时
$hstr="";
for($i=0;$i<=12;$i++)
{
	$iStr=$i."点";
	if($i==$up_bdate)
	{
		$hstr.="<option value='$i' selected=\"selected\" >".$iStr."</option>";
	}
	else
	{
		$hstr.="<option value='$i'>".$iStr."</option>";
	}
	
}
$buffer=str_replace("{up_bdateStr}",$hstr,$buffer);


$hstr="";
for($i=0;$i<=12;$i++)
{
	$iStr=$i."点";
	if($i==$up_edate)
	{
		$hstr.="<option value='$i' selected=\"selected\" >".$iStr."</option>";
	}
	else
	{
		$hstr.="<option value='$i'>".$iStr."</option>";
	}
	
}
$buffer=str_replace("{up_up_edate}",$hstr,$buffer);



$hstr="";
for($i=12;$i<=24;$i++)
{
	$iStr=$i."点";
	if($i==$dowm_bdate)
	{
		$hstr.="<option value='$i' selected=\"selected\" >".$iStr."</option>";
	}
	else
	{
		$hstr.="<option value='$i'>".$iStr."</option>";
	}
	
}
$buffer=str_replace("{dowm_bdateStr}",$hstr,$buffer);




$hstr="";
for($i=12;$i<=24;$i++)
{
	$iStr=$i."点";
	if($i==$dowm_edate)
	{
		$hstr.="<option value='$i' selected=\"selected\" >".$iStr."</option>";
	}
	else
	{
		$hstr.="<option value='$i'>".$iStr."</option>";
	}
	
}
$buffer=str_replace("{dowm_edateStr}",$hstr,$buffer);

$buffer=str_replace("{url}",$url,$buffer);

$buffer=str_replace("{postName}",$_SERVER['PHP_SELF']."?Tid=$Tid",$buffer);

include($cfg_inc_replace_path);
echo $buffer;
?>

<?php
require("config/config.php");
require ($cfg_inc_common_path);

$myAreas = getManagerAreas($sin_uid, $sin_manager);

$buffer=implode("",file($template_man_xinxi_path));
if($_GET['op']=='del')
{
	$Tid=$_GET['Tid'];
	$Tid=intval($Tid);
	//开始更改
	$udateSql="update db_ivr set status=-1 where Tid='$Tid'";
	mysql_query($udateSql)or die("error548");
	echo "<script>alert('删除成功！!');location='$web_path/man_xinxi.php'</script>";
	exit;	
	
}
if($_POST['add2'])
{
	if(!$sjg)
	{
		echo "<script>alert('所属地区必须选择！');</script>";
		exit;
	}
	//
}
if($_POST['add'])
{
	$name=trimHtml($_POST['name']);
	$mobi=trimHtml($_POST['mobi']);
	$sjg=trimHtml($_POST['sjg']);
	$id_card=trimHtml($_POST['id_card']);
	$id_shebao=trimHtml($_POST['id_shebao']);
	// $jz_type=trimHtml($_POST['jz_type']);
	$jg_grade=trimHtml($_POST['jg_grade']);
	// $bjz_date=trimHtml($_POST['bjz_date']);	
	// $ejz_date=trimHtml($_POST['ejz_date']);	
	// $up_bdate=trimHtml($_POST['up_bdate']);
	// $up_edate=trimHtml($_POST['up_edate']);
	// $dowm_bdate=trimHtml($_POST['dowm_bdate']);
	// $dowm_edate=trimHtml($_POST['dowm_edate']);
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
	$id_card = strtoupper($id_card);
	if(!preg_match("/[0-9A-Z]{18}/", $id_card)) {
		echo "<script>alert('身份证格式错误！');</script>";
		exit;
	}
	if($mobiLengh<11)
	{
		echo "<script>alert('手机格式错误！');</script>";
		exit;
	}
	if(!$sjg)
	{
		echo "<script>alert('所属地区必须选择！');</script>";
		exit;
	}
	if(!$id_shebao)
	{
		echo "<script>alert('社保编号必须填写！');</script>";
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
	//判断手机号码是否重复
	$accountSql="select Tid from db_ivr where mobi='$mobi' and status>0";
	$accountSqlBuffer=mysql_query($accountSql)or die("error776".mysql_error());
	list($accountTid)=mysql_fetch_row($accountSqlBuffer);
	if($accountTid)
	{
		echo "<script>alert('该手机号码已经存在!');</script>";
		exit;
	}
	//开始添加
	//$sql="insert into db_ivr(oid,name,mobi,bjz_date,ejz_date,jz_type,jg_grade,up_bdate,up_edate,dowm_bdate,dowm_edate,regstate,flag,ydate)values('$sjg','$name','$mobi','$bjz_date','$ejz_date','$jz_type','$jg_grade','$up_bdate','$up_edate','$dowm_bdate','$dowm_edate','0',1,now())";
	$sql="insert into db_ivr(oid,name,mobi,id_card,id_shebao,bjz_date,ejz_date,jz_type,jg_grade,up_bdate,up_edate,dowm_bdate,dowm_edate,regstate,flag,ydate) " .
		"values('$sjg','$name','$mobi','$id_card','$id_shebao',NULL,NULL,'0','$jg_grade','0','0','0','0','0',1,now())";
	mysql_query($sql)or die("error:" . mysql_error());
	mysql_close();
	echo "<script>alert('添加成功!');parent.location='$web_path/man_xinxi.php'</script>";
	exit;	
}
//$sql="select Tid,oid,name,mobi,bjz_date,ejz_date,jz_type,jg_grade,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr where regstate<2";
$sql="select Tid,oid,name,mobi,id_card,id_shebao,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr where regstate<2 and status>0";
if($myAreas) {
    $sql .= " AND oid IN(".implode(",", $myAreas).")";
} else {
    $sql .= " AND 1=2";
}
//搜索
if($_REQUEST["submit"])
{
		$GetUrl=$_SERVER["PHP_SELF"]."?submit=ok&";
		if($_REQUEST["sname"])
		{
			$sname=$_REQUEST["sname"];
			$sql.=" and name like '%$sname%'";
			$GetUrl.="sname=$sname&";
		}
		
		if($_REQUEST["smobi"])
		{
			$smobi=$_REQUEST["smobi"];
			$sql.=" and mobi like '%$smobi%'";
			$GetUrl.="smobi=$smobi&";
		}
		
		if($_REQUEST["classid"])
		{
			$classid=$_REQUEST["classid"];
			$classStr=$classid;
			$tempCid=",".$classid.",";
			$csql="select Tid from db_office where fstr like '%$tempCid%' and status>0";
			$csqlBuffer=mysql_query($csql)or die("error55");
			while(list($gTid)=mysql_fetch_row($csqlBuffer))
			{
				$classStr.=",".$gTid;
			}
			$sql.=" and oid in ($classStr)";
			$GetUrl.="classid=$classid&";
		}
}
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$totleSta=mysql_num_rows($sqlBuffer);
pageft($totleSta,$admin_page_config,$GetUrl);
$sql.=" order by Tid desc";
$sql.=" limit $firstcount,$displaypg";
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$whilebuffer=Template("$template_man_xinxi_path","<!--begin-->","<!--end-->");
$totleBuffer="";
while(list($Tid,$oid,$name,$mobi,$id_card,$id_shebao,$regstate,$up_bdate,$up_edate,$dowm_bdate,$dowm_edate)=mysql_fetch_row($sqlBuffer))
{
	$temp=$whilebuffer;
	$temp=str_replace("{name}",$name,$temp);
	
	$cSql="select name from db_office where Tid='$oid' and status>0";
	$cSqlBuffer=mysql_query($cSql)or die("error548".mysql_error());
	list($cname2)=mysql_fetch_row($cSqlBuffer);
	$temp=str_replace("{oid}",$cname2,$temp);
	$temp=str_replace("{mobi}",$mobi,$temp);
	$temp=str_replace("{Tid}",$Tid,$temp);
	$totleBuffer.=$temp;
}
$buffer=str_replace($whilebuffer,$totleBuffer,$buffer);


//=========添加
$buffer=str_replace("{postName}","man_xinxi.php?op=add",$buffer);

if($myAreas) {
    $ftidNameStr=getclassStr('0', "Tid in(".implode(",", $myAreas).")");
} else {
    $ftidNameStr=getclassStr('0', '1=2');
}
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
//=========添加

if($myAreas) {
    $ftidNameStr=getclassStr($_REQUEST["classid"], "Tid in(".implode(",", $myAreas).")");
} else {
    $ftidNameStr=getclassStr($_REQUEST["classid"], '1=2');
}
$buffer=str_replace("{classidStr}",$ftidNameStr,$buffer);
$buffer=str_replace("{pageStr}",$pagenav,$buffer);
$buffer=str_replace("{postName}","man_xinxi.php?op=add",$buffer);
include($cfg_inc_replace_path);
echo $buffer;
?>

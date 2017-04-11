<?php
require("config/config.php");
require ($cfg_inc_common_path);

$myAreas = getManagerAreas($sin_uid, $sin_manager);

$buffer=implode("",file($template_sw_path));
$sql="select Tid,oid,name,mobi,id_card,id_shebao,bjz_date,ejz_date,jz_type,jg_grade,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr";
$wheres = [];
$wheres[] = "regstate<2";
$wheres[] = "status>0";
//搜索
$GetUrl = "";
if($_REQUEST["submit"])
{
		$GetUrl=$_SERVER["PHP_SELF"]."?submit=ok&";
		if($_REQUEST["sname"])
		{
			$sname=$_REQUEST["sname"];
            $wheres[] = "name like '$sname%'";
			$GetUrl.="sname=$sname&";
		}
		
		if($_REQUEST["smobi"])
		{
			$smobi=$_REQUEST["smobi"];
            $wheres[] = "mobi like '$smobi%'";
			$GetUrl.="smobi=$smobi&";
		}

		if($_REQUEST["sid_card"])
		{
			$sid_card=$_REQUEST["sid_card"];
            $wheres[] = "id_card like '$sid_card%'";
			$GetUrl.="sid_card=$sid_card&";
		}		

		if($_REQUEST["area_id"])
		{
			$classid=$_REQUEST["area_id"];
			$classStr=$classid;
			$tempCid=",".$classid.",";
			$csql="select Tid from db_office where fstr like '%$tempCid%' and status>0";
			$csqlBuffer=mysql_query($csql)or die("error55");
			while(list($gTid)=mysql_fetch_row($csqlBuffer))
			{
				$classStr.=",".$gTid;
			}
            $wheres[] = "oid in ($classStr)";
			$GetUrl.="area_id=$classid&";
		}
		
		if($_REQUEST["sjz_type"]!="" and $_REQUEST["sjz_type"]!="999")
		{
			$sjz_type=$_REQUEST["sjz_type"];
            $wheres[] = "jz_type ='$sjz_type'";
			$GetUrl.="sjz_type=$sjz_type&";
		}
		
		if($_REQUEST["sjg_grade"]!="" and $_REQUEST["sjg_grade"]!="999")
		{
			$sjg_grade=$_REQUEST["sjg_grade"];
            $wheres[] = "jg_grade ='$sjg_grade'";
			$GetUrl.="sjg_grade=$sjg_grade&";
		}
		
		if($_REQUEST["sregstate"]!="" and $_REQUEST["sregstate"]!="999")
		{
			$sregstate=$_REQUEST["sregstate"];
            $wheres[] = "regstate ='$sregstate'";
			$GetUrl.="sregstate=$sregstate&";
		}
}
if($myAreas) {
    $wheres[] = "oid in (".implode(',', $myAreas).")";
} else {
    $wheres[] = "1=2";
}

$sql .= " where " . implode(" AND ", $wheres);

$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$totleSta=mysql_num_rows($sqlBuffer);
pageft($totleSta,$admin_page_config,$GetUrl);
$sql.=" order by Tid desc";
$sql.=" limit $firstcount,$displaypg";
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$whilebuffer=Template("$template_sw_path","<!--begin-->","<!--end-->");
$totleBuffer="";
while(list($Tid,$oid,$name,$mobi,$id_card,$id_shebao,$bjz_date,$ejz_date,$jz_type,$jg_grade,$regstate,$up_bdate,$up_edate,$dowm_bdate,$dowm_edate)=mysql_fetch_row($sqlBuffer))
{
	$regstate=intval($regstate);
	$temp=$whilebuffer;
	$jg_grade=intval($jg_grade);
	$jg_gradeValue=$jg_grade_row[$jg_grade];
	$temp=str_replace("{jg_grade}",$jg_gradeValue,$temp);
	
	$regstate=intval($regstate);
	$regstateValue=$regstate_grade_row[$regstate];
	$temp=str_replace("{regstate}",$regstateValue,$temp);
	
	$temp=str_replace("{name}",$name,$temp);
	$cSql="select name from db_office where Tid='$oid' and status>0";
	$cSqlBuffer=mysql_query($cSql)or die("error548".mysql_error());
	list($cname2)=mysql_fetch_row($cSqlBuffer);
	$temp=str_replace("{oid}",$cname2,$temp);
	$temp=str_replace("{mobi}",$mobi,$temp);
	$temp=str_replace("{id_card}",$id_card,$temp);
	$temp=str_replace("{id_shebao}",$id_shebao,$temp);
	$temp=str_replace("{Tid}",$Tid,$temp);
	$bjz_dateRow=explode(" ",$bjz_date);
	$bjz_date1=$bjz_dateRow[0];
	
	$ejz_dateRow=explode(" ",$ejz_date);
	$ejz_date1=$ejz_dateRow[0];
	
	if($regstate==0)
	{
		$temp=str_replace("{op}","<a href='###' onclick=\"opfun('sw_op.php?type=dj&Tid=$Tid')\">登记</a>",$temp);
	}
	elseif($regstate==1)
	{
		$temp=str_replace("{op}","<a href='###'  onclick=\"opfun2('sw_op.php?type=yy&Tid=$Tid')\">语音</a>&nbsp;<a href='###'  onclick=\"opfun('sw_op.php?type=rz&Tid=$Tid')\">验证</a>&nbsp;<a onClick=\"return confirm('确认要删除？')\" href='sw_op.php?type=del&Tid=$Tid' target='ifrm'>删除</a>",$temp);
	}
	else
	{
		$temp=str_replace("{op}","",$temp);
	}
	
	
	$temp=str_replace("{bjz_date}",$bjz_date1,$temp);
	$temp=str_replace("{ejz_date}",$ejz_date1,$temp);
	$totleBuffer.=$temp;
}
$buffer=str_replace($whilebuffer,$totleBuffer,$buffer);

//矫正状态
$optionBuffer=optionStr($jz_type_row,"-1","");
$buffer=str_replace("{sjz_type}",$optionBuffer,$buffer);
//监管级别
$optionBuffer=optionStr($jg_grade_row,"-1","");
$buffer=str_replace("{sjg_grade}",$optionBuffer,$buffer);

$optionBuffer=optionStr($regstate_grade_row,"-1","");
$buffer=str_replace("{sregstate_Str}",$optionBuffer,$buffer);

$areaId = getFromArray($_REQUEST, "area_id", 0);
if($myAreas) {
    $ftidNameStr=getclassStr($areaId, "Tid in(".implode(",", $myAreas).")");
} else {
    $ftidNameStr=getclassStr($areaId, '1=2');
}
$buffer=str_replace("{areaIdStr}",$ftidNameStr,$buffer);
$buffer=str_replace("{pageStr}",$pagenav,$buffer);
$buffer=str_replace("{postName}",$_SERVER['PHP_SELF'],$buffer);
include($cfg_inc_replace_path);
echo $buffer;
?>

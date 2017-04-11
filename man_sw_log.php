<?php
require("config/config.php");
require($client_path);
require ($cfg_inc_common_path);

$myAreas = getManagerAreas($sin_uid, $sin_manager);

$buffer=implode("",file($template_man_sw_path));
$sql="select g.Tid,g.uid,g.type,g.yzdate,g.up_yzdate,g.server_state,g.state,g.sw_state,g.sw_url,g.end_yzdate,g.sh_state,g.shjg,i.mobi,i.name,i.oid from db_ivr_log as g,db_ivr as i where g.uid=i.Tid";
if($myAreas) {
    $sql .= " AND i.oid IN(".implode(",", $myAreas).")";
} else {
    $sql .= " AND 1=2";
}

$GetUrl = "";
//g.flag=0 and
//搜索
if($_REQUEST["submit"])
{
		$GetUrl=$_SERVER["PHP_SELF"]."?submit=ok&";
		if($_REQUEST["sname"])
		{
			$sname=$_REQUEST["sname"];
			$sql.=" and i.name like '%$sname%'";
			$GetUrl.="sname=$sname&";
		}
		
		if($_REQUEST["smobi"])
		{
			$smobi=$_REQUEST["smobi"];
			$sql.=" and i.mobi like '%$smobi%'";
			$GetUrl.="smobi=$smobi&";
		}
		
		if($_REQUEST["classid"])
		{
			$classid=$_REQUEST["classid"];
			$classStr=$classid;
			$tempCid=",".$classid.",";
			$csql="select Tid from db_office where fstr like '%$tempCid%'";
			$csqlBuffer=mysql_query($csql)or die("error55");
			while(list($gTid)=mysql_fetch_row($csqlBuffer))
			{
				$classStr.=",".$gTid;
			}
			$sql.=" and i.oid in ($classStr)";
			$GetUrl.="classid=$classid&";
		}
		
		if($_REQUEST["bjz_date"]!="")
		{
			$bjz_date=$_REQUEST["bjz_date"];
			$bjz_dateRow=explode(" ",$bjz_date);
			$bjz_date1=$bjz_dateRow[0];
			$sql.=" and g.yzdate >='$bjz_date1 00:00:00'";
			$GetUrl.="bjz_date=$bjz_date1&";
		}
		
		if($_REQUEST["ejz_date"]!="")
		{
			$ejz_date=$_REQUEST["ejz_date"];
			$ejz_dateRow=explode(" ",$ejz_date);
			$ejz_date1=$ejz_dateRow[0];
			$sql.=" and g.yzdate <='$ejz_date1 23:59:59'";
			$GetUrl.="ejz_date=$ejz_date1&";
		}
		
		if($_REQUEST["sstate"]!="" and $_REQUEST["sstate"]!="999")
		{
			$sstate=$_REQUEST["sstate"];
			$sql.=" and g.state ='$sstate'";
			$GetUrl.="sstate=$sstate&";
		}
		
		if($_REQUEST["s_sw_state"]!="" and $_REQUEST["s_sw_state"]!="9999")
		{
			$s_sw_state=$_REQUEST["s_sw_state"];
			$sql.=" and g.sw_state ='$s_sw_state'";
			$GetUrl.="s_sw_state=$s_sw_state&";
		}
		
		if($_REQUEST["ssh_state"])
		{
			$ssh_state=$_REQUEST["ssh_state"];
			$sql.=" and g.sh_state ='$ssh_state'";
			$GetUrl.="ssh_state=$ssh_state&";
		}
		
}
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$totleSta=mysql_num_rows($sqlBuffer);
pageft($totleSta,$admin_page_config,$GetUrl);
$sql.=" order by Tid desc";
$sql.=" limit $firstcount,$displaypg";
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$whilebuffer=Template("$template_man_sw_path","<!--begin-->","<!--end-->");
$totleBuffer="";
while(list($Tid,$uid,$type,$yzdate,$up_yzdate,$server_state,$state,$sw_state,$sw_url,$end_yzdate,$sh_state,$shjg,$mobi,$name,$oid)=mysql_fetch_row($sqlBuffer))
{
	$temp=$whilebuffer;
	$cSql="select name from db_office where Tid='$oid'";
	$cSqlBuffer=mysql_query($cSql)or die("error548".mysql_error());
	list($cname2)=mysql_fetch_row($cSqlBuffer);
	$temp=str_replace("{oid}",$cname2,$temp);
	$temp=str_replace("{name}",$name,$temp);
	$temp=str_replace("{mobi}",$mobi,$temp);
	$temp=str_replace("{yzdate}",$yzdate,$temp);
	if($type)
	{
		$temp=str_replace("{typeStr}","自动",$temp);
	}
	else
	{
		$temp=str_replace("{typeStr}","人工",$temp);
	}
	
	$state=intval($state);
	$stateValue=$sw_state_arr[$state];
	$temp=str_replace("{state}",$stateValue,$temp);
	$temp=str_replace("{Tid}",$Tid,$temp);
	

	$sw_stateValue=$sw_state_type_row[$sw_state];
	$temp=str_replace("{sw_state}",$sw_stateValue,$temp);
	//审核结果
	$sh_state=intval($sh_state);
	$sh_stateValue=$sh_state_arr[$sh_state];
	if($sh_state==0)
	{
		$temp=str_replace("{sh_state}","待审核",$temp);
	}
	else
	{
		$temp=str_replace("{sh_state}",$sh_stateValue,$temp);
	}
	$temp=str_replace("{shjg}",$shjg,$temp);
	$url="$web_path/man_sw_shenhe.php?Tid=$Tid";
	
	$urlyuyin="$web_path/sw_op.php?type=logyy&Tid=$Tid";
	$temp=str_replace("{op}","<a onclick=\"opfun2('$urlyuyin')\" href='###'>语音</a>&nbsp;&nbsp;<a href='###' onclick=\"opfun('$url')\">审核</a>",$temp);
	$totleBuffer.=$temp;
}
$buffer=str_replace($whilebuffer,$totleBuffer,$buffer);

//声纹审核结果
$optionBuffer=optionStr($sw_state_type_row,"-9999","");
$buffer=str_replace("{sw_stateStr}",$optionBuffer,$buffer);


//声纹审核结果
$optionBuffer=optionStr($sh_state_arr,"-1","");
$buffer=str_replace("{sh_state_Str}",$optionBuffer,$buffer);
//声纹结果
$optionBuffer=optionStr($sw_state_arr,"-1","");
$buffer=str_replace("{state_value}",$optionBuffer,$buffer);

if($myAreas) {
    $ftidNameStr=getclassStr($_REQUEST["classid"], "Tid in(".implode(",", $myAreas).")");
} else {
    $ftidNameStr=getclassStr($_REQUEST["classid"], '1=2');
}
$buffer=str_replace("{classidStr}",$ftidNameStr,$buffer);
$buffer=str_replace("{pageStr}",$pagenav,$buffer);
$buffer=str_replace("{postName}",$_SERVER['PHP_SELF'],$buffer);
include($cfg_inc_replace_path);
echo $buffer;
?>

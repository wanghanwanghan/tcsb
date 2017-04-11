<?php
require("config/config.php");

require ($cfg_inc_common_path);

$Tid=$_GET['Tid'];
$m5=$_GET['m5'];
$t=$_GET['t'];
$trueMd5=$m_key_md5.$Tid.$t;
$trueMd5Str=md5($trueMd5);
if($trueMd5Str!=$m5)
{
echo "<script>parent.document.getElementById(\"tie\").innerHTML='数据发送验证失败01!';parent.document.getElementById(\"tie3\").innerHTML='';</script>";
exit;	
}
//开始进行判断
$sql="select flag,state,server_state,up_yzdate,uid from db_ivr_log where Tid='$Tid'";
$sqlsBuffer=mysql_query($sql)or die("error548");
list($flag,$state,$server_state,$up_yzdate,$uid)=mysql_fetch_row($sqlsBuffer);
mysql_close();
$server_state=intval($server_state);
$server_stateValue=$service_state_row[$server_state];
if($server_state!="99999")
{
	echo "<script>parent.document.getElementById(\"tie\").innerHTML='$server_stateValue';</script>";
}
//如果是登记的
if($flag==0)
{
	require($mysql_path);
	$sql2="select Tid,regstate from db_ivr where Tid='$uid' and status>0";
	$sqlsBuffer2=mysql_query($sql2)or die("error548");
	list($aTid,$regstate)=mysql_fetch_row($sqlsBuffer2);
	mysql_close();
	if($aTid)
	{
		if($regstate==1)
		{
			$opStr="op_".$uid;
			$regStr="reg_".$uid;
echo "<script>parent.parent.document.getElementById(\"$regStr\").innerHTML='已注册';parent.parent.document.getElementById(\"$opStr\").innerHTML=\"<a href='sw_op.php?type=yy&Tid=$uid' target='_blank'>语音</a>&nbsp;&nbsp;<a href='###'  onclick=\\\"opfun('sw_op.php?type=rz&Tid=$uid')\\\">声纹</a>&nbsp;&nbsp;<a href='sw_op.php?type=del&Tid=$Tid' target='ifrm'>删除</a>\";</script>";
		}
	}
}

if($server_state=="999" or $server_state=="-2" or $server_state=="-4" or $server_state=="-5" or $server_state=="-6" or $server_state=="0"  or $server_state=="3"  or $server_state=="10"  or $server_state=="11" or $server_state=="12")
{
	echo "<script>parent.document.getElementById(\"tie3\").innerHTML='';</script>";
	exit;
}
//判断最后验证时间是否超过。
$upTime=secChangeTime($up_yzdate);
$nowTime=time();
$centerTime=$nowTime-$upTime;
if($centerTime>$cfg_error_Sec)
{
	require($mysql_path);
	$uSql="update db_ivr_log set end_yzdate=now(),state=1,sw_state='5',server_state='999' where Tid='$Tid'";
	mysql_query($uSql)or die("error14".mysql_error());
	mysql_close();
}


$buffer=implode("",file($template_shuaxin_sw_path));
$buffer=str_replace("{cfg_warmSec}",$cfg_warmSec,$buffer);
$url="$web_path/ifrm_task.php?Tid=".$Tid.'&m5='.$m5.'&t='.$t;
$buffer=str_replace("{shuaxin}","Load('$url');",$buffer);
echo $buffer;
?>
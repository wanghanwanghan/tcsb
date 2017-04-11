<?php
require("config/config.php");
require($client_path);
require ($cfg_inc_common_path);

$buffer=implode("",file($template_result_shenhe_path));
$Tid=$_GET['Tid'];
$Tid=intval($Tid);
if($_POST['btn2'])
{
	$sh_state=$_POST['sh_state'];
	$shjg=$_POST['shjg'];
	$usql="update db_ivr_log set shjg='$shjg',sh_state='$sh_state' where Tid='$Tid'";
	mysql_query($usql)or die("error54");	
	$sh_stateValue=$sh_state_arr[$sh_state];
	$btn2="reg_$Tid";
	$btn3="sh_$Tid";
	echo "<script>alert('审核成功！');parent.document.getElementById(\"$btn2\").innerHTML='$sh_stateValue';parent.document.getElementById(\"$btn3\").innerHTML='$shjg';parent.funclose()</script>";
exit;
}

$sql="select flag,state,server_state,sh_state,shjg from db_ivr_log where Tid='$Tid'";
$sqlsBuffer=mysql_query($sql)or die("error548");
list($flag,$state,$server_state,$sh_state,$shjg)=mysql_fetch_row($sqlsBuffer);
$optionBuffer=optionStr($sh_state_arr,$sh_state,"");
$buffer=str_replace("{sh_state}",$optionBuffer,$buffer);
$buffer=str_replace("{shjg}",$shjg,$buffer);
$buffer=str_replace("{title}","审核",$buffer);
$buffer=str_replace("{postName}","$_SERVER[PHP_SELF]"."?Tid=$Tid",$buffer);
mysql_close();
include($cfg_inc_replace_path);
echo $buffer;
exit;
?>
<?php
/*
角色列表
*/
require("../config/config.php");
require ($cfg_inc_common_path);

$templateFile = $template_mk."/role/man_role.html";
$buffer=implode("",file($templateFile));

$sql="select id, name, remark from db_roles where status>0";
$whilebuffer=Template($templateFile,"<!--begin-->","<!--end-->");
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$totleBuffer="";
while(list($rid,$name, $remark)=mysql_fetch_row($sqlBuffer))
{
	$temp=$whilebuffer;
	$temp=str_replace("{ID}",$rid,$temp);
	$temp=str_replace("{roleName}",$name,$temp);
	$temp=str_replace("{remark}",$remark,$temp);
	$totleBuffer.=$temp;
}

$buffer=str_replace($whilebuffer, $totleBuffer,$buffer);

mysql_close();
include($cfg_inc_replace_path);
$buffer=str_replace("{postName}",$_SERVER[PHP_SELF],$buffer);
$buffer=str_replace("{sStateStr}",$employStr,$buffer);
$buffer=str_replace("{pageStr}",$pagenav,$buffer);
$buffer=str_replace("{posiStr}","角色管理 >> 管理员管理",$buffer);
echo $buffer;
?>
